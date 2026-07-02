<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Discussion extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'user_id',
        'title',
        'content',
    ];

    /** Die Diskussion gehört zu einer Gruppe */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /** Die Diskussion hat einen Autor */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}