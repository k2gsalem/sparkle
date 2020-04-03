<?php

namespace App\Observers;

use App\Photo;

class Photoobserver
{
    /**
     * Handle the photo "created" event.
     *
     * @param  \App\Photo  $photo
     * @return void
     */
    public function creating(Photo $photo)
    {
        //
        $user_id = auth()->user()->id;
        $photo->created_by = $user_id;
        $photo->updated_by = $user_id;
    }

    /**
     * Handle the photo "updated" event.
     *
     * @param  \App\Photo  $photo
     * @return void
     */
    public function updated(Photo $photo)
    {
        //
        $user_id = auth()->user()->id;
        $photo->updated_by = $user_id;
    }

    /**
     * Handle the photo "deleted" event.
     *
     * @param  \App\Photo  $photo
     * @return void
     */
    public function deleted(Photo $photo)
    {
        //
    }

    /**
     * Handle the photo "restored" event.
     *
     * @param  \App\Photo  $photo
     * @return void
     */
    public function restored(Photo $photo)
    {
        //
    }

    /**
     * Handle the photo "force deleted" event.
     *
     * @param  \App\Photo  $photo
     * @return void
     */
    public function forceDeleted(Photo $photo)
    {
        //
    }
}
