<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class TicketHistory extends Model
{
    use HasFactory;

    protected $primaryKey = 'history_id';

    protected $fillable = [
        'ticket_id',
        'user_id',
        'action_type_id',
        'changed_field',
        'old_value',
        'new_value',
        'recorded_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    /**
     * Get the ticket that the history belongs to.
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'ticket_id');
    }

    /**
     * Get the user who performed the action.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the action type of the history entry.
     */
    public function actionType()
    {
        return $this->belongsTo(ActionType::class, 'action_type_id', 'action_type_id');
    }
}