<?php

namespace App\Http\Controllers;

use App\Models\Mail;
use App\Models\Tag;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class TagController extends Controller
{
    public function index()
    {
        if (request()->has('tags')) {
            if (request()->tags == 'all') {
                return response([
                    'tags' =>  Tag::orderBy('created_at', 'desc')->with('mails', function ($mails) {
                        return $mails->with('sender', function ($sender) {
                            return $sender->with('category')
                                ->get();
                        })->with('status:id,name,color')
                            ->with('tags')->with('attachments')->with('activities', function ($activity) {
                                return $activity->with('user')
                                    ->get();
                            });
                    })->get()

                ], 200);
            } elseif (request()->tags != null) {
                return response([
                    'tags' =>  Tag::whereIn('id', json_decode(request()->tags))->with('mails', function ($mails) {
                        return $mails->with('sender', function ($sender) {
                            return $sender->with('category')
                                ->get();
                        })->with('status:id,name,color')
                            ->with('tags')->with('attachments')->with('activities', function ($activity) {
                                return $activity->with('user')
                                    ->get();
                            });
                    })->get()

                ], 200);
            }
        }

        return response([
            'tags' => Tag::all()

        ], 200);
    }

    public function show($id)
    {

        return response([
            'tags' =>  Mail::find($id)->tags()->get()

        ], 200);
    }

    public function store(Request $request)
    {
        //validate fields
        $attrs = $request->validate([
            'name' => 'required|string',
        ]);

        $tag = Tag::firstOrCreate([
            'name' => $attrs['name']
        ]);

        return response([
            'message' => 'tag created.',
            'tag' => $tag,
        ], 200);
    }

    public function sync($id, Request $request)
    {
        Mail::find($id)->tags()->sync($request['tags']);

        return response([
            'messagse' => 'tags added.'

        ], 200);
    }

    //unlink or unlink and delete Tag
    public function destroy($id)
    {
        $tag = Tag::find($id);

        if (!$tag) {
            return response([
                'message' => 'Tag not found.'
            ], 403);
        }

        $tag->delete();

        return response([
            'message' => 'Tag deleted.'
        ], 200);
    }
}
