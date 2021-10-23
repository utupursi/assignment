<?php

namespace App\Http\Controllers;

use App\Http\Request\CartRequest;
use App\Http\Request\ProductRequest;
use App\Http\Resources\CartResource;
use App\Http\Resources\ProductResource;
use App\Models\Cart;
use App\Models\Product;
use App\Repositories\CartRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use http\Client\Curl\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;


class CartController extends Controller
{
    /**
     * @var ProductRepositoryInterface
     */
    protected $cartRepository;

    /**
     * productController constructor.
     * @param CartRepositoryInterface $cartRepository
     */
    public function __construct(CartRepositoryInterface $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }


    /**
     * @param Request $request
     * @return CartResource|JsonResponse
     */
    public function addToCart(Request $request)
    {
        $rules = $this->validateCartParams();
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => 'false', 'message' => $validator->messages()], 400);
        }

        $cart = $this->cartRepository->create($request->all());

        return $this->response($cart);
    }

    public function deleteFromCart(Request $request)
    {
        $validator = Validator::make($request->all(), ['product_id' => 'required|numeric']);

        if ($validator->fails()) {
            return response()->json(['success' => 'false', 'message' => $validator->messages()], 400);
        }

        $cart = $this->cartRepository->deleteFromCart($request['product_id']);

        return $this->response($cart);

    }

    /**
     * @param $request
     * @return array
     */
    public function validateCartParams()
    {
        $rules = [
            'product_id' => 'required|numeric',
            'quantity' => 'required|numeric',
            'total_price' => 'required|numeric|max:1000000',
        ];

        return $rules;
    }

    public function response($data)
    {
        if ($data instanceof Cart) {
            return new CartResource($data);
        }
        return response()->json(['success' => 'false', 'message' => $data['message']], $data['code']);
    }


}
