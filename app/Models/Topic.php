<?php

namespace App\Models;

use App\Models\trait\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    use HasFactory, SoftDeletes, Filterable;

    protected $guarded = false;
    protected $table = 'topics';
    protected $primaryKey = 'id';

    public function forum(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Forum::class, 'forumId', 'id');
    }

    public function tags(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'topic_tags', 'topicId', 'tagId');
    }

    public function author(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }

    public function likes(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'topic_likes', 'topicId', 'userId');
    }

    public function posts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Post::class, 'topicId', 'id');
    }

    public function latestPost()
    {
        return $this->posts()->latest('updated_at')->first();
    }

    public function images()
    {
        return $this->hasMany(TopicImage::class, 'topicId', 'id');
    }

    public function isRejected()
    {
        return $this->hasOne(RejectedTopic::class, 'topicId', 'id');
    }


    public static function allApprovedTopics()
    {
        return self::all()->where('status', '=', '1');
    }

}
