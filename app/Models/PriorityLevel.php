<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriorityLevel extends Model
{
    use HasFactory;

    protected $primaryKey = 'priority_id';

    protected $fillable = [
        'priority_name',
        'description',
        'level',
    ];


    public function getCssClass(): string
    {
        return match ($this->priority_name) {
            'Low' => 'bg-green-100 text-green-800',
            'Medium' => 'bg-yellow-100 text-yellow-800',
            'High' => 'bg-red-100 text-red-800',
            default => 'bg-gray-200 text-gray-800', 
        };
    }

    /**
     * Define the relationship with tickets.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'priority_id', 'priority_id');
    }
}