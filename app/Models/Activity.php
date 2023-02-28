<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mail;
use App\Models\User;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'body',
        'user_id',
        'mail_id',
        'send_number',
        'send_date',
        'send_destination'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function mail()
    {
        return $this->belongsTo(Mail::class);
    }
}
