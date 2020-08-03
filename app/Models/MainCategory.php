<?php

namespace App\Models;

use App\Observers\MainCategoryObserve;
use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
    protected $table = 'main_categories';
    public $timestamps = true;
    protected $fillable = [
        'translation_lang', 'translation_of', 'name', 'slug', 'photo', 'active', 'created_at', 'updated_at'
    ];

    protected static function boot()
    {
        parent::boot();
        MainCategory::observe(MainCategoryObserve::class);
    }

    public function scopeActive($active)
    {
        return $active->where('active', 1);
    }

    public function scopeSelection($query)
    {
        return $query->select('id', 'translation_lang','translation_of', 'name', 'slug', 'photo', 'active');
    }

    public function getActive()
    {
        return $this->active == 1 ? 'مفعل' : 'غير مفعل';
    }

    public function getPhotoAttribute($val)
    {
        return ($val !== null) ? asset('assets/'.$val) : "";
    }

    public function categories(){
        return $this->hasMany(self::class,'translation_of');
    }

    public function vendors(){
        return $this -> hasMany(Vendor::class,'category_id','id');
    }
}
