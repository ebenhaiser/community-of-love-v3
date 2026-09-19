<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberFollowUp extends Model
{
    use HasFactory;

    protected $table = 'member_follow_ups';

    protected $primaryKey = 'follow_up_id';

    public $timestamps = false;

    protected $fillable = [
        'member_id',
        'cool_id',
        'status',
        'consecutive_absent_count',
        'action_taken',
        'notes',
        'handled_by',
        'handled_at',
        'is_deleted',
        'created_by',
        'date_created',
        'modified_by',
        'date_modified',
        'deleted_by',
        'date_deleted',
    ];

    protected function casts(): array
    {
        return [
            'consecutive_absent_count' => 'integer',
            'is_deleted' => 'boolean',
            'handled_at' => 'datetime',
            'date_created' => 'datetime',
            'date_modified' => 'datetime',
            'date_deleted' => 'datetime',
        ];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id', 'member_id');
    }

    public function cool(): BelongsTo
    {
        return $this->belongsTo(Cool::class, 'cool_id', 'cool_id');
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by', 'user_id');
    }
}
