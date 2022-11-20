<?php

namespace App\Observers;

use App\Models\Event;
use App\Models\NotificationRequest;
use App\Models\User;
use App\Models\Walet;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        //
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        $events = Event::where('user_id',$user->id)->get();
        foreach($events as $event){
            $event->delete();
        }
        $walet = Walet::where('user_id',$user->id)->get();
        $walet->delete();
        $notification_requests = NotificationRequest::where('user_id',$user->id)->get();
        foreach($notification_requests as $nr){
            $nr->delete();
        }
    }
    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
