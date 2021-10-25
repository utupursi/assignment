<?php

namespace App\Http\Controllers;

use App\Http\Request\CartRequest;
use App\Http\Request\ProductRequest;
use App\Http\Resources\CartResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ProductResource;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Repositories\CartRepositoryInterface;
use App\Repositories\OrderRepositoryInterface;
use App\Repositories\ProductRepositoryInterface;
use http\Client\Curl\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;


class OrderController extends Controller
{
    /**
     * @var ProductRepositoryInterface
     */
    protected $orderRepository;

    /**
     * productController constructor.
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }


    /**
     * @param Request $request
     * @return CartResource|JsonResponse
     */
    public function makeOrder(Request $request)
    {
        $validator = Validator::make($request->all(), ['products' => 'required|array', 'pay_method' => 'required|string|max:255']);

        if ($validator->fails()) {
            return response()->json(['success' => 'false', 'message' => $validator->messages()], 400);
        }

        $cart = $this->orderRepository->create($request->all());

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
            'total_price' => 'required|numeric|max:1000000|gt:0',
        ];

        return $rules;
    }

    public function response($data)
    {
        if ($data instanceof Order) {
            return new OrderResource($data);
        }
        return response()->json(['success' => 'false', 'message' => $data['message']], $data['code']);
    }


}
