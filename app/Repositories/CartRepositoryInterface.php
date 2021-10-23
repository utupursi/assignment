<?php

namespace App\Repositories;

use Illuminate\Http\Request;

interface CartRepositoryInterface
{
    public function getData(Request $request, array $relation = []);

    public function create(array $attributes = []);

    public function deleteFromCart(int $productId);
}
