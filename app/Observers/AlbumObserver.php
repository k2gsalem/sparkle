<?php

namespace App\Observers;

use App\Album;
// use App\User;
// use Auth;

class AlbumObserver
{
    /**
     * Handle the album "created" event.
     *
     * @param  \App\Album  $album
     * @return void
     */
    public function creating(Album $album)
    {
       // Profile::find($id)->update($inputs);
        $user_id = auth()->user()->id;
        $album->created_by = $user_id;
        $album->updated_by = $user_id;
        //
    }

    /**
     * Handle the album "updated" event.
     *
     * @param  \App\Album  $album
     * @return void
     */
    public function updated(Album $album)
    {
        $user_id = auth()->user()->id;
        $album->updated_by = $user_id;
        //
    }

    /**
     * Handle the album "deleted" event.
     *
     * @param  \App\Album  $album
     * @return void
     */
    // public function deleted(Album $album)
    // {
    //     //
    // }

    /**
     * Handle the album "restored" event.
     *
     * @param  \App\Album  $album
     * @return void
     */
    // public function restored(Album $album)
    // {
    //     //
    // }

    /**
     * Handle the album "force deleted" event.
     *
     * @param  \App\Album  $album
     * @return void
     */
    // public function forceDeleted(Album $album)
    // {
    //     //
    // }
}
