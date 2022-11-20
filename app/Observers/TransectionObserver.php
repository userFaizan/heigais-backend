<?php

namespace App\Observers;

use App\Models\Transection;
use App\Models\Walet;

class TransectionObserver
{
    /**
     * Handle the Transection "created" event.
     *
     * @param  \App\Models\Transection  $transection
     * @return void
     */
    public function created(Transection $transection)
    {
        $walet = Walet::where('id',$transection->walet_id)->first();
        $walet->update(['credit'=>$walet->credit+$transection->credit]);
    }

    /**
     * Handle the Transection "updated" event.
     *
     * @param  \App\Models\Transection  $transection
     * @return void
     */
    public function updated(Transection $transection)
    {
        //
    }

    /**
     * Handle the Transection "deleted" event.
     *
     * @param  \App\Models\Transection  $transection
     * @return void
     */
    public function deleted(Transection $transection)
    {
        //
    }

    /**
     * Handle the Transection "restored" event.
     *
     * @param  \App\Models\Transection  $transection
     * @return void
     */
    public function restored(Transection $transection)
    {
        //
    }

    /**
     * Handle the Transection "force deleted" event.
     *
     * @param  \App\Models\Transection  $transection
     * @return void
     */
    public function forceDeleted(Transection $transection)
    {
        //
    }
}
