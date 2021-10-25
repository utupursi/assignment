<?php

namespace App\Repositories\Eloquent;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\User;
use App\Repositories\CartRepositoryInterface;
use App\Repositories\Eloquent\Base\BaseRepository;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{

    public function __construct(Order $model)
    {
        parent::__construct($model);
    }


    public function create(array $attributes = [])
    {
//        $product = Product::find($attributes['product_id']);

        $carts = Cart::whereIn('product_id', $attributes['products'])->where(['user_id' => auth('sanctum')->user()->id])->get();

        if (count($carts) == 0) {
            return ['message' => 'Products was not found in cart', 'code' => 400];
        }

        $totalPrice = 0;
        $orderProducts = [];

        foreach ($carts as $cart) {
            $totalPrice += $cart->total_price * $cart->quantity;
        }
        if (auth('sanctum')->user()->balance < $totalPrice) {
            return ['message' => 'You do not have enough money', 'code' => 400];
        }

        DB::beginTransaction();

        try {
            $order = $this->model->create([
                'user_id' => auth('sanctum')->user()->id,
                'status' => 'pending',
                'total_price' => $totalPrice,
                'payment_method' => $attributes['pay_method']
            ]);

            foreach ($carts as $cart) {
                $orderProducts[] = [
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'price' => $cart->total_price,
                    'quantity' => $cart->quantity,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];

                $product = Product::where(['id' => $cart->product_id])->first();
                if ($product->quantity < $cart->quantity) {
                    throw new \Exception('Too much product quantity-' . $product->title);
                }
                if ($product) {
                    $product->quantity = $product->quantity - $cart->quantity;
                    $product->save();
                }
                $ids[] = $cart->id;

            }

            if ($order) {
                OrderProduct::insert($orderProducts);
            }

            auth('sanctum')->user()->update([
                'balance' => auth('sanctum')->user()->balance - $totalPrice
            ]);

            Cart::whereIn('id', $ids)->delete();

            DB::commit();

            return $order;
        } catch (\Illuminate\Database\QueryException $exception) {
            DB::rollback();
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
