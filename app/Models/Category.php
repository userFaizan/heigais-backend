<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function children(){
        return $this->hasMany(Category::class,'parent_id','id');
    }
    public function parent(){
        return $this->belongsTo(Category::class,'parent_id','id');
    }
    public function events(){
        return $this->hasMany(Event::class,'category_id');
    }
    public function notification(){
        return $this->hasMany(NotificationRequest::class,'category_id');
    }
    public function notifications(){
        return $this->belongsToMany(NotificationRequest::class,'category_notification_requests','category_id','notification_id');
    }
}
