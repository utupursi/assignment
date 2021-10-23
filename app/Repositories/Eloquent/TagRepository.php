<?php

namespace App\Repositories\Eloquent;

use App\Http\Request\LoginRequest;
use App\Http\Request\RegisterRequest;
use App\Models\Article;
use App\Models\Tag;
use App\Models\User;
use App\Models\UserToken;
use App\Repositories\ArticleRepositoryInterface;
use App\Repositories\AuthRepositoryInterface;
use App\Repositories\Eloquent\Base\BaseRepository;
use App\Repositories\TagRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TagRepository extends BaseRepository implements TagRepositoryInterface
{

    public function __construct(Tag $model)
    {
        parent::__construct($model);
    }

    /**
     * @param Request $request
     * @param array $relation
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|mixed
     */
    public function getData($request, array $relation = [])
    {

        $data = $this->model->query();
        $sort = 'article_count';
        $order = 'desc';

        $data->withCount('article');
        $this->orderItems($request, $data, $sort, $order);

        return $data->get();
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|\Illuminate\Support\HigherOrderCollectionProxy|mixed
     */
    public function getTagArticles(Request $request, int $id)
    {
        $sort = 'created_at';
        $order = 'desc';
        $limit = 10;

        $data = $this->model->with(['articles' => function ($query) use ($request, $sort, $order, $limit) {
            $query->withCount('comment');
            $this->orderItems($request, $query, $sort, $order);
            $query->take($request['limit'] ?: $limit);
            if ($request['paginate']) {
                $query->paginate($request['paginate'])->getCollection();
            }
        }])->findOrFail($id);

        return $data->articles;
    }


    public function orderItems($request, $data, $sort, $order)
    {

        if ($request['sort'] == 'article_count' && $request['order']) {
            $data->orderBy('article_count', $request['order']);
        } elseif ($request['sort'] == 'article_count' && !$request['order']) {
            $data->orderBy('article_count', $order);
        }

        if ($request['sort'] == 'comment_count' && $request['order']) {
            $data->orderBy('comment_count', $request['order']);
        } elseif ($request['sort'] == 'comment_count' && !$request['order']) {
            $data->orderBy('comment_count', $order);
        }


        if ($request['sort'] === "created_at" && $request['order']) {
            $data->orderBy('created_at', $request['order']);
        } elseif ($request['sort'] == 'created_at' && !$request['order']) {
            $data->orderBy('created_at', $order);
        }

        if (!$request['sort'] && !$request['order']) {
            $data->orderBy($sort, $order);
        }

        if (!$request['sort'] && $request['order']) {
            $data->orderBy($sort, $request['order']);
        }

    }


}
