<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    use HasFactory;

    Protected $fillable =[
        'class_id',
        'teacher_id',
        'student_id',
        'subject_id',
        'mark',
        'success',
        'semester',
    ];
    
    public function Clas(){
        return $this ->belongsTo(schoolClass::class);
    }
    public function Subject(){
        return $this ->belongsTo(Subject::class);
    }
    public function Teacher(){
        return $this ->belongsTo(Teacher::class);
    }
    public function Student(){
        return $this ->belongsTo(Student::class);
    }
}
