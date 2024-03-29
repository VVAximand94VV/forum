<?php

namespace App\Models;

use App\Models\Traits\User\UserRolesAndPermissions;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, UserRolesAndPermissions;

    protected $guarded = false;
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'login',
        'name',
        'email',
        'lastVisit',
        'roleId',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function socialAccountInfo()
    {
        return $this->hasMany(SocialAccount::class, 'userId', 'id');
    }

    public function topics(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Topic::class, 'userId', 'id');
    }

    public function posts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Post::class, 'userId', 'id');
    }

    public function likedPosts(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_likes', 'userId', 'postId');
    }

    public function likedTopics(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Topic::class, 'topic_likes', 'userId', 'topicId');
    }

    public function postBookmarks(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'user_bookmarks', 'userId', 'postId');
    }

    public function topicBookmarks(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Topic::class, 'user_topic_bookmarks', 'userId', 'topicId');
    }

    public function unapprovedTopic()
    {
        return $this->hasMany(Topic::class, 'userId', 'id')->where('status', '=', 0);
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'senderId', 'id');
    }

    public function isWarned(): bool
    {
        return $this->isWarned;
    }

    public function isBanned(): bool
    {
        return (bool)$this->banDetails();
    }

    public function banDetails()
    {
        return $this->hasMany(BanList::class, 'userId', 'id')->first();
    }

    /**
     * @return string|bool
     * If avatar path and url not null - return avatar url
     */
    public function getAvatar(): string|bool
    {
        return $this->avatarPath && $this->avatarUrl ? $this->avatarUrl : false;
    }

    /**
     * @return bool
     * Check user online status
     */
    public function checkOnlineStatus(): bool
    {
        $now = Carbon::parse(now());
        $lastVisit = Carbon::parse($this->lastVisit);
        $diff = $now->diffInMinutes($lastVisit);
        return (bool) $diff < 2 ?? false;
    }
}
