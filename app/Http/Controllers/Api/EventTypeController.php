<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EventType;
use Illuminate\Http\Request;

class EventTypeController extends Controller
{
    public function event_types(){
        $types = EventType::all();
        return response()->json($types,200);
    }
}
