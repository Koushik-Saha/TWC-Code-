<?php

namespace App\Models;

use App\Models\MotherCategory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubCategory;

class Category extends Model
{
    protected $fillable=['category_name','mother_category_id','active'];

//    public function motherCategory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
//    {
//        return $this->belongsTo(MotherCategory::class);
//    }
//
//    public function subCategories(): \Illuminate\Database\Eloquent\Relations\HasMany
//    {
//        return $this->hasMany(SubCategory::class,'category_id');
//    }

    public function inventory(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InventoryManagement::class);
    }
}
