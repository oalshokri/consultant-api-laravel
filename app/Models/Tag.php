<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mail;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function mails()
    {
        return $this->belongsToMany(Mail::class);
    }
}
