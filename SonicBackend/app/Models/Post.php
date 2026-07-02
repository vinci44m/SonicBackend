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
        'votes',
    ];

    // 1. Definiere, dass dieses Feld immer mitgeliefert werden soll
    protected $appends = ['user_vote'];

    protected $casts = [
        'tags' => 'array',
    ];

    // 2. Erstelle eine Methode, die den Wert ausliest
    public function getUserVoteAttribute()
    {
        // Wir holen den Wert, den wir im Controller mit setAttribute gesetzt haben
        return $this->getAttribute('user_vote') ?? 0;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}