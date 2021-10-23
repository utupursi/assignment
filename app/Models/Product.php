<?php

namespace App\Models;

use App\Traits\ScopeFilter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;


class Product extends Model
{
    use HasFactory, ScopeFilter, SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'products';

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
        'slug',
        'price',
        'quantity'
    ];


//    public function comment()
//    {
//        return $this->hasMany(ArticleComment::class, 'article_id');
//    }
//
//    public function comments()
//    {
//        return $this->hasManyThrough(
//            Comment::class,
//            ArticleComment::class,
//            'article_id',
//            'id',
//            'id',
//            'comment_id'
//        );
//    }
//
//    public function tags()
//    {
//        return $this->hasManyThrough(
//            Tag::class,
//            ArticleTag::class,
//            'article_id',
//            'id',
//            'id',
//            'tag_id'
//        );
//    }


}
