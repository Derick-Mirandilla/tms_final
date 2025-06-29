<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles; 
use Spatie\Permission\Models\Role as SpatieRoleModel;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles; 


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'role_id', 
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
    // Define relationship to Role (Spatie's Role model)
    // This is crucial for retrieving the role based on the role_id column
    public function userRole() 
     {
            
        return $this->belongsTo(SpatieRoleModel::class, 'role_id');
    }


    // Relationships for created/assigned tickets, history, comments (as defined earlier)
    public function createdTickets()
    {
        return $this->hasMany(Ticket::class, 'created_by_user_id', 'id');
    }

    public function assignedTickets()
    {
        return $this->hasMany(Ticket::class, 'assigned_agent_id', 'id');
    }

    public function ticketHistories()
    {
        return $this->hasMany(TicketHistory::class, 'user_id', 'id');
    }

    public function ticketComments()
    {
        return $this->hasMany(TicketComment::class, 'user_id', 'id');
    }
}
