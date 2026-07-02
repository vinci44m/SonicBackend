<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'created_by',
    ];

    /** Wer hat die Gruppe gegründet? */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** Alle Mitgliedschaften dieser Gruppe */
    public function members()
    {
        return $this->hasMany(GroupMember::class);
    }

    /** Alle Posts/Diskussionen dieser Gruppe */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function discussions()
    {
        return $this->hasMany(Discussion::class);
    }
}
