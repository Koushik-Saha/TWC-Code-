<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /**
     * The table associated with the Model.
     * @var string
     */
    protected $table = 'bsoft_items';

    /**
     * The primary key associated with the table.
     * @var string
     */
    protected $primaryKey = 'item_id';

    public function itemLogs() {
        return $this->hasMany(ItemLog::class, 'il_item_id', 'item_id');
    }
    public function itemLog() {
        return $this->belongsTo(ItemLog::class, 'il_item_id', 'item_id');
    }
}
