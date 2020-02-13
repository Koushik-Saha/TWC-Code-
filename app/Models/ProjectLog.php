<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectLog extends Model
{
    /**
     * The table associated with the Model.
     * @var string
     */
    protected $table = 'bsoft_project_logs';

    /**
     * The primary key associated with the table.
     * @var string
     */
    protected $primaryKey = 'pl_id';

    public function project() {
        return $this->belongsTo(ProjectLog::class, 'pl_project_id', 'project_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'pl_user_id', 'id');
    }
}
