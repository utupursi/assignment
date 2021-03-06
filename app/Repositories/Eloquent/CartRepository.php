<?php

namespace App\Repositories\Eloquent;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use App\Repositories\CartRepositoryInterface;
use App\Repositories\Eloquent\Base\BaseRepository;
use App\Repositories\ProductRepositoryInterface;
use http\Env\Response;
use Illuminate\Support\Facades\Session;
use Laravel\Sanctum\PersonalAccessToken;

class CartRepository extends BaseRepository implements CartRepositoryInterface
{

    public function __construct(Cart $model)
    {
        parent::__construct($model);
    }


    public function create(array $attributes = [])
    {
        $product = Product::find($attributes['product_id']);

        if (!$product) {
            return ['message' => 'Product was not found', 'code' => 400];
        }

        if (auth('sanctum')->user()) {
            $attributes['user_id'] = auth('sanctum')->user()->id;
            $cart = $this->model->where(['user_id' => $attributes['user_id'], 'product_id' => $attributes['product_id']])->first();

            if ($cart && $product->quantity <= $cart->quantity) {
                return ['message' => 'Too many product quantity', 'code' => 400];
            }
        }

        if ($product->quantity < $attributes['quantity']) {
            return ['message' => 'Too many product quantity', 'code' => 400];
        }

        try {
            if (auth('sanctum')->user() && $cart) {
                $attributes['quantity'] += $cart->quantity;
                $cart->update($attributes);
                return $this->model->withTrashed()->find($cart->id);
            }
            return $this->model->create($attributes);
        } catch (\Illuminate\Database\QueryException $exception) {
            return ['message' => $exception->errorInfo, 'code' => 500];
        }
    }

    public function deleteFromCart(int $productId)
    {
        $cart = $this->model->where(['product_id' => $productId, 'user_id' => auth('sanctum')->user()->id])->first();

        if ($cart) {
            $cart->delete();
            return $this->model->withTrashed()->find($cart->id);
        }
        return ['message' => 'product does not exist', 'code' => 400];
    }

}
