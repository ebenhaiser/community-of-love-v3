<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    use HasFactory;

    protected $table = 'members';

    protected $primaryKey = 'member_id';

    public $timestamps = false;

    protected $fillable = [
        'member_code',
        'name',
        'phone',
        'email',
        'join_date',
        'status',
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
            'join_date' => 'date',
            'is_active' => 'boolean',
            'is_deleted' => 'boolean',
            'date_created' => 'datetime',
            'date_modified' => 'datetime',
            'date_deleted' => 'datetime',
        ];
    }

    public function coolMembers(): HasMany
    {
        return $this->hasMany(CoolMember::class, 'member_id', 'member_id');
    }

    public function cools(): BelongsToMany
    {
        return $this->belongsToMany(Cool::class, 'cool_members', 'member_id', 'cool_id')
            ->withPivot(['start_date', 'end_date', 'status', 'is_deleted']);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'member_id', 'member_id');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(MemberSession::class, 'member_id', 'member_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(MemberMessage::class, 'member_id', 'member_id');
    }

    public function eventMembers(): HasMany
    {
        return $this->hasMany(EventMember::class, 'member_id', 'member_id');
    }

    public function eventAttendances(): HasMany
    {
        return $this->hasMany(EventAttendance::class, 'member_id', 'member_id');
    }
}
