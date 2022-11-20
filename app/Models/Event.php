<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function categories(){
        return $this->belongsToMany(Category::class,'event_categories','event_id','category_id');
    }
    // public function category(){
    //     return $this->belongsToMany(Category::class,'event_categories','event_id')->latest();
    // }
    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function type(){
        return $this->belongsTo(EventType::class,'type_id');
    }
}
