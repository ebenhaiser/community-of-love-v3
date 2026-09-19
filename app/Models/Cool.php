<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cool extends Model
{
    use HasFactory;

    protected $table = 'cools';

    protected $primaryKey = 'cool_id';

    public $timestamps = false;

    protected $fillable = [
        'cool_code',
        'name',
        'shepherd_id',
        'description',
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
            'is_active' => 'boolean',
            'is_deleted' => 'boolean',
            'date_created' => 'datetime',
            'date_modified' => 'datetime',
            'date_deleted' => 'datetime',
        ];
    }

    public function shepherd(): BelongsTo
    {
        return $this->belongsTo(Shepherd::class, 'shepherd_id', 'shepherd_id');
    }

    public function coolMembers(): HasMany
    {
        return $this->hasMany(CoolMember::class, 'cool_id', 'cool_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'cool_members', 'cool_id', 'member_id')
            ->withPivot(['start_date', 'end_date', 'status', 'is_deleted']);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'cool_id', 'cool_id');
    }

    public function qrAccesses(): HasMany
    {
        return $this->hasMany(QrAccess::class, 'cool_id', 'cool_id');
    }

    public function qrAccess(): HasOne
    {
        return $this->hasOne(QrAccess::class, 'cool_id', 'cool_id')->latestOfMany('qr_access_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(MemberMessage::class, 'cool_id', 'cool_id');
    }

    public function eventCools(): HasMany
    {
        return $this->hasMany(EventCool::class, 'cool_id', 'cool_id');
    }
}
