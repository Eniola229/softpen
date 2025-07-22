<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

//ADMIN
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\SchoolController;

//SCHOOL
use App\Http\Controllers\School\SchoolAuthController;
use App\Http\Controllers\School\ClassController;
use App\Http\Controllers\School\StudentController;
use App\Http\Controllers\School\TeachersController;
use App\Http\Controllers\School\DepartmentController;
use App\Http\Controllers\School\SubjectController;

//STAFF
use App\Http\Controllers\Staff\StaffAuthController;
use App\Http\Controllers\Staff\StaffStudentController;
use App\Http\Controllers\Staff\StaffResultController;

//STUDENT
use App\Http\Controllers\ResultController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/result', [ResultController::class, 'index'])->name('student.result');
    Route::get('/view-result/{result}', [ResultController::class, 'showMyReportCard'])->name('student.result.view');
});

require __DIR__.'/auth.php';


//ADMIN 
Route::get('admin/login', [AdminAuthController::class, 'index'])->name('admin/login');
Route::post('post/login', [AdminAuthController::class, 'postLogin'])->name('admin-login.post'); 

//AUTH ADMIN
Route::middleware('auth:admin')->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->name('admin-dashboard');
    Route::get('/schools', [SchoolController::class, 'index'])->name('admin/schools');
    Route::post('/schools', [SchoolController::class, 'create'])->name('admin/schools');
    Route::get('add/schools', [SchoolController::class, 'addSchool'])->name('admin/add/schools');
    Route::get('view/schools/{id}', [SchoolController::class, 'view'])->name('admin/view/schools');
    Route::get('/change/{id}', [SchoolController::class, 'changeStatus'])->name('admin/changeStatus');


    Route::get('logout', [AdminAuthController::class, 'logout'])->name('admin/logout');
});

//SCHOOL
Route::get('school/login', [SchoolAuthController::class, 'index'])->name('school/login');
Route::post('school/post/login', [SchoolAuthController::class, 'postLogin'])->name('school-login.post'); 
//AUTH SCHOOL
Route::middleware('auth:school')->prefix('school')->group(function () {
    Route::get('/dashboard', [SchoolAuthController::class, 'dashboard'])->name('school-dashboard');
    Route::get('/class', [ClassController::class, 'index'])->name('school/class');
    Route::post('/class', [ClassController::class, 'create'])->name('/class');
    Route::get('add/class', [ClassController::class, 'addClass'])->name('school/add/class');
    Route::get('/class-remove/{id}', [ClassController::class, 'deleteClass'])->name('/delete/class');
    Route::get('/department', [DepartmentController::class, 'index'])->name('school/department');
    Route::get('add/department', [DepartmentController::class, 'addDepartments'])->name('school/add/department');
    Route::post('/department', [DepartmentController::class, 'create'])->name('school/department');
    Route::get('/view/department/{id}', [DepartmentController::class, 'view'])->name('school/view/department');
    Route::get('/subject', [SubjectController::class, 'index'])->name('school/subject');
    Route::get('add/subject', [SubjectController::class, 'addSubject'])->name('school/add/subject');
    Route::post('/subject', [SubjectController::class, 'create'])->name('school/subject');
    Route::get('/view/subject/{id}', [SubjectController::class, 'view'])->name('school/view/subject');
    Route::get('/student', [StudentController::class, 'index'])->name('student/subject');
    Route::get('add/student', [StudentController::class, 'addStudent'])->name('school/add/student');
    Route::post('add/student', [StudentController::class, 'create'])->name('school/add/student');
    Route::get('/view/student/{id}', [StudentController::class, 'view'])->name('school/view/student/');
    Route::get('/teacher', [TeachersController::class, 'index'])->name('student/subject');
    Route::get('add/teacher', [TeachersController::class, 'addStaff'])->name('school/add/teacher');
    Route::post('add/teacher', [TeachersController::class, 'create'])->name('school/add/teacher');
    Route::get('/view/teacher/{id}', [TeachersController::class, 'view'])->name('school/view/teacher/');

    Route::get('logout', [SchoolAuthController::class, 'logout'])->name('school/logout');
});

Route::get('staff/login', [StaffAuthController::class, 'index'])->name('staff/login');
Route::post('staff/post/login', [StaffAuthController::class, 'postLogin'])->name('staff-login.post'); 

//AUTH STAFF
Route::middleware('auth:staff')->prefix('staff')->group(function () {
    Route::get('dashboard', [StaffAuthController::class, 'dashboard'])->name('staff-dashboard');
    Route::get('classes', [StaffStudentController::class, 'index'])->name('staff-classes');
    Route::get('/view/class/{id}', [StaffStudentController::class, 'students'])->name('staff-students');
    Route::get('/view/student/{id}', [StaffStudentController::class, 'viewStudent'])->name('staff/view/student/');
    Route::post('staff/upload/result', [StaffResultController::class, 'store'])->name('staff.upload.result');
    Route::get('/staff/result-report/{result}', [StaffResultController::class, 'showReportCard'])
        ->name('staff.result.report');



    Route::get('logout', [StaffAuthController::class, 'logout'])->name('staff/logout');
});