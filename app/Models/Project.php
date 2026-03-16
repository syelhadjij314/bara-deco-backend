<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    const CATEGORIES = ['interieur', 'exterieur', 'plafond', 'commercial'];

    protected $fillable = [
        'title',
        'description',
        'category',
        'image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function getCategoryLabelAttribute(): string
    {
        return match($this->category) {
            'interieur'  => 'Intérieur',
            'exterieur'  => 'Extérieur',
            'plafond'    => 'Faux plafond',
            'commercial' => 'Commercial',
            default      => ucfirst($this->category),
        };
    }
}
