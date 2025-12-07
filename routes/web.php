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
use App\Http\Controllers\Staff\StaffCBTController;
use App\Http\Controllers\Staff\ExamController;
use App\Http\Controllers\Staff\QuestionController;

//STUDENT
use App\Http\Controllers\Student\StudentAuthController;
use App\Http\Controllers\Student\StudentExamController;
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
    Route::get('/profile', [AdminAuthController::class, 'profile'])->name('admin/profile');
    Route::post('/profile', [AdminAuthController::class, 'update'])->name('admin/profile');
    Route::get('/schools', [SchoolController::class, 'index'])->name('admin/schools');
    Route::post('/schools', [SchoolController::class, 'create'])->name('admin/schools');
    Route::get('add/schools', [SchoolController::class, 'addSchool'])->name('admin/add/schools');
    Route::get('view/schools/{id}', [SchoolController::class, 'view'])->name('admin/view/schools');
    Route::get('/change/{id}', [SchoolController::class, 'changeStatus'])->name('admin/changeStatus');
    Route::get('/activate/{id}', [SchoolController::class, 'Activate'])->name('admin/Activate');

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
    Route::get('/view/class/{id}', [ClassController::class, 'students'])->name('staff-students');
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
    Route::get('/student/delete/{id}', [StudentController::class, 'deleteStudent'])->name('school/student/deleteStudent');
    Route::post('add/student', [StudentController::class, 'create'])->name('school/add/student');
    Route::get('/view/student/{id}', [StudentController::class, 'view'])->name('school/view/student/');
    Route::get('/student/change/{id}', [StudentController::class, 'changeStatus'])->name('school/student/changeStatus');
    Route::get('/teacher', [TeachersController::class, 'index'])->name('teacher/all');
    Route::get('add/teacher', [TeachersController::class, 'addStaff'])->name('school/add/teacher');
    Route::post('add/teacher', [TeachersController::class, 'create'])->name('school/add/teacher');
    Route::get('/view/teacher/{id}', [TeachersController::class, 'view'])->name('school/view/teacher/');
    Route::get('/result-report/{result}', [StaffResultController::class, 'showReportCard'])->name('school.result.report');
    Route::get('/change/{id}', [TeachersController::class, 'changeStatus'])->name('school/teacher/changeStatus');
    Route::get('/teacher/delete/{id}', [TeachersController::class, 'deleteTeacher'])->name('school/teacher/deleteTeacher');


    Route::post('promote-students', [StudentController::class, 'promoteAll'])->name('school.promote.students');

    

    Route::get('logout', [SchoolAuthController::class, 'logout'])->name('school/logout');
});

Route::get('staff/login', [StaffAuthController::class, 'index'])->name('staff/login');
Route::post('staff/post/login', [StaffAuthController::class, 'postLogin'])->name('staff-login.post'); 

//AUTH STAFF
Route::middleware('auth:staff')->prefix('staff')->group(function () {
    Route::get('dashboard', [StaffAuthController::class, 'dashboard'])->name('staff-dashboard');
    Route::get('classes', [StaffStudentController::class, 'index'])->name('staff-classes');
    Route::get('/view/class/{id}', [StaffStudentController::class, 'students'])->name('staff-students');
    Route::get('/cbt/{id}', [StaffCBTController::class, 'index'])->name('staff-cbt');
    Route::get('/view/student/{id}', [StaffStudentController::class, 'viewStudent'])->name('staff/view/student/');
    Route::post('staff/upload/result', [StaffResultController::class, 'store'])->name('staff.upload.result');
    Route::get('/staff/result-report/{result}', [StaffResultController::class, 'showReportCard'])
        ->name('staff.result.report');

    Route::prefix('classes/{classId}/exams')->group(function () {
        Route::get('/', [ExamController::class, 'index'])->name('staff.exams.index');
        Route::get('/create', [ExamController::class, 'create'])->name('staff.exams.create');
        Route::post('/', [ExamController::class, 'store'])->name('staff.exams.store');
        Route::get('/{examId}', [ExamController::class, 'show'])->name('staff.exams.show');
        Route::get('/{examId}/edit', [ExamController::class, 'edit'])->name('staff.exams.edit');
        Route::put('/{examId}', [ExamController::class, 'update'])->name('staff.exams.update');
        Route::delete('/{examId}', [ExamController::class, 'destroy'])->name('staff.exams.destroy');
        Route::post('/{examId}/publish', [ExamController::class, 'publish'])->name('staff.exams.publish');

        Route::prefix('{examId}/questions')->group(function () {
            Route::get('/create', [QuestionController::class, 'create'])->name('staff.questions.create');
            Route::post('/', [QuestionController::class, 'store'])->name('staff.questions.store');
            Route::get('/{questionId}/edit', [QuestionController::class, 'edit'])->name('staff.questions.edit');
            Route::put('/{questionId}', [QuestionController::class, 'update'])->name('staff.questions.update');
            Route::delete('/{questionId}', [QuestionController::class, 'destroy'])->name('staff.questions.destroy');
        });
    });
    
    Route::get('logout', [StaffAuthController::class, 'logout'])->name('staff/logout');
});

Route::get('student/login', [StudentAuthController::class, 'index'])->name('student/login');
Route::post('student/post/login', [StudentAuthController::class, 'postLogin'])->name('student-login.post'); 

//AUTH STUDENT
Route::middleware('auth:student')->prefix('student')->group(function () {
    Route::get('dashboard', [StudentExamController::class, 'dashboard'])->name('student-dashboard');
    
    // Exam routes
    Route::get('exam/{examId}/start', [StudentExamController::class, 'startExam'])->name('student.exam.start');
    Route::get('exam/{examId}/take', [StudentExamController::class, 'takeExam'])->name('student.exam.take');
    Route::post('exam/{examId}/submit', [StudentExamController::class, 'submitExam'])->name('student.exam.submit');
    Route::post('exam/{examId}/save-answer', [StudentExamController::class, 'saveAnswer'])->name('student.exam.save-answer');
    Route::get('exam/result/{resultId}', [StudentExamController::class, 'showResult'])->name('student.exam.result');
    
    // Results
    Route::get('/result-report/{result}', [StaffResultController::class, 'showReportCard'])->name('student.result.report');
    Route::get('results', [StudentAuthController::class, 'Result'])->name('student.results');
    
    Route::get('logout', [StudentAuthController::class, 'logout'])->name('student/logout');
});