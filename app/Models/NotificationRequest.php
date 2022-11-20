<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationRequest extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }
    public function categories(){
        return $this->belongsToMany(Category::class,'category_notification_requests','notification_id','category_id');
    }
}
