<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\User
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @mixin \Eloquent
 */
class User extends Authenticatable implements MustVerifyEmailContract

{
    use Traits\ActiveUserHelper;
    use HasRoles;
    use HasApiTokens, HasFactory,MustVerifyEmailTrait;

    use Notifiable{
        notify as protected laravelNotify;
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'introduction',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }


    public function isAuthorOf($model)
    {
        return $this->id == $model->user_id;
    }


    public function replies()
    {
        return $this->hasMany(Reply::class);
    }


    public function notify($instance)
    {
        // ????????????????????????????????????????????????????????????
        if($this->id == Auth::id()){
            return;
        }

        //?????????????????????????????????????????????????????? Email ?????????????????? Pass
        if(method_exists($instance,'toDatabase')){
            $this->increment('notification_count');
        }

        $this->laravelNotify($instance);
    }


    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }

    public function setPasswordAttribute($value)
    {
        // ???????????????????????? 60??????????????????????????????????????????
        if (strlen($value) != 60) {

            // ????????? 60????????????????????????
            $value = bcrypt($value);
        }

        $this->attributes['password'] = $value;
    }

    public function setAvatarAttribute($path)
    {
        // ???????????? `http` ????????????????????????????????????????????????????????? URL
        if ( ! \Str::startsWith($path, 'http')) {

            // ??????????????? URL
            $path = config('app.url') . "/uploads/images/avatars/$path";
        }

        $this->attributes['avatar'] = $path;
    }

}
