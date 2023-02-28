<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sender;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function senders()
    {
        return $this->hasMany(Sender::class);
    }
}
