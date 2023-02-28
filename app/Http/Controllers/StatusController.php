<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    // get all Statuses
    public function index(Request $request)
    {
        $withMail = $request->mail;
        if ($withMail == 'true') {
            return response([
                'statuses' => Status::orderBy('created_at', 'desc')->with('mails', function ($mail) {
                    return $mail->with('sender', function ($sender) {
                        return $sender->with('category:id,name')->get();
                    })->with('status:id,name,color')
                        ->with('tags')->with('attachments')->with('activities', function ($activity) {
                            return $activity->with('user')
                                ->get();
                        })
                        ->get();
                })
                    ->withCount('mails')
                    ->get()
            ], 200);
        } else {
            return response([
                'statuses' => Status::orderBy('created_at', 'desc')
                    ->withCount('mails')
                    ->get()
            ], 200);
        }
    }

    // get single Status
    public function show($id)
    {

        $withMail = request()->mail;
        if ($withMail == 'true') {
            return response([
                'status' => Status::where('id', $id)->with('mails', function ($mail) {
                    return $mail->with('sender', function ($sender) {
                        return $sender->with('category:id,name')->get();
                    })->with('status:id,name,color')
                        ->with('tags')->with('attachments')->with('activities', function ($activity) {
                            return $activity->with('user')
                                ->get();
                        })
                        ->get();
                })
                    ->withCount('mails')
                    ->first()
            ], 200);
        } else {
            return response([
                'status' => Status::where('id', $id)
                    ->withCount('mails')
                    ->first()
            ], 200);
        }
    }

    // create aStatus
    public function store(Request $request)
    {
        //validate fields
        $attrs = $request->validate([
            'name' => 'required|string'
        ]);

        $status = Status::firstOrCreate([
            'name' => $attrs['name']
        ]);

        return response([
            'message' => 'status created.',
            'status' => $status,
        ], 200);
    }

    // update aStatus
    public function update(Request $request, $id)
    {
        $status = Status::find($id);

        if (!$status) {
            return response([
                'message' => 'status not found.'
            ], 403);
        }

        //validate fields
        $attrs = $request->validate([
            'name' => 'required|string'
        ]);

        $status->update([
            'name' => $attrs['name']
        ]);

        return response([
            'message' => 'status updated.',
            'status' => $status
        ], 200);
    }

    //deleteStatus
    public function destroy($id)
    {
        $status = Status::find($id);

        if (!$status) {
            return response([
                'message' => 'status not found.'
            ], 403);
        }

        $status->delete();

        return response([
            'message' => 'status deleted.'
        ], 200);
    }
}
