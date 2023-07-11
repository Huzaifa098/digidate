<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Tags extends Model
{
    use HasFactory;

    protected $table = 'tags';

    /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */
   protected $fillable = [
        'name',
        'admin_id',
   ];

   public function user()
    {
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }

    public function user_tags()
    {
        return $this->hasMany(User_tags::class, 'tag_id');
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
