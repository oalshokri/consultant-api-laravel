<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Attachment;
use App\Models\Mail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Tag;

class MailController extends Controller
{
    // get all mails
    public function index()
    {

        return response([
            'mails' => Mail::orderBy('created_at', 'desc')->with('sender', function ($sender) {
                return $sender->with('category')
                    ->get();
            })->with('status:id,name,color')
                ->with('tags')->with('attachments')->with('activities', function ($activity) {
                    return $activity->with('user')
                        ->get();
                })
                ->get()
        ], 200);
    }

    // get single Mail
    public function show($id)
    {
        return response([
            'mail' => Mail::where('id', $id)->with('sender:id,name,category_id')->with('status:id,name,color')
                ->with('tags')->with('attachments')->with('activities')
                ->withCount('activities')->get()
        ], 200);
    }

    // create a Mail
    public function store(Request $request)
    {
        //validate fields
        $attrs = $request->validate([
            'subject' => 'required|string',
            'description' => 'nullable|string',
            'sender_id' => 'nullable|integer',
            'archive_number' => 'required|string',
            'archive_date' => 'required|date',
            'decision' => 'nullable|string',
            'status_id' => 'integer',
            'final_decision' => 'nullable|string'
        ]);


        $mail = Mail::create([
            'subject' => $attrs['subject'],
            'description' => $attrs['description'],
            'sender_id' =>  $attrs['sender_id'],
            'archive_number' => $attrs['archive_number'],
            'archive_date' =>  $attrs['archive_date'],
            'decision' =>  $attrs['decision'],
            'status_id' =>  $attrs['status_id'],
            'final_decision' =>  $attrs['final_decision'],

        ]);


        $mail->tags()->sync(json_decode(request()->tags, true));

        if (request()->activities != null) {
            $mail->activities()->createMany(json_decode(request()->activities, true));
        }

        return response([
            'message' => 'Mail created.',
            'mail' => Mail::where('id', $mail->id)->first(),
        ], 200);
    }

    // update a Mail
    public function update(Request $request, $id)
    {
        $mail = Mail::find($id);

        if (!$mail) {
            return response([
                'message' => 'Mail not found.'
            ], 403);
        }

        //validate fields
        $attrs = $request->validate([
            'decision' => 'nullable|string',
            'status_id' => 'integer',
            'final_decision' => 'nullable|string'
        ]);

        $mail->update([
            'decision' =>  $attrs['decision'],
            'status_id' =>  $attrs['status_id'],
            'final_decision' =>  $attrs['final_decision']
        ]);

        $mail->tags()->sync(json_decode(request()->tags, true));

        $tags = Tag::orderBy('created_at', 'desc')->with('mails')->get();
        foreach ($tags as $tag) {
            if (!$tag->mails()->exists())
                $tag->delete();
        }


        $mail->attachments()->whereIn('id', json_decode(request()->idAttachmentsForDelete))->delete();

        Storage::delete(json_decode(request()->pathAttachmentsForDelete));

        if (request()->activities != null) {
            $mail->activities()->createMany(json_decode(request()->activities, true));
        }

        return response([
            'message' => 'Mail updated.',
            'mail' => $mail
        ], 200);
    }

    //delete Mail
    public function destroy($id)
    {
        $mail = Mail::find($id);

        if (!$mail) {
            return response([
                'message' => 'Mail not found.'
            ], 403);
        }

        $attachments = $mail->attachments()->pluck('image');

        $mail->attachments()->delete();
        $mail->activities()->delete();
        $mail->tags()->detach();
        $mail->delete();

        Storage::delete($attachments->all());

        $tags = Tag::orderBy('created_at', 'desc')->with('mails')->get();
        foreach ($tags as $tag) {
            if (!$tag->mails()->exists())
                $tag->delete();
        }

        return response([
            'message' => 'mail deleted.'
        ], 200);
    }
}
