<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Mail;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    // get all Activitys
    public function index()
    {
        return response([
            'activities' => Activity::orderBy('created_at', 'desc')->with('mail')->with('user')
                ->get()
        ], 200);
    }

    // get single Activity
    public function show($id)
    {
        return response([
            'activity' => Activity::where('id', $id)->with('mail')->with('user')->get()
        ], 200);
    }

    // create a Activity
    public function store(Request $request, $id)
    {
        $mail = Mail::find($id);

        if (!$mail) {
            return response([
                'message' => 'mail not found.'
            ], 403);
        }

        //validate fields
        $attrs = $request->validate([
            'body' => 'required|string',
            'user_id' => 'required|integer',
            'mail_id' => 'required|integer',
            'send_number' => 'string',
            'send_date' => 'date',
            'send_destination' => 'string',
        ]);

        $activity = Activity::create([
            'body' => $attrs['body'],
            'user_id' => auth()->user()->id,
            'mail_id' => $id,
            'send_number' => $attrs['send_number'],
            'send_date' => $attrs['send_date'],
            'send_destination' => $attrs['send_destination']
        ]);

        // for now skip for Activity image

        return response([
            'message' => 'Activity created.',
            'Activity' => $activity,
        ], 200);
    }

    // update a Activity
    public function update(Request $request, $id)
    {
        $activity = Activity::find($id);

        if (!$activity) {
            return response([
                'message' => 'Activity not found.'
            ], 403);
        }

        if ($activity->user_id != auth()->user()->id) {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }

        //validate fields
        $attrs = $request->validate([
            'body' => 'required|string',
            'user_id' => 'required|integer',
            'mail_id' => 'required|integer',
            'send_number' => 'string',
            'send_date' => 'date',
            'send_destination' => 'string',
        ]);

        $activity->update([
            'body' => $attrs['body'],
            'user_id' => $attrs['user_id'],
            'mail_id' => $attrs['mail_id'],
            'send_number' => $attrs['send_number'],
            'send_date' => $attrs['send_date'],
            'send_destination' => $attrs['send_destination']
        ]);

        // for now skip for Activity image

        return response([
            'message' => 'Activity updated.',
            'activity' => $activity
        ], 200);
    }

    //delete Activity
    public function destroy($id)
    {
        $activity = Activity::find($id);

        if (!$activity) {
            return response([
                'message' => 'Activity not found.'
            ], 403);
        }

        if ($activity->user_id != auth()->user()->id) {
            return response([
                'message' => 'Permission denied.'
            ], 403);
        }

        $activity->delete();

        return response([
            'message' => 'Activity deleted.'
        ], 200);
    }
}
