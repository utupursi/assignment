<?php

namespace App\Http\Controllers;

use App\Http\Request\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Repositories\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * productController constructor.
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }


    /**
     * @param ProductRequest $request
     * @return JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function addProduct(Request $request)
    {
        $rules = $this->validateProductParams();
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => 'false', 'message' => $validator->messages()], 400);
        }

        return new ProductResource($this->productRepository->create($request->all()));
    }

    public function deleteProduct(Request $request)
    {
        $product = Product::find($request['id']);
        if (!$product) {
            return response()->json(['success' => 'false', 'message' => "Product was not found"], 400);
        }

        return new ProductResource($this->productRepository->delete($product->id));
    }

    /**
     * @param $request
     * @return array
     */
    public function validateProductParams()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products',
            'price' => 'required|numeric|max:1000000',
            'quantity' => 'required|numeric',
        ];

        return $rules;
    }


}
