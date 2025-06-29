<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionType extends Model
{
    protected $primaryKey = 'action_type_id';

    protected $fillable = [
        'type_name',
        'description',
    ];

    public function ticketHistories()
    {
        return $this->hasMany(TicketHistory::class, 'action_type_id', 'action_type_id');
    }

}
