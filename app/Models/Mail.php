<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sender;
use App\Models\Status;
use App\Models\Attachment;
use App\Models\Activity;
use App\Models\Tag;
use Laravel\Scout\Searchable;

class Mail extends Model
{
    use HasFactory;
    use Searchable;

    protected $fillable = [
        'subject',
        'description',
        'sender_id',
        'archive_number',
        'archive_date',
        'decision',
        'status_id',
        'final_decision'
    ];

    public function searchableAs()
    {
        return 'mails';
    }



    public function sender()
    {
        return $this->belongsTo(Sender::class);
    }
    public function status()
    {
        return $this->belongsTo(Status::class);
    }
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
