<?php

namespace App\Models;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Role extends Model
{
    use HasTranslations;

    public $translatable = ['role'];
    protected $guarded = [];



    public function getpermessionAttribute($value)
    {
        return json_decode($value);
    }

    public function admins()
    {
        return $this->hasMany(Admin::class , 'role_id');
    }

}
