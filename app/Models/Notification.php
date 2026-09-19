<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $primaryKey = 'notification_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'notification_type',
        'title',
        'message',
        'reference_type',
        'reference_id',
        'read_at',
        'is_deleted',
        'date_created',
        'date_deleted',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
            'is_deleted' => 'boolean',
            'date_created' => 'datetime',
            'date_deleted' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
