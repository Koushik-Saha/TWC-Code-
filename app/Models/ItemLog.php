<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemLog extends Model
{
    /**
     * The table associated with the Model.
     * @var string
     */
    protected $table = 'bsoft_item_logs';

    /**
     * The primary key associated with the table.
     * @var string
     */
    protected $primaryKey = 'il_id';

    public function vendor() {
        return $this->belongsTo(User::class, 'il_vendor_id', 'id');
    }

    public function project() {
        return $this->belongsTo(Project::class, 'il_project_id', 'project_id');
    }
    public function projectTransferredFrom() {
        return $this->belongsTo(Project::class, 'il_project_from', 'project_id');
    }

    public function item() {
        return $this->belongsTo(Item::class, 'il_item_id', 'item_id');
    }

    public function activity() {
        return $this->hasOne(Activity::class, 'activity_item_log_id', 'il_id');
    }

    public function invoice() {
        return $this->hasOne(Invoice::class, 'invoice_item_log_id', 'il_id');
    }
}
