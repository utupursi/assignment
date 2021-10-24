<?php

namespace App\Repositories;

use Illuminate\Http\Request;

interface OrderRepositoryInterface
{
    public function getData(Request $request, array $relation = []);

    public function create(array $attributes = []);

//    public function deleteFromCart(int $productId);
}
