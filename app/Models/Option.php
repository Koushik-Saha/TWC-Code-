<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    /**
     * The table associated with the Model.
     * @var string
     */
    protected $table = 'bsoft_options';

    /**
     * The primary key associated with the table.
     * @var string
     */
    protected $primaryKey = 'option_id';
    /**
     * Indicates if the model should be timestamped.
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = ['option_name', 'option_content'];
}
