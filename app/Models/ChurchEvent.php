<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChurchEvent extends Model
{
    use HasFactory;

    protected $table = 'church_events';

    protected $primaryKey = 'event_id';

    public $timestamps = false;

    protected $fillable = [
        'event_code',
        'name',
        'event_date',
        'start_time',
        'end_time',
        'location',
        'description',
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
            'event_date' => 'date',
            'is_deleted' => 'boolean',
            'date_created' => 'datetime',
            'date_modified' => 'datetime',
            'date_deleted' => 'datetime',
        ];
    }

    public function eventCools(): HasMany
    {
        return $this->hasMany(EventCool::class, 'event_id', 'event_id');
    }

    public function cools(): BelongsToMany
    {
        return $this->belongsToMany(Cool::class, 'event_cools', 'event_id', 'cool_id')
            ->withPivot(['is_deleted', 'created_by', 'date_created', 'deleted_by', 'date_deleted']);
    }

    public function eventMembers(): HasMany
    {
        return $this->hasMany(EventMember::class, 'event_id', 'event_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'event_members', 'event_id', 'member_id')
            ->withPivot(['is_deleted', 'created_by', 'date_created', 'deleted_by', 'date_deleted']);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(EventAttendance::class, 'event_id', 'event_id');
    }
}
