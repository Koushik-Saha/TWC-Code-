<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\SubCategory;

class MotherCategory extends Model
{
    protected $fillable=['mother_name','active'];

//    public function categories(): \Illuminate\Database\Eloquent\Relations\HasMany
//    {
//        return $this->hasMany(Category::class,'mother_category_id');
//    }
//
//    public function subCategories(): \Illuminate\Database\Eloquent\Relations\HasMany
//    {
//        return $this->hasMany(SubCategory::class,'mother_category_id');
//    }

    public function inventory(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InventoryManagement::class);
    }

}
