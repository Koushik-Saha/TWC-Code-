<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MotherCategory;
use App\Models\Category;
use App\Models\Manufacture;

class SubCategory extends Model
{
    protected $fillable=['sub_category_name','mother_category_id','category_id','active'];

//    public function motherCategory(): \Illuminate\Database\Eloquent\Relations\BelongsTo
//    {
//        return $this->belongsTo(MotherCategory::class);
//    }
//    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
//    {
//        return $this->belongsTo(Category::class);
//    }

    public function inventory(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(InventoryManagement::class);
    }
}
