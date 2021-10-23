<?php

namespace App\Repositories;

use Illuminate\Http\Request;

interface ArticleRepositoryInterface
{
    public function getData(Request $request,array $relation=[]);
    public function getArticleComments(Request $request,int $id);
//    public function delete(Request $request);
}
