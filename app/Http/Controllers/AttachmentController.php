<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attachment;
use Illuminate\Validation\Rule;
use Nette\Utils\Image;
use Illuminate\Support\Facades\Storage;


class AttachmentController extends Controller
{

    // create an attachment
    public function store(Request $request)
    {


        $attributes = request()->validate([
            'title' => 'required',
            'image' => 'required|image',
            'mail_id' => ['required', Rule::exists('mails', 'id')]
        ]);

        $attributes['image'] = request()->file('image')->store('images');



        $attachment = Attachment::create($attributes);


        return response([
            'message' => 'attachment uploaded.',
            'attachment' => $attachment,
        ], 200);
    }

    public function show($imageUrl)
    {
        return Image::make(storage_path('public/' . $imageUrl))->response();
        // return response([
        //     'image' => Image::make(storage_path('public/' . $imageUrl))
        // ], 200);
    }

    //delete attachments
    public function deleteAttachments()
    {

        //  $attachments = $mail->attachments()->pluck('image');

        //  $mail->attachments()->delete();
        //  $mail->activities()->delete();
        //  $mail->tags()->detach();
        //  $mail->delete();

        //  Storage::delete($attachments->all());

        return response([
            'message' => 'attachments deleted.'
        ], 200);
    }
}
