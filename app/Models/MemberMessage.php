<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberMessage extends Model
{
    use HasFactory;

    protected $table = 'member_messages';

    protected $primaryKey = 'message_id';

    public $timestamps = false;

    protected $fillable = [
        'cool_id',
        'member_id',
        'shepherd_id',
        'member_session_id',
        'message',
        'status',
        'read_at',
        'is_deleted',
        'date_created',
        'deleted_by',
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

    public function cool(): BelongsTo
    {
        return $this->belongsTo(Cool::class, 'cool_id', 'cool_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id', 'member_id');
    }

    public function shepherd(): BelongsTo
    {
        return $this->belongsTo(Shepherd::class, 'shepherd_id', 'shepherd_id');
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(MemberSession::class, 'member_session_id', 'session_id');
    }
}
