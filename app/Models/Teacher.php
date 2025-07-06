<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    Protected $fillable=[
        'user_id',
        'certification',
        'photo',
        'subject',
        'salary',
    ];

//______________________________________________________________________________________

    public function user(){
        return $this->belongsTo(User::class);
    }
//_______________________________________________________________________________________

    public function Mark(){
        return $this ->hasMany(Mark::class);
    }
//_______________________________________________________________________________________

    public function AbsenceTeacher(){
        return $this ->hasOne(AbsenceTeacher::class);
    }
//_______________________________________________________________________________________

    public function checkInTeacher(){
        return $this ->hasMany(checkInTeacher::class);
    }

//_______________________________________________________________________________________

    public function teacherClass(){
        return $this ->hasMany(TeacherClass::class);
    }
//_______________________________________________________________________________________


}
