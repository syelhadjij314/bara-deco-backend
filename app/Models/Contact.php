<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    const STATUS_NEW      = 'new';
    const STATUS_READ     = 'read';
    const STATUS_REPLIED  = 'replied';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'service',
        'message',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function scopeUnread($query)
    {
        return $query->where('status', self::STATUS_NEW);
    }

    public function markAsRead(): void
    {
        if ($this->status === self::STATUS_NEW) {
            $this->update(['status' => self::STATUS_READ]);
        }
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_NEW     => 'Nouveau',
            self::STATUS_READ    => 'Lu',
            self::STATUS_REPLIED => 'Répondu',
            default              => $this->status,
        };
    }
}
