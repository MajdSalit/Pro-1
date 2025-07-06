<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable=[
        'event_name',
        'date',
        'event_description',
    ];

    public function Supervisor(){
        return $this->belongsTo(Supervisor::class);
    }
}
