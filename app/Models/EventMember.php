<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventMember extends Model
{
    use HasFactory;

    protected $table = 'event_members';

    protected $primaryKey = 'event_member_id';

    public $timestamps = false;

    protected $fillable = [
        'event_id',
        'member_id',
        'is_deleted',
        'created_by',
        'date_created',
        'deleted_by',
        'date_deleted',
    ];

    protected function casts(): array
    {
        return [
            'is_deleted' => 'boolean',
            'date_created' => 'datetime',
            'date_deleted' => 'datetime',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(ChurchEvent::class, 'event_id', 'event_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id', 'member_id');
    }
}
