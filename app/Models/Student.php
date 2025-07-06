<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    Protected $fillable = [
        'user_id',
        'class_id',
        'serialNum',
        'schoolGraduatedFrom',
        'photo',
        'Gpa',
        'expelled',
        'justification',
    ];
//_________________________________________________________________________

    public function user(){
        return $this->belongsTo(User::class);
    }
//_________________________________________________________________________

    public function AbsenceStudent(){
        return $this->hasOne(AbsenceStudent::class);
    }
//_________________________________________________________________________

    public function parent(){
        return $this->belongsTo(Parent::class);
    }
//__________________________________________________________________________

    public function Mark(){
        return $this->hasMany(Mark::class);
    }
//__________________________________________________________________________

    public function CheckInTeacher(){
        return $this->belongsTo(CheckInTeacher::class);
    }
//__________________________________________________________________________

    public function schoolClass(){
        return $this->belongsTo(schoolClass::class);
     }
}
