<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id',
        'status',
        'total_price',
        'payment_method',
    ];

    public function products()
    {
        return $this->hasMany('App\Models\OrderProduct', 'order_id');
    }

    public function totalprice()
    {
        $total = 0;
        foreach ($this->products as $item) {
            $total += $item->quantity * $item->price;
        }
        return $total;
    }

    public function loan()
    {
        return $this->morphOne('App\Models\Loan', 'loanable');
    }

    public function tbcLoan(): MorphOne
    {
        return $this->morphOne(TbcLoan::class, 'tbcloanable');
    }

    public function bank()
    {
        return $this->hasOne(Bank::class, 'id', 'bank_id');
    }

    public function paymentType()
    {
        return $this->hasOne(PaymentType::class, 'id', 'payment_type_id');
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
