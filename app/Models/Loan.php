<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    /**
     * The table associated with the Model.
     * @var string
     */
    protected $table = 'bsoft_loans';

    /**
     * The primary key associated with the table.
     * @var string
     */
    protected $primaryKey = 'loan_id';
}
