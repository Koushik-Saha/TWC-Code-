<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    /**
     * The table associated with the Model.
     * @var string
     */
    protected $table = 'bsoft_invoice_details';

    /**
     * The primary key associated with the table.
     * @var string
     */
    protected $primaryKey = 'inv_id';
    /**
     * Indicates if the model should be timestamped.
     * @var bool
     */
    const UPDATED_AT = false;
}
