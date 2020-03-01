<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /**
     * The table associated with the Model.
     *
     * @var string
     */
    protected $table = 'bsoft_projects';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'project_id';

    /**
     * The staffs that assigned to the project.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'bsoft_project_logs', 'pl_project_id', 'pl_user_id');
    }

    /**
     * Get the client that owns the Project.
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'project_client_id', 'id');
    }

    public function shifts() {
        return $this->hasMany(WorkingShift::class, 'shift_project_id', 'project_id');
    }

    public function attendances() {
        return $this->hasMany(Attendance::class, 'attendance_project_id', 'project_id');
    }

    public function items() {
        return $this->belongsToMany(Item::class, 'bsoft_item_logs', 'il_project_id', 'il_item_id');
    }

    public function transferredItems() {
        return $this->belongsToMany(Item::class, 'bsoft_item_logs', 'il_project_from', 'il_item_id');
    }

    public function employees() {
        return $this->belongsToMany(User::class, 'bsoft_project_logs', 'pl_project_id', 'pl_user_id');
    }

    public function itemLogs() {
        return $this->hasMany(ItemLog::class, 'il_project_id', 'project_id');
    }

    public function transferredItemLogs() {
        return $this->hasMany(ItemLog::class, 'il_project_from', 'project_id');
    }

    public function projectLogs() {
        return $this->hasMany(ProjectLog::class, 'pl_project_id', 'project_id');
    }

    public function payments() {
        return $this->hasMany(Payment::class, 'payment_for_project', 'project_id');
    }

    public function vendors() {
        $role_id = Role::whereRoleSlug('supplier')->firstOrFail()->role_id;
        return $this->employees()->where('role_id', '=', $role_id)->get();
    }

    public function requestProject(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RequestItem::class);
    }

    public function purchaseProject(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }


}
