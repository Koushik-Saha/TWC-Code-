<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    protected $fillable=[
        'item_id',
        'price',
        'vat',
        'quantity',
        'amount',
        'request_code',
        'user_id',
        'status',
        'payment_amount',
        'cartId',
        'status'
    ];

    public function userRole(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,'user_id','id');
    }






}
