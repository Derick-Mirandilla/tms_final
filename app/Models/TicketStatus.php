<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketStatus extends Model
{
    use HasFactory;

    protected $primaryKey = 'status_id';

    protected $fillable = [
        'status_name',
        'description',
    ];

    public function getCssClass(): string
    {
        return match ($this->status_name) {
            'Open' => 'bg-blue-100 text-blue-800',
            'In Progress' => 'bg-yellow-100 text-yellow-800',
            'Escalated' => 'bg-red-100 text-red-800',
            'Resolved' => 'bg-green-100 text-green-800',
            'Closed' => 'bg-gray-100 text-gray-800',
            'Reopened' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-200 text-gray-800', // Fallback
        };
    }

    /**
     * Define the relationship with tickets.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'status_id', 'status_id');
    }
}