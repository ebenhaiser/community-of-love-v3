<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    use HasFactory;

    protected $table = 'activities';

    protected $primaryKey = 'activity_id';

    public $timestamps = false;

    protected $fillable = [
        'cool_id',
        'activity_type_id',
        'name',
        'activity_date',
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
            'activity_date' => 'date',
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

    public function activityType(): BelongsTo
    {
        return $this->belongsTo(ActivityType::class, 'activity_type_id', 'activity_type_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'activity_id', 'activity_id');
    }

    public function materials(): HasMany
    {
        return $this->hasMany(ActivityMaterial::class, 'activity_id', 'activity_id');
    }
}
