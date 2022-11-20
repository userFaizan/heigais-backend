<?php

namespace App\Observers;

use App\Models\AppText;
use App\Models\User;
use App\Models\Event;
use App\Models\NotificationRequest;
use Berkayk\OneSignal\OneSignalFacade as OneSignal;

class EventObserver
{
    /**
     * Handle the Event "created" event.
     *
     * @param  \App\Models\Event  $event
     * @return void
     */
    public function created(Event $event)
    {
        //

    }

    /**
     * Handle the Event "updated" event.
     *
     * @param  \App\Models\Event  $event
     * @return void
     */
    public function updated(Event $event)
    {
        // $users = NotificationRequest::with('user')->where('category_id',$event->category_id)
        // ->whereRaw('(3959 * acos(cos(radians('.$event->lat.')) * cos(radians(lat)) * cos(radians(lng) - radians('.$event->lng.')) + sin(radians('.$event->lat.')) * sin(radians(lat)))) <= 5')
        // ->whereBetween('date_from',[$event->date_from,$event->date_to])
        // ->whereBetween('date_to',[$event->date_from,$event->date_to])->get()
        // ->map(function ($query){
        //     return User::where('id',$query->user_id)->first();
        // });
        $users = NotificationRequest::with('user','categories')->where('category_id',$event->category_id)
        // ->whereRaw('(3959 * acos(cos(radians('.$event->lat.')) * cos(radians(lat)) * cos(radians(lng) - radians('.$event->lng.')) + sin(radians('.$event->lat.')) * sin(radians(lat)))) <= 5')
        // ->whereBetween('date_from',[$event->date_from,$event->date_to])
        // ->whereBetween('date_to',[$event->date_from,$event->date_to])
        ->get()
        ->map(function ($query) use ($event){
            $lonDelta = deg2rad($query->lng) - deg2rad($query->lng);
            $a = pow(cos(deg2rad($query->lat)) * sin($lonDelta), 2) +
                pow(cos(deg2rad($event->lat)) * sin(deg2rad($query->lat)) - sin(deg2rad($event->lat)) * cos(deg2rad($query->lat)) * cos($lonDelta), 2);
            $b = sin(deg2rad($event->lat)) * sin(deg2rad($query->lat)) + cos(deg2rad($event->lat)) * cos(deg2rad($query->lat)) * cos($lonDelta);
            $angle = atan2(sqrt($a), $b);
            $earthRadius = 6371;
            if($query->user->unit == 1){
                $earthRadius = 3959;
            }
            $distance = $angle * $earthRadius;
            if($distance <= $query->radius){
                if($query->user_id != $event->user_id){
                    foreach ($query->categories->pluck('id')->toArray() as $notify_cat_id){
                        if($event->categories->contains($notify_cat_id)){
                            if($query->notification_status)
                            {
                                return User::where('id',$query->user_id)->first();
                            }else{
                                if(date('Y-m-d', strtotime($event->date_from)) < date('Y-m-d', strtotime($query->date_from))
                                && date('Y-m-d', strtotime($event->date_to)) > date('Y-m-d', strtotime($query->date_to))){
                                    if(date('Y-m-d', strtotime($event->date_from)) < date('Y-m-d', strtotime($query->date_from))
                                    && date('Y-m-d', strtotime($event->date_to)) > date('Y-m-d', strtotime($query->date_to))){
                                        return User::where('id',$query->user_id)->first();
                                    }
                                }
                                // return User::where('id',$query->user_id)->first();
                            }
                        }
                    }
                }
            }
        });
        $users = $users->unique();
        if($users){
            foreach ($users as $user){
                if($user){
                    foreach($user->devices as $device){
                        OneSignal::sendNotificationToUser(
                            "A new event that you requested, was added",
                            $device->onesignel_user_id,
                            $url = null,
                            $data = ['event_id'=>$event->id],
                            $buttons = null,
                            $schedule = null
                        );
                    }
                }
            }
        }
    }

    /**
     * Handle the Event "deleted" event.
     *
     * @param  \App\Models\Event  $event
     * @return void
     */
    public function deleted(Event $event)
    {
        //
    }

    /**
     * Handle the Event "restored" event.
     *
     * @param  \App\Models\Event  $event
     * @return void
     */
    public function restored(Event $event)
    {
        //
    }

    /**
     * Handle the Event "force deleted" event.
     *
     * @param  \App\Models\Event  $event
     * @return void
     */
    public function forceDeleted(Event $event)
    {
        //
    }
}
