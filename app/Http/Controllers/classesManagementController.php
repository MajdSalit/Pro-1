<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\schoolClass;
use App\Models\TeacherClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class classesManagementController extends Controller
{
    public function createClasses(Request $request){
    try{
        //validation
        $validation=Validator::make($request->all(),[
            'className'=>'regex:/^\d{1,2}-[A-Z]$/',
            'studentsNum'=>'required|integer',
        ]);
        if($validation->fails()){
            return response()->json([
                'status'=>false,
                'message'=>$validation->errors(),
            ]);
        }
        //create the class
        $class=schoolClass::create([
            'className'=>$request->className,
            'studentsNum'=>$request->studentsNum,
        ]);
        //returning success messgae
        return response()->json([
            'status'=>true,
            'message'=>'class has been created successfully!',
        ]);
    }catch(\Throwable $th){
        return response()->json([
            'status'=>false,
            'message'=>$th->getMessage(),
        ],500);
    }
    }

        public function showClasses(Request $request){
        try{
            //getting all classes
            $classes=schoolClass::all();
            //returning data
            return response()->json([
                'status'=>false,
                'data'=>$classes,
            ]);
        }catch(\Throwable $th){
            return response()->json([
                'status'=>false,
                'message'=>$th->getMessage(),
            ],500);
        }
    }
    
    public function editClass(Request $request){
        try{
            //validation
            $validation=Validator::make($request->all(),[
                'classId'=>'required|integer|exists:classes,id',
                'studentsNum'=>'required|integer'
            ]);
            if($validation->fails()){
                return response()->json([
                    'status'=>false,
                    'message'=>$validation->errors(),
                ]);
            }
            //getting the class
            $class=schoolClass::where('id',$request->classId)->first();
            //editting the class students number
            $class->studentsNum=$request->studentsNum;
            //saving the class after editing
            $class->save();
            //returning success message
            return response()->json([
                'status'=>false,
                'message'=>'class has been edited succesfully!',
                'class'=>$class,
            ]);
        }catch(\Throwable $th){
            return response()->json([
                'status'=>false,
                'message'=>$th->getMessage(),
            ],500);
        }
    }

    public function assignStudentToClass(Request $request){
        try{
            //validation
            $validation=Validator::make($request->all(),[
                'studentId'=>'required|integer|exists:students,id',
                'className'=>'regex:/^\d{1,2}-[A-Z]$/',
            ]);
            if($validation->fails()){
                return response()->json([
                    'status'=>false,
                    'message'=>$validation->errors(),
                ]);
            }
            //getting the student
            $student=Student::where('id',$request->studentId)->first();
            //getting the class
            $class=schoolClass::where('className',$request->className)->first();
            //we need to check if the class has capacity
            $classStudents=Student::where('class_id',$class->id)->get();
            if(count($classStudents)<$class->studentsNum){
                return response()->json([
                    'status'=>false,
                    'message'=>'the class is full',
                ],422);
            }
            //assigning the class to the student
            $student->class_id=$class->id;
            //return success message
            return Response()->json([
                'status'=>true,
                'message'=>'student has been assigned to class successfully!',
            ]);
        }catch(\Throwable $th){
            return response()->json([
                'status'=>false,
                'message'=>$th->getMessage(),
            ],500);
        }
    }

    public function deleteClass(Request $request){
        try{
        //validation
        $validation=Validator::make($request->all(),[
            'classId'=>'required|integer|exists:classes,id',
        ]);
        if($validation->fails()){
            return response()->json([
                'status'=>false,
                'message'=>$validation->errors()
            ]);
        }
        //resetting students in the deleted class
        $students=Student::where('class_id',$request->classId)->get();
        foreach($students as $student){
            $student->class_id=null;
        }
        //deleting the classes
        schoolClass::where('id',$request->classId)->delete();
        //returning success message
        return Response()->json([
            'status'=>true,
            'message'=>'class has been deleted successfully!',
        ]);
        }catch(\Throwable $th){
            return Response()->json([
                'status'=>false,
                'message'=>$th->getMessage(),
            ],500);
        }
    }

        public function showAllTeachers(){
        try{
            //getting all teachers
            $teachers=Teacher::all();
            //returning data
            return response()->json([
                'status'=>true,
                'data'=>$teachers,
            ]);
        }catch(\Throwable $th){
            return response()->json([
                'status'=>false,
                'message'=>$th->getMessage(),
            ],500);
        }
    }

    public function assignTeacherToClass(Request $request){
        try{
            //validation
            $validation=Validator::make($request->all(),[
            'teacherId'=>'required|integer|exists:teachers,id',
            'className'=>'regex:/^\d{1,2}-[A-Z]$/',
            ]);

            if($validation->fails()){
                return response()->json([
                    'status'=>false,
                    'message'=>$validation->errors(),
                ],422);
            }
            //first off all we need to gather the data about the subject to get the right subject id
                //we wiil get the subject that this teacher teaches
                $theacherSubject=Teacher::where('id',$request->teacherId)->value('subject');
                //now we need to get class id by its name
                $class=schoolClass::where('className',$request->className)->first();
                if (!$class) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Class not found',
                    ], 404);
                }
                //now we need to know which grade this class is
                $classParts = explode('-', $request->className);
                $grade = $classParts[0];
                //now we will get the subject from subjects table
                $subject=Subject::where('subjectName',$theacherSubject)->where('grade',$grade)->first();
                if (!$subject) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Subject not found for this grade',
                    ], 404);
                }

                //checking existing assign
                $existingAssign=TeacherClass::where('teacher_id',$request->teacherId)->where('class_id',$class->id)->where('subject_id',$subject->id)->first();
                $alreadyAssigned=TeacherClass::where('class_id',$class->id)->where('subject_id',$subject->id)->first();

                if($existingAssign){
                    return response()->json([
                        'status'=>false,
                        'message'=>'the assign has been made already',
                    ],422);
                }

                if($alreadyAssigned){
                    //getting the assigned tacher details
                    $assignedTeacher=Teacher::where('id',$alreadyAssigned->teacher_id)->first();
                    $assignedTeacherUser=User::where('id',$assignedTeacher->id)->first();
                    return response()->json([
                        'status'=>false,
                        'message'=>'mr ' . $assignedTeacherUser->name . ' ' . $assignedTeacherUser->lastName . ' is already assigned for the same class with the same subject but if you want we can overwrite it',
                    ],422);
                }
            //now we will create a teacher-classes row  
            $classesTeacherAssignedTO=TeacherClass::where('teacher_id',$request->teacherId)->count();
            if($classesTeacherAssignedTO < 3){
                $teacherClass=TeacherClass::firstOrCreate([
                    'teacher_id'=>$request->teacherId,
                    'class_id'=>$class->id,
                    'subject_id'=>$subject->id,
                ]);
            }else{
                return response()->json([
                    'status'=>false,
                    'messgae'=>'the teacher has been assigned to enough classes',
                ],422);
            }
            //returning success message
            return response()->json([
                'status'=>true,
                'message'=>'assignment has been done successfully!',
                'data'=>$teacherClass,
            ]);
        }catch(\Throwable $th){
            return response()->json([
                'status'=>false,
                'message'=>$th->getMessage(),
            ],500);
        }
    }

    public function overWriteTeacherToClass(Request $request){
        try{
            //validation
            $validation=Validator::make($request->all(),[
            'teacherId'=>'required|integer|exists:teachers,id',
            'className'=>'regex:/^\d{1,2}-[A-Z]$/',
            ]);

            if($validation->fails()){
                return response()->json([
                    'status'=>false,
                    'message'=>$validation->errors(),
                ],422);
            }
            //first off all we need to gather the data about the subject to get the right subject id
                //we wiil get the subject that this teacher teaches
                $theacherSubject=Teacher::where('id',$request->teacherId)->value('subject');
                //now we need to get class id by its name
                $class=schoolClass::where('className',$request->className)->first();
                if (!$class) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Class not found',
                    ], 404);
                }
                //now we need to know which grade this class is
                $classParts = explode('-', $request->className);
                $grade = $classParts[0];
                //now we will get the subject from subjects table
                $subject=Subject::where('subjectName',$theacherSubject)->where('grade',$grade)->first();    
                if (!$subject) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Subject not found for this grade',
                    ], 404);
                }

                //deleting the old record
                TeacherClass::where('class_id',$class->id)->where('subject_id',$subject->id)->delete();

            //now we will create a teacher-classes row  
            $classesTeacherAssignedTO=TeacherClass::where('teacher_id',$request->teacherId)->count();
            if($classesTeacherAssignedTO < 3){
                $teacherClass=TeacherClass::firstOrCreate([
                    'teacher_id'=>$request->teacherId,
                    'class_id'=>$class->id,
                    'subject_id'=>$subject->id,
                ]);
            }else{
                return response()->json([
                    'status'=>false,
                    'messgae'=>'the teacher has been assigned to enough classes',
                ],422);
            }
            //returning success message
            return response()->json([
                'status'=>true,
                'message'=>'assignment has been done successfully!',
                'data'=>$teacherClass,
            ]);

        }catch(\Throwable $th){
            return response()->json([
                'status'=>false,
                'message'=>$th->getMessage(),
            ],500);
        }
    }
    public function unassignTeacherToClass(Request $request){
        try{
            //validation
            $validation=Validator::make($request->all(),[
            'teacherId'=>'required|integer|exists:teachers,id',
            'className'=>'regex:/^\d{1,2}-[A-Z]$/',
            ]);

            if($validation->fails()){
                return response()->json([
                    'status'=>false,
                    'message'=>$validation->erros(),
                ],422);
            }
            //first of all we wanna get the claass id based on the classname
            $class=SchoolClass::where('className',$request->className)->first();
            //now we wanna delete the records on the db
            TeacherClass::where('teacher_id',$request->teacherId)->where('class_id',$class->id)->delete();
            //return success message
            return response()->json([
                'status'=>true,
                'message'=>'the teacher has been unassigned successfully!',
            ]);
        }catch(\Throwable $th){
            return response()->json([
                'status'=>false,
                'message'=>$th->getMessage()
            ]);
        }
    }

    public function getPaginateStudents()
    {
        try {

            //get the students from the student table, then User() relation to get the info
            $students = User::with(['student.SchoolClass:id,className'])
                ->where('role', 'student')
                ->get()
                ->map(function ($user) {
                    return [
                        'user_id' => $user->id,
                        'student_id' => $user->student->id,
                        'full_name' => trim(implode(' ', array_filter([$user->name, $user->middleName, $user->lastName]))),
                        'email' => $user->email,
                        'phone' => $user->phoneNumber,
                        'photo' => $user->student->photo,
                        'gpa' => $user->student->Gpa,
                        'class_id' => $user->student->class_id ?? '',
                        'class_name' => $user->student->schoolClass->className ?? '',
                        'absences number' => $user->student->AbsenceStudent->absence_num ?? ''
                    ];
                });

            return response()->json([
                'status' => true,
                'data' => $students
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
