<?php

namespace App\Models;

use App\Models\Classes;
use App\Models\Students;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    use HasFactory;

    protected $table = 'sections';
    protected $fillable = [
        'class_id',
        'name',
    ];

    /**
     * Get the class
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class);
    }

/**
 * Get students
 *
 * @return \Illuminate\Database\Eloquent\Relations\HasMany
 */
    public function students():HasMany{
        return $this->hasMany(Students::class,'section_id');
    }
}
