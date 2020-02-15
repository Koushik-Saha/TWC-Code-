<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestItem extends Model
{
    protected $fillable=[
        'mother_category_id',
        'category_id',
        'sub_category_id',
        'manufacture_id',
        'item_id',
        'price',
        'vat',
        'quantity',
        'amount',
        'project_id',
        'request_date',
        'request_code',
        'request_id',
        'requestItem_id'
        ];

    public function requestItem(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(InventoryManagement::class,'item_id','id');
    }

    public function requestUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,'request_id','id');
    }

    public function requestProject(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Project::class,'project_id','project_id');
    }


}
