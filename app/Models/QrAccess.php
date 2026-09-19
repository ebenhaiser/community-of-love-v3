<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QrAccess extends Model
{
    use HasFactory;

    protected $table = 'qr_accesses';

    protected $primaryKey = 'qr_access_id';

    public $timestamps = false;

    protected $fillable = [
        'cool_id',
        'access_code',
        'pin_hash',
        'qr_token',
        'expires_at',
        'last_used_at',
        'is_active',
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
            'expires_at' => 'datetime',
            'last_used_at' => 'datetime',
            'is_active' => 'boolean',
            'is_deleted' => 'boolean',
            'date_created' => 'datetime',
            'date_modified' => 'datetime',
            'date_deleted' => 'datetime',
        ];
    }

    public function cool(): BelongsTo
    {
        return $this->belongsTo(Cool::class, 'cool_id', 'cool_id');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(MemberSession::class, 'qr_access_id', 'qr_access_id');
    }
}
