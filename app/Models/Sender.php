<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Mail;

class Sender extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'mobile',
        'address',
        'category_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function mails()
    {
        return $this->hasMany(Mail::class);
    }
}
