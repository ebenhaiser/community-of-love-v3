<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityMaterial extends Model
{
    use HasFactory;

    protected $table = 'activity_materials';

    protected $primaryKey = 'material_id';

    public $timestamps = false;

    protected $fillable = [
        'activity_id',
        'material_type',
        'file_name',
        'file_path',
        'external_url',
        'file_size',
        'mime_type',
        'description',
        'is_deleted',
        'uploaded_by',
        'date_uploaded',
        'deleted_by',
        'date_deleted',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
            'is_deleted' => 'boolean',
            'date_uploaded' => 'datetime',
            'date_deleted' => 'datetime',
        ];
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class, 'activity_id', 'activity_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by', 'user_id');
    }
}
