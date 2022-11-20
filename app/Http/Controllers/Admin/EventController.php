<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppText;
use App\Models\Category;
use App\Models\Event;
use App\Models\EventType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.event.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = EventType::all();
        $pcategories =Category::where('parent_id',null)->get();
        $ccategories =Category::where('parent_id',1)->get();
        foreach($pcategories as $category){
            $app_text = AppText::where('text_key',$category->title)->where('language_id',1)->first();
            $category['title']=$app_text->text;
        }
        foreach($ccategories as $category){
            $app_text = AppText::where('text_key',$category->title)->where('language_id',1)->first();
            $category['title']=$app_text->text;
        }
        $users = User::all()->except(auth()->id());
        return view('admin.event.create',compact('types','pcategories','ccategories','users','app_text'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dump($request->all());
        $request['date_from'] = $request->start_date.' '.$request->start_time.':00';
        // dd($request->time_from);
        // dd()
        $request['title']=ucfirst($request->title);
        if($request->type_id < 3){
            $request['date_to'] = $request->start_date.' '.$request->end_time.':00';
            $data = $request->except(['_token','start_date','start_time','end_time','category_ids']);
            // $event = Event::create($request->except([]));
            // $event->categories()->attach($request->category_ids);
        }else{
            $request['date_to'] = $request->end_date.' '.$request->end_time.':00';
            // $event = Event::create();
            $data = $request->except(['_token','start_date','end_date','start_time','end_time','category_ids']);
            // $event->categories()->attach($request->category_ids);
        }
        // dd($data);
        $event = Event::create($data);
        $event->categories()->attach($request->category_ids);
        return redirect('admin/events');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $edit_event = Event::where('id',$id)->first();
        $types = EventType::all();
        $pcategories =Category::where('parent_id',null)->get();
        $ccategories =Category::where('parent_id',1)->get();
        foreach($pcategories as $category){
            $app_text = AppText::where('text_key',$category->title)->where('language_id',1)->first();
            $category['title']=$app_text->text;
        }
        foreach($ccategories as $category){
            $app_text = AppText::where('text_key',$category->title)->where('language_id',1)->first();
            $category['title']=$app_text->text;
        }
        $users = User::all()->except(auth()->id());
        return view('admin.event.create',compact('types','pcategories','ccategories','users','app_text','edit_event'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $event = Event::where('id',$id)->first();
        if(isset($request->category_id)){
            $event->update(['category_id'=>$request->category_id]);
        }
        if(isset($request->type_id)){
            $event->update(['type_id'=>$request->type_id]);
            if($request->type_id < 3){
                $request['date_to'] = $request->start_date.' '.$request->end_time.':00';
            }else{
                $request['date_to'] = $request->end_date.' '.$request->end_time.':00';
            }

            if(isset($request->date_from)){
                $event->update(['date_from'=>$request->start_date.' '.$request->start_time.':00']);
            }
            if(isset($request->date_to)){
                $event->update(['date_to'=>$request->date_to]);
            }
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
        return redirect('admin/events');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function get_all_events()
    {
        $events = Event::with('user','categories','type')->orderBy('created_at','DESC')->get();
            return DataTables::of($events)
                ->addIndexColumn()
                ->addColumn('owner', function(Event $event){
                    return $event->user->email;
                })
                ->addColumn('title', function(Event $event){
                    return ucfirst($event->title);
                })
                ->addColumn('description', function(Event $event){
                    return ucfirst($event->description);
                })
                ->addColumn('category', function(Event $event){

                    $category_html = "";
                    foreach($event->categories as $category){
                        $category_text = AppText::select('text')->where('language_id',1)->where('text_key',$category->title)->first();
                        $category_html =$category_html.'<div class="text-center" style="color:white; background-color:'.$category->color.'; border-radius:20px; padding:5px 10px; margin-bottom: 3px;">'.$category_text->text.'</div>';
                    }
                    return $category_html;
                })
                ->addColumn('type', function(Event $event){
                    return $event->type->title;
                })
                ->addColumn('created_at', function(Event $event){
                    return Carbon::createFromFormat('Y-m-d H:i:s', $event->created_at)->timezone('Europe/Moscow');
                })
                ->addColumn('action', function(Event $event){
                    return view('admin.event.action',compact('event'))->render();
                })
                ->rawColumns(['action','category'])
                ->make(true);
    }

    public function get_child_categories(Request $request){
            $categories=Category::where('parent_id',$request->id)->get();
            return [
                'categories'=>$categories
            ];
    }

    public function delete_event(Request $request){
        $event = Event::where('id',$request->id)->first();
        $event->delete();
        return response()->json([
            'message'=>'Event deleted successfully!',
            'code'=>200
        ]);
    }
}
