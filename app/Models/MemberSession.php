<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MemberSession extends Model
{
    use HasFactory;

    protected $table = 'member_sessions';

    protected $primaryKey = 'session_id';

    public $timestamps = false;

    protected $fillable = [
        'qr_access_id',
        'member_id',
        'session_token_hash',
        'created_at',
        'expires_at',
        'last_activity_at',
        'is_revoked',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'expires_at' => 'datetime',
            'last_activity_at' => 'datetime',
            'is_revoked' => 'boolean',
        ];
    }

    public function qrAccess(): BelongsTo
    {
        return $this->belongsTo(QrAccess::class, 'qr_access_id', 'qr_access_id');
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id', 'member_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(MemberMessage::class, 'member_session_id', 'session_id');
    }
}
