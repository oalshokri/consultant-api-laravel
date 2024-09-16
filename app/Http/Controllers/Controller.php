<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use App\Models\Mail;
//use Algolia\AlgoliaSearch\SearchIndex;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function saveImage($image, $path = 'public')
    {
        if (!$image) {
            return null;
        }

        $filename = time() . '.png';
        // save image
        Storage::disk($path)->put($filename, base64_decode($image));

        //return the path
        // Url is the base url exp: localhost:8000
        return URL::to('/') . '/storage/' . $path . '/' . $filename;
    }

    // search mails
    public function search()
    {
        if (request()->start != null && request()->status_id != null) {
            return response([
                'mails' => Mail::search(request()->text)->whereBetween('created_at', [strtotime(request()->start), strtotime(request()->end)])
                    ->where('status_id',  request()->status_id)->query(fn ($query) => $query->with('sender', function ($sender) {
                        return $sender->with('category')
                            ->get();
                    })->with('status:id,name,color')
                        ->with('tags')->with('attachments')->with('activities', function ($activity) {
                            return $activity->with('user')
                                ->get();
                        }))
                    ->get()
            ], 200);
        }
        if (request()->start != null) {
            return response([
                'mails' => Mail::search(request()->text)->whereBetween('created_at', [strtotime(request()->start), strtotime(request()->end)])

                    ->query(fn ($query) => $query->with('sender', function ($sender) {
                        return $sender->with('category')
                            ->get();
                    })->with('status:id,name,color')
                        ->with('tags')->with('attachments')->with('activities', function ($activity) {
                            return $activity->with('user')
                                ->get();
                        }))->get()
            ], 200);
        }
        if (request()->status_id != null) {
            return response([
                'mails' => Mail::search(request()->text)
                    ->where('status_id',  request()->status_id)
                    ->query(fn ($query) => $query->with('sender', function ($sender) {
                        return $sender->with('category')
                            ->get();
                    })->with('status:id,name,color')
                        ->with('tags')->with('attachments')->with('activities', function ($activity) {
                            return $activity->with('user')
                                ->get();
                        }))->get()
            ], 200);
        }
        return response([
            'mails' => Mail::search(request()->text)->query(fn ($query) => $query->with('sender', function ($sender) {
                return $sender->with('category')
                    ->get();
            })->with('status:id,name,color')
                ->with('tags')->with('attachments')->with('activities', function ($activity) {
                    return $activity->with('user')
                        ->get();
                }))->get()
        ], 200);
    }
}
