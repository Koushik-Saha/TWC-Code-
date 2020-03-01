<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The table associated with the Model.
     *
     * @var string
     */
    protected $table = 'bsoft_users';

    /**
     * The attributes that are mass assignable.
     *
     */
    /*protected $fillable = [
        'name', 'email', 'password',
    ];*/

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the role that owns the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    /**
     * Get the activities for the user.
     */
    public function activities()
    {
        return $this->hasMany(Activity::class, 'activity_of_user_id', 'id');
    }

    /**
     * Get the projects for the client user.
     */
    public function clientProjects()
    {
        return $this->hasMany(Project::class, 'project_client_id', 'id');
    }

    /**
     * The projects that assigned to the user.
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'bsoft_project_logs', 'pl_user_id', 'pl_project_id');
    }

    public function vendorProjects()
    {
        return $this->belongsToMany(Project::class, 'bsoft_item_logs', 'il_vendor_id', 'il_project_id');
    }

    public function attendances() {
        return $this->hasMany(Attendance::class, 'attendance_user_id', 'id');
    }

    public function itemLogs() {
        return $this->hasMany(ItemLog::class, 'il_vendor_id', 'id');
    }

    public function banks() {
        return $this->hasMany(BankAccount::class, 'bank_user_id', 'id');
    }

    public function clientPayments() {
        return $this->hasMany(Payment::class, 'payment_from_user', 'id');
    }

    public function vendorPayments() {
        return $this->hasMany(Payment::class, 'payment_to_user', 'id');
    }

    public function staffPayments() {
        return $this->hasMany(Payment::class, 'payment_to_user', 'id');
    }

    public function managerPayments() {
        return $this->hasMany(Payment::class, 'payment_to_user', 'id');
    }

    public function expenses() {
        return $this->hasMany(Payment::class, 'payment_from_user', 'id');
    }

    /*public function managerRefunds() {
        return $this->hasMany(Payment::class, 'payment_from_user', 'id');
    }*/

    public function payments() {
        return $this->hasMany(Payment::class, 'payment_from_user', 'id');
    }

    /**
     * @return bool
     */
    public function isAdmin() {
        return $this->role->role_slug === 'administrator';
    }

    /**
     * @return bool
     */
    public function isManager() {
        return $this->role->role_slug === 'manager';
    }

    /**
     * @return bool
     */
    public function isProjectManager() {
        return $this->role->role_slug === 'project_manager';
    }

    /**
     * @return bool
     */
    public function isAccountant() {
        return $this->role->role_slug === 'accountant';
    }

    public function addedBy() {
        $activity = Activity::whereIn('activity_note', ['Administrator Created', 'Staff Created', 'Vendor Created', 'Client Created'])
            ->where('activity_for_user_id', '=', $this->id)
            ->first();
        return ($activity) ? $activity->activityBy : null;
    }

    public function requestItem(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RequestItem::class);
    }

    public function requestUser(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RequestItem::class);
    }

    public function purchaseUser(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PurchaseItem::class,'id','user_id');
    }

//    public function userRole(): \Illuminate\Database\Eloquent\Relations\HasMany
//    {
//        return $this->hasMany(PurchaseItem::class);
//    }
}
