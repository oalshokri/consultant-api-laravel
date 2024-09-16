<?php

namespace App\Http\Controllers;

use App\Models\Sender;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SenderController extends Controller
{
    // get all Senders
    public function index()
    {
        $withMail = request()->mail;
        if ($withMail == 'true') {
            return response([
                'senders' => Sender::orderBy('created_at', 'desc')->with('category')->with('mails', function ($mail) {
                    return $mail->with('status:id,name,color')
                        ->with('tags')->with('attachments')->with('activities', function ($activity) {
                            return $activity->with('user')
                                ->get();
                        })
                        ->get();
                })
                    ->withCount('mails')
                    ->paginate(20)
            ], 200);
        } else {
            return response([
                'senders' => Sender::orderBy('created_at', 'desc')->with('category')
                    ->withCount('mails')
                    ->get()
            ], 200);
        }
    }

    // get single Sender
    public function show($id)
    {
        $withMail = request()->mail;
        if ($withMail == 'true') {
            return response([
                'sender' => Sender::where('id', $id)->with('category')->with('mails', function ($mail) {
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
                'sender' => Sender::where('id', $id)
                    ->withCount('mails')
                    ->first()
            ], 200);
        }
    }

    // create a Sender
    public function store(Request $request)
    {
        //validate fields
        $attrs = $request->validate([
            'name' => 'required|string',
            'mobile' => ['required', Rule::unique('senders', 'mobile')],
            'address' => 'nullable|string',
            'category_id' => ['required', Rule::exists('categories', 'id')]
        ]);

        $sender = Sender::firstOrCreate([
            'name' => $attrs['name'],
            'mobile' => $attrs['mobile'],
            'address' =>  $attrs['address'],
            'category_id' => $attrs['category_id']
        ]);


        return response([
            'message' => 'Sender created.',
            'sender' => Sender::where('id', $sender->id)->withCount('mails')->with('category')
                ->get(),
        ], 200);
    }

    // update a Sender
    public function update(Request $request, $id)
    {
        $sender = Sender::find($id);

        if (!$sender) {
            return response([
                'message' => 'Sender not found.'
            ], 403);
        }

        //validate fields
        $attrs = $request->validate([
            'name' => 'required|string',
            'mobile' => 'required|string',
            'address' => 'string',
            'category_id' => 'required|integer'
        ]);

        $sender->update([
            'name' => $attrs['name'],
            'mobile' => $attrs['mobile'],
            'address' =>  $attrs['address'],
            'category_id' => $attrs['category_id']
        ]);

        return response([
            'message' => 'Sender updated.',
            'sender' => $sender
        ], 200);
    }

    //delete Sender
    public function destroy($id)
    {
        $sender = Sender::find($id);

        if (!$sender) {
            return response([
                'message' => 'Sender not found.'
            ], 403);
        }


        $sender->mails()->delete();
        $sender->delete();

        return response([
            'message' => 'Sender deleted.'
        ], 200);
    }
}
