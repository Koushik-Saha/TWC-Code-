<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Role extends Model
{
    use Sluggable;
    /**
     * The table associated with the Model.
     * @var string
     */
     protected $table = 'bsoft_roles';

    /**
     * The primary key associated with the table.
     * @var string
     */
     protected $primaryKey = 'role_id';
    /**
     * Indicates if the model should be timestamped.
     * @var bool
     */
     public $timestamps = false;

    /**
     * Get the users for the role.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'role_id');
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'role_slug' => [
                'source' => 'role_name'
            ]
        ];
    }


}
