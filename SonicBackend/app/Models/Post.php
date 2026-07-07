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
        // 'votes' entfernen wir hier, da wir es dynamisch berechnen
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    // Praktisches Extra: Du kannst jetzt einfach $post->votes_count aufrufen
    public function getVotesCountAttribute()
    {
        // Beispiel: Up-Votes minus Down-Votes
        return $this->votes()->where('type', 'up')->count() - $this->votes()->where('type', 'down')->count();
    }
}