<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RejectedTopic extends Model
{
    use HasFactory;

    protected $guarded = false;
    protected $table = 'rejected_topics';

    protected function topic()
    {
        return $this->belongsTo(Topic::class, 'topicId', 'id');
    }

    protected function reason()
    {
        return $this->belongsTo(TopicRejectType::class, 'reasonId', 'id');
    }

    protected function user()
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }
}
