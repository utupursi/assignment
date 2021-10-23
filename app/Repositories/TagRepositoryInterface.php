<?php

namespace App\Repositories;

use App\Http\Request\LoginRequest;

use App\Http\Request\RegisterRequest;
use Illuminate\Http\Request;

interface TagRepositoryInterface
{
    public function getData(Request $request, array $relation = []);

    public function getTagArticles(Request $request, int $id);

}
