<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'chime_meeting_id',
        'started_at',
        'ended_at',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function participants()
    {
        return $this->belongsToMany(User::class);
    }
}
