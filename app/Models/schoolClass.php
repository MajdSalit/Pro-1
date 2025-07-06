<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class schoolClass extends Model
{
    protected $table = "classes";
    protected $fillable=[
        'className',
        'studentsNum',
    ];
     public function student(){
        return $this->hasMany(Student::class);
     }
     /////////////////////////////////////////////////////////
     public function ExamSchedule(){
        return $this->hasOne(ExamSchedule::class);
     }
     ////////////////////////////////////////////////////////
     public function TeacherClass(){
        return $this->hasmany(TeacherClass::class);
     }
     ////////////////////////////////////////////////////////
     public function Mark(){
        return $this->hasmany(Mark::class);
     }
     ////////////////////////////////////////////////////////
     public function ScheduleBrief(){
        return $this->hasmany(ScheduleBrief::class);
     }
     ////////////////////////////////////////////////////////
     public function Session(){
        return $this->hasmany(Session::class);
     }
   }
