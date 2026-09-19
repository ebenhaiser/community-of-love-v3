<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Shepherd extends Model
{
    use HasFactory;

    protected $table = 'shepherds';

    protected $primaryKey = 'shepherd_id';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'phone',
        'email',
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

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'shepherd_id', 'shepherd_id');
    }

    public function cools(): HasMany
    {
        return $this->hasMany(Cool::class, 'shepherd_id', 'shepherd_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(MemberMessage::class, 'shepherd_id', 'shepherd_id');
    }
}
