<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matching extends Model
{
    use HasFactory;

    protected $table = 'matching';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sender',
        'receiver',
        'status',
    ];


    public function userSender()
    {
        return $this->belongsTo(User::class, 'sender', 'id');
    }

    public function userReceiver()
    {
        return $this->belongsTo(User::class, 'receiver' , 'id');
    }

}
