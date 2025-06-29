<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $primaryKey = 'ticket_id';

    protected $fillable = [
        'reference_number', 'subject', 'description', 'resolved_at',
        'status_id', 'priority_id', 'customer_id',
        'created_by_user_id', 'assigned_agent_id', 'category_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'resolved_at' => 'datetime', 
        'created_at' => 'datetime',  
        'updated_at' => 'datetime',  
    ];


    // Define relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_user_id', 'id');
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_agent_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function status()
    {
        return $this->belongsTo(TicketStatus::class, 'status_id', 'status_id');
    }

    public function priority()
    {
        return $this->belongsTo(PriorityLevel::class, 'priority_id', 'priority_id');
    }

    public function comments()
    {
        return $this->hasMany(TicketComment::class, 'ticket_id', 'ticket_id');
    }

    public function history()
    {
        return $this->hasMany(TicketHistory::class, 'ticket_id', 'ticket_id');
    }
}