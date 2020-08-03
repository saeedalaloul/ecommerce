<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class language extends Model
{
    protected $fillable = [
        'abbr', 'locale', 'name', 'direction', 'active', 'created_at', 'updated_at'
    ];

    public function scopeActive($active)
    {
        return $active->where('active', 1);
    }

    public function scopeSelection($query)
    {
        return $query->select('id','abbr', 'name', 'direction', 'active');
    }

    public function getActive()
    {
        return $this -> active == 1 ? 'مفعل' : 'غير مفعل';
    }
}
