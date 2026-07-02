<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // Diese Felder dürfen per Massen-Zuweisung (Mass Assignment) gespeichert werden
    protected $fillable = [
        'user_id', 
        'content', 
        'commentable_id', 
        'commentable_type'
    ];

    /**
     * Verbindung zum Benutzer (User), der den Kommentar geschrieben hat.
     * Ein Kommentar gehört zu einem User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Die polymorphe Beziehung.
     * Dadurch erkennt Laravel automatisch, ob der Kommentar zu einem Video oder Post gehört,
     * basierend auf 'commentable_type' und 'commentable_id' in deiner Datenbank.
     */
    public function commentable()
    {
        return $this->morphTo();
    }
}