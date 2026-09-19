<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoolMember extends Model
{
    use HasFactory;

    protected $table = 'cool_members';

    protected $primaryKey = 'cool_member_id';

    public $timestamps = false;

    protected $fillable = [
        'cool_id',
        'member_id',
        'start_date',
        'end_date',
        'status',
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
            'start_date' => 'date',
            'end_date' => 'date',
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

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'member_id', 'member_id');
    }
}
