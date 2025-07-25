<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSchedule extends Model
{
    use HasFactory;

    protected $fillable=[
        'class_id',
        'schedule_pdf',
    ];
    public function Clas(){
        return $this->belongsTo(schoolClass::class);
    }
}
