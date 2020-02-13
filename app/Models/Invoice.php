<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    /**
     * The table associated with the Model.
     * @var string
     */
    protected $table = 'bsoft_invoices';

    /**
     * The primary key associated with the table.
     * @var string
     */
    protected $primaryKey = 'invoice_id';

    public function itemLog() {
        return $this->belongsTo(ItemLog::class, 'invoice_item_log', 'il_id');
    }
}
