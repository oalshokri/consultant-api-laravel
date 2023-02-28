<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mail;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'mail_id'
    ];

    public function mail()
    {
        return $this->belongsTo(Mail::class);
    }
}
