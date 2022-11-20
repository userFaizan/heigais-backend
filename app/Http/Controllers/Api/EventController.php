<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateEventRequest;
use App\Models\Category;
use App\Models\Event;
use App\Models\Walet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    public function get_events(Request $request){
        Log::error($request->all());
        Log::error(date('Y-m-d H:i:s',strtotime($request->from_date)));
        $convert_distance = 6371;
        if($request->unit == 1){
            $convert_distance = 3959;
        }
        $distance = '('.$convert_distance.' * acos(cos(radians('.$request->latitude.')) * cos(radians(lat)) * cos(radians(lng) - radians('.$request->longitude.')) + sin(radians('.$request->latitude.')) * sin(radians(lat))))';
        $events = Event::with(['categories'])->select('*', DB::RAW($distance.' AS distance'))->whereIn('type_id',[1,2]);
        if($request->radius){
            $events = $events->whereRaw($distance .'<='. $request->radius??5);
        }
        if($request->from_date){
            $events = $events->where('date_from','>=',date('Y-m-d H:i:s',strtotime($request->from_date)));
        }
        if($request->to_date){
            $events = $events->where('date_to','<=',date('Y-m-d H:i:s',strtotime($request->to_date)));
        }
        if($request->category){
            $category = Category::where('id',$request->category)->first();
            if($category->parent_id){
                $events = $events->whereHas('categories',function ($r) use ($request) {
                    return $r->where('category_id',$request->category);
                });
            }else{
                $events = $events->where('category_id',$category->id);
            }
        }
        $events = $events->whereDate('date_to','>',date('Y-m-d',strtotime(Carbon::now()->subday())));
        //return $events->paginate(15);
        $events = $events->get();
        $premium_events = Event::with(['categories'])->select('*', DB::RAW($distance.' AS distance'))->where('type_id',3);
        if($request->radius){
            $premium_events = $premium_events->whereRaw($distance .'<='. $request->radius??5);
        }
        $premium_events =$premium_events->whereDate('date_to','>',date('Y-m-d',strtotime(Carbon::now()->subday())))->get();
        // $item = collect();
        // $item->push($events);
        foreach($premium_events as $pe){
            $events->push($pe);
        }
        return [
            'events'=>$events,
            'is_app_updated'=>true
        ];
    }
    public function search_events(Request $request){
        Log::error($request->all());
        Log::error(date('Y-m-d H:i:s',strtotime($request->from_date)));
        $convert_distance = 6371;
        if($request->unit == 1){
            $convert_distance = 3959;
        }
        $distance = '('.$convert_distance.' * acos(cos(radians('.$request->latitude.')) * cos(radians(lat)) * cos(radians(lng) - radians('.$request->longitude.')) + sin(radians('.$request->latitude.')) * sin(radians(lat))))';
        $events = Event::with(['categories'])->select('*', DB::RAW($distance.' AS distance'));
        if($request->radius){
            $events = $events->whereRaw($distance .'<='. $request->radius??5);
        }
        if($request->from_date){
            $events = $events->where('date_from','>=',date('Y-m-d H:i:s',strtotime($request->from_date)));
        }
        if($request->to_date){
            $events = $events->where('date_to','<=',date('Y-m-d H:i:s',strtotime($request->to_date)));
        }
        if($request->category){
            $category = Category::where('id',$request->category)->first();
            if($category->parent_id){
                $events = $events->whereHas('categories',function ($r) use ($request) {
                    return $r->where('category_id',$request->category);
                });
            }else{
                $events = $events->where('category_id',$category->id);
            }
        }
        $events = $events->where('date_to','>',date('Y-m-d H:i:s',strtotime(Carbon::now())));
        //return $events->paginate(15);
        $events = $events->latest()->take(5)->get();
        return $events;
    }

    public function get_events_cats($id, Request $request){
        $events = Event::with(['category'])->select('*', DB::RAW('(3959 * acos(cos(radians('.$request->latitude.')) * cos(radians(lat)) * cos(radians(lng) - radians('.$request->longitude.')) + sin(radians('.$request->latitude.')) * sin(radians(lat)))) AS distance'));
        if($request->radius){
            $events = $events->whereRaw('(3959 * acos(cos(radians('.$request->latitude.')) * cos(radians(lat)) * cos(radians(lng) - radians('.$request->longitude.')) + sin(radians('.$request->latitude.')) * sin(radians(lat)))) <='.$request->radius);
        }
        if($request->from_date){
            $events = $events->where('date_from','>=',date('Y-m-d h:i:s',strtotime($request->from_date)));
        }
        if($request->to_date){
            $events = $events->where('date_to','<=',date('Y-m-d Y-m-d h:i:s',strtotime($request->to_date)));
        }
        if($request->category){
            $events = $events->whereHas('categories',function ($r) use ($request) {
                return $r->where('category_id',$request->category);
            });
        }
        return $events->paginate(15);
    }

    public function get_event($id){
        $event = Event::with('category','categories','type','user')->where('id',$id)->first();
        if($event){
            return response()->json($event,200);
        }else{
            return response()->json(['message'=>'detail not found'],404);
        }

    }

    public function my_events(){
        return Event::with('categories')->where('user_id',auth()->id())->get();
    }

    public function add_event(CreateEventRequest $request){
        $request['user_id']=auth()->id();
        $request['title']=ucfirst($request->title);
        if(isset($request->credit) && $request->credit > 0){
            $event = Event::create($request->except(['category_ids','credit']));
            $event->categories()->attach($request->category_ids);
            $event->update(['title'=>$request->title.' ']);
            $walet = Walet::where('user_id',auth()->id())->first();
            $walet->update(['credit'=>$walet->credit - $request->credit]);
        }else{
            $event = Event::create($request->except(['category_ids','credit']));
            $event->categories()->attach($request->category_ids);
            $event->update(['title'=>$request->title.' ']);
        }
        return response()->json(['message'=>'event created successfully!','code'=>200]);
    }

    public function update_event(Request $request, $id){
        // dd($request->all());
        $event = Event::where('id',$id)->where('user_id',auth()->id())->first();
        if($event){
            if(isset($request->category_id)){
                $event->update(['category_id'=>$request->category_id]);
            }
            if(isset($request->type_id)){
                $event->update(['type_id'=>$request->type_id]);
            }
            if(isset($request->title)){
                $event->update(['title'=>ucfirst($request->title)]);
            }
            if(isset($request->sub_title)){
                $event->update(['sub_title'=>$request->sub_title]);
            }
            if(isset($request->description)){
                $event->update(['description'=>$request->description]);
            }
            if(isset($request->date_from)){
                $event->update(['date_from'=>$request->date_from]);
            }
            if(isset($request->date_to)){
                $event->update(['date_to'=>$request->date_to]);
            }
            if(isset($request->address)){
                $event->update(['address'=>$request->address]);
            }
            if(isset($request->lat)){
                $event->update(['lat'=>$request->lat]);
            }
            if(isset($request->lng)){
                $event->update(['lng'=>$request->lng]);
            }
            if(isset($request->link)){
                $event->update(['link'=>$request->link]);
            }
            if(isset($request->category_ids)){
                $event->categories()->detach();
                $event->categories()->attach($request->category_ids);
            }
            return response()->json(['message'=>'event updated successfully!','code'=>200]);
        }else{
            return response()->json(['message'=>'event not found!','code'=>404],404);
        }
    }

    public function delete_event($id){
        $event = Event::where('id',$id)->where('user_id',auth()->id())->first();
        if($event){
            $event->delete();
            $event->categories()->detach();
            return response()->json(['message'=>'event deleted successfully!','code'=>200]);
        }else{
            return response()->json(['message'=>'event not found!','code'=>404],404);
        }
    }
}
