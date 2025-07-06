<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherClass extends Model
{
    use HasFactory;
    protected $fillable=[
        'teacher_id',
        'class_id',
        'subject_id',
    ];

    public function Teacher(){
        return $this ->belongsToMany(Teacher::class);
    }
    public function Clas(){
        return $this ->belongsToMany(schoolClass::class);
    }
    public function TeacherClass(){
        return $this ->belongsTo(TeacherClass::class);
    }
}
