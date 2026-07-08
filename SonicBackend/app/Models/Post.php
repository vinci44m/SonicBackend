<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'tags',
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    // NEU: sorgt dafür, dass votes_count im JSON mit ausgegeben wird
    protected $appends = ['votes_count'];

    public function user() { return $this->belongsTo(User::class); }
    public function comments() { return $this->hasMany(Comment::class); }
    public function votes() { return $this->hasMany(Vote::class); }

    public function getVotesCountAttribute()
    {
        return $this->votes()->where('type', 'up')->count()
             - $this->votes()->where('type', 'down')->count();
    }
}