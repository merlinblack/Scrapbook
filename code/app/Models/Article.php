<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Article extends Model
{
    use HasFactory;

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', true);
    }

    public function scopeNotSite(Builder $query): Builder
    {
        return $query->where('category', '!=', 'site');
    }

    public function scopeSlug(Builder $query, $slug): Builder
    {
        return $query->where('slug',$slug);
    }
}
