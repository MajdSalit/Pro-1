<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable=[
        'subjectName',
        'minMark',
        'maxMark',
        'grade',
    ];

    public function TeacherClass(){
        return $this->hasMany(TeacherClass::class);
    }

    public function Session(){
        return $this->hasMany(Session::class);
    }

    public function Mark(){
        return $this->hasMany(Mark::class);
    }
}
