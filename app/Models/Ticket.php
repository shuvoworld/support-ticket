<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ticket extends Model
{
    protected $fillable = [
        'subject',
        'content',
        'priority',
        'user_id',
        'agent_id',
        'category_id',
        'status_id',
        'department_id',
        'closed_at',
    ];

    protected $casts = [
        'closed_at' => 'datetime',
        'priority' => 'string',
    ];

    protected static function booted()
    {
        static::creating(function ($ticket) {
            // If no status is set, default to "Open"
            if (!$ticket->status_id) {
                $openStatus = \App\Models\Status::where('name', 'Open')->first();
                if ($openStatus) {
                    $ticket->status_id = $openStatus->id;
                }
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function publicComments(): HasMany
    {
        return $this->hasMany(Comment::class)->where('is_internal', false);
    }

    public function internalNotes(): HasMany
    {
        return $this->hasMany(Comment::class)->where('is_internal', true);
    }

    public function latestComment()
    {
        return $this->hasOne(Comment::class)->where('is_internal', false)->latest();
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
