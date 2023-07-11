<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $table = 'users';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'role',
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

    public function user_info()
    {
        return $this->hasOne(User_info::class, 'user_id');
    }

    public function user_preferences()
    {
        return $this->hasOne(User_preferences::class, 'user_id');
    }

    public function tags()
    {
        return $this->hasMany(Tags::class, 'admin_id');
    }

    public function matching()
    {
        return $this->hasMany(Matching::class, ['sender' , 'receiver']);
    }

    public function chatSender()
    {
        return $this->hasMany(Chat::class, 'sender');
    }

    public function chatReceiver()
    {
        return $this->hasMany(Chat::class, 'receiver');
    }

    public function user_images()
    {
        return $this->hasMany(User_images::class, 'user_id');
    }

    public function user_tags()
    {
        return $this->hasOne(User_tags::class, 'user_id');
    }

}
