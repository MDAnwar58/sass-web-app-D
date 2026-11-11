<?php

use App\Http\Controllers\LMS\{
    CourseController, LessonController, QuizController,
    AssignmentController, EnrollmentController, CertificateController
};
use Illuminate\Support\Facades\Route;

Route::resource('courses', CourseController::class);
Route::resource('courses.lessons', LessonController::class)->shallow();
Route::resource('courses.quizzes', QuizController::class)->shallow();
Route::resource('courses.assignments', AssignmentController::class)->shallow();
Route::resource('enrollments', EnrollmentController::class)->only(['index', 'store', 'destroy']);
Route::get('certificates/{cert}/download', [CertificateController::class, 'download'])->name('certificates.download');
Route::post('lessons/{lesson}/complete', [LessonController::class, 'markComplete'])->name('lessons.complete');
