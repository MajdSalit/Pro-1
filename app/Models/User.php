<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'middleName',
        'lastName',
        'phoneNumber',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

///////////////////////////////////////////////////the realations///////////////////////////////////////////


    public function teacher(){
        return $this->hasOne(Teacher::class);
    }
 //________________________________________________________________________

    public function student(){
        return $this->hasOne(Student::class);
    }                                                                     
//_________________________________________________________________________

    public function supervisor(){
        return $this->hasOne(Supervisor::class);
    }
//_________________________________________________________________________

    public function application(){
        return $this->hasOne(Application::class);
    }
//_________________________________________________________________________
    public function Userpermission(){
        return $this->hasMany(UserPermission::class);
    }
//_________________________________________________________________________
    public function Parent(){
        return $this->hasOne(Parent::class);
    }
//_________________________________________________________________________
    public function passwordOtp()
    {
        return $this->hasOne(PasswordOtp::class);
    }
//_________________________________________________________________________
    public function CheckInEmployee()
    {
        return $this->hasmany(CheckInEmployee::class);
    }
//_________________________________________________________________________
    public function Vacation()
    {
        return $this->hasOne(Vacation::class);
    }

}
