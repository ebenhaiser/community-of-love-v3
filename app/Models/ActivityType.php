<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ActivityType extends Model
{
    use HasFactory;

    protected $table = 'activity_types';

    protected $primaryKey = 'activity_type_id';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'is_deleted',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_deleted' => 'boolean',
        ];
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'activity_type_id', 'activity_type_id');
    }
}
