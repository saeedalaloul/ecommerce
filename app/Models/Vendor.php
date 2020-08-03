<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Vendor extends Model
{
    use Notifiable;

    protected $table = 'vendors';
    protected $fillable = [
        'name', 'mobile', 'password', 'address', 'logo', 'email', 'category_id', 'active', 'created_at', 'updated_at'
    ];

    protected $hidden = ['category_id', 'password'];

    public function scopeActive($active)
    {
        return $active->where('active', 1);
    }

    public function getActive()
    {
        return $this->active == 1 ? 'مفعل' : 'غير مفعل';
    }

    public function getLogoAttribute($val)
    {
        return ($val !== null) ? asset('assets/' . $val) : "";
    }

    public function scopeSelection($query)
    {
        return $query->select('id', 'category_id', 'name','address','email', 'active', 'logo', 'mobile');
    }

    public function category()
    {
        return $this->belongsTo(MainCategory::class, 'category_id', 'id');
    }

    public function setPasswordAttribute($password)
    {
        if (!empty($password)) {
            $this->attributes['password'] = bcrypt($password);
        }
    }
}
