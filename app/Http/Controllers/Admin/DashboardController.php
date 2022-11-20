<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use App\Models\Category;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $visitors_today = Visitor::whereDate('created_at', Carbon::today())->get()->count();
        $visitors_week = Visitor::whereDate('created_at','>=', Carbon::now()->subWeek())->get()->count();
        $visitors_month = Visitor::whereDate('created_at','>=', Carbon::now()->subMonth())->get()->count();
        $user_count = User::whereHas(
            'roles', function($q){
              $q->where('name', 'user');
          })->get()->count();
        $event_count = Event::all()->count();
        $category_count = Category::all()->count();
       return view('admin.dashboard.index', compact('user_count','event_count','category_count','visitors_today','visitors_week','visitors_month'));
    }
}
