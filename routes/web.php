<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\Setting;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\AccountsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/** for side bar menu active */
function set_active($routes) {
    if (is_array($routes)) {
        foreach ($routes as $route) {
            if (Request::is($route) || Request::is("$route/*")) {
                return 'active';
            }
        }
    } else {
        if (Request::is($routes) || Request::is("$routes/*")) {
            return 'active';
        }
    }
    return '';
}

Route::get('/', function () {
    return view('auth.login');
});

// Authentication Routes
Auth::routes();

Route::group(['namespace' => 'App\Http\Controllers\Auth'], function() {
    // Login routes
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::post('/login', 'authenticate');
        Route::get('/logout', 'logout')->name('logout');
        Route::post('change/password', 'changePassword')->name('change/password');
    });

    // Registration routes
    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', 'register')->name('register');
        Route::post('/register', 'storeUser')->name('register');    
    });
});

// Password Reset Routes
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

// Group Routes with Auth Middleware
Route::group(['middleware' => 'auth'], function () {
    // Main Dashboard Routes
    Route::controller(HomeController::class)->group(function () {
        Route::get('/home', 'index')->name('home');
        Route::get('user/profile/page', 'userProfile')->name('user/profile/page');
        Route::get('teacher/dashboard', 'teacherDashboardIndex')->name('teacher/dashboard');
        Route::get('student/dashboard', 'studentDashboardIndex')->name('student/dashboard');
    });

    // User Management Routes
    Route::controller(UserManagementController::class)->group(function () {
        Route::get('list/users', 'index')->name('list/users');
        Route::post('change/password', 'changePassword')->name('change/password');
        Route::get('view/user/edit/{id}', 'userView');
        Route::post('user/update', 'userUpdate')->name('user/update');
        Route::post('user/delete', 'userDelete')->name('user/delete');
        Route::get('get-users-data', 'getUsersData')->name('get-users-data'); // Get all user data
    });

    // Setting Routes
    Route::controller(Setting::class)->group(function () {
        Route::get('setting/page', 'index')->name('setting/page');
    });

    // Student Routes
    Route::controller(StudentController::class)->group(function () {
        Route::get('student/list', 'student')->name('student/list'); // List students
        Route::get('student/grid', 'studentGrid')->name('student/grid'); // Grid students
        Route::get('student/add/page', 'studentAdd')->name('student/add/page'); // Add student page
        Route::post('student/add/save', 'studentSave')->name('student/add/save'); // Save student
        Route::get('student/edit/{id}', 'studentEdit'); // Edit student
        Route::post('student/update', 'studentUpdate')->name('student/update'); // Update student
        Route::post('student/delete', 'studentDelete')->name('student/delete'); // Delete student
        Route::get('student/profile/{id}', 'studentProfile'); // Student profile
    });

    // Teacher Routes
    Route::controller(TeacherController::class)->group(function () {
        Route::get('teacher/add/page', 'teacherAdd')->name('teacher/add/page'); // Add teacher page
        Route::get('teacher/list/page', 'teacherList')->name('teacher/list/page'); // List teachers
        Route::get('teacher/grid/page', 'teacherGrid')->name('teacher/grid/page'); // Grid teachers
        Route::post('teacher/save', 'saveRecord')->name('teacher/save'); // Save teacher
        Route::get('teacher/edit/{user_id}', 'editRecord'); // Edit teacher
        Route::post('teacher/update', 'updateRecordTeacher')->name('teacher/update'); // Update teacher
        Route::post('teacher/delete', 'teacherDelete')->name('teacher/delete'); // Delete teacher
    });

    // Department Routes
    Route::controller(DepartmentController::class)->group(function () {
        Route::get('department/list/page', 'departmentList')->name('department/list/page'); // List departments
        Route::get('department/add/page', 'indexDepartment')->name('department/add/page'); // Add department page
        Route::get('department/edit/{department_id}', 'editDepartment'); // Edit department
        Route::post('department/save', 'saveRecord')->name('department/save'); // Save department
        Route::post('department/update', 'updateRecord')->name('department/update'); // Update department
        Route::post('department/delete', 'deleteRecord')->name('department/delete'); // Delete department
        Route::get('get-data-list', 'getDataList')->name('get-data-list'); // Get data list
    });

    // Subject Routes
    Route::controller(SubjectController::class)->group(function () {
        Route::get('subject/list/page', 'subjectList')->name('subject/list/page'); // List subjects
        Route::get('subject/add/page', 'subjectAdd')->name('subject/add/page'); // Add subject page
        Route::post('subject/save', 'saveRecord')->name('subject/save'); // Save subject
        Route::post('subject/update', 'updateRecord')->name('subject/update'); // Update subject
        Route::post('subject/delete', 'deleteRecord')->name('subject/delete'); // Delete subject
        Route::get('subject/edit/{subject_id}', 'subjectEdit'); // Edit subject
    });

    // Invoice Routes
    Route::controller(InvoiceController::class)->group(function () {
        Route::get('invoice/list/page', 'invoiceList')->name('invoice/list/page'); // List invoices
        Route::get('invoice/paid/page', 'invoicePaid')->name('invoice/paid/page'); // Paid invoices
        Route::get('invoice/overdue/page', 'invoiceOverdue')->name('invoice/overdue/page'); // Overdue invoices
        Route::get('invoice/draft/page', 'invoiceDraft')->name('invoice/draft/page'); // Draft invoices
        Route::get('invoice/recurring/page', 'invoiceRecurring')->name('invoice/recurring/page'); // Recurring invoices
        Route::get('invoice/cancelled/page', 'invoiceCancelled')->name('invoice/cancelled/page'); // Cancelled invoices
        Route::get('invoice/grid/page', 'invoiceGrid')->name('invoice/grid/page'); // Grid invoices
        Route::get('invoice/add/page', 'invoiceAdd')->name('invoice/add/page'); // Add invoice page
        Route::post('invoice/add/save', 'saveRecord')->name('invoice/add/save'); // Save invoice
        Route::post('invoice/update/save', 'updateRecord')->name('invoice/update/save'); // Update invoice
        Route::post('invoice/delete', 'deleteRecord')->name('invoice/delete'); // Delete invoice
        Route::get('invoice/edit/{invoice_id}', 'invoiceEdit')->name('invoice/edit/page'); // Edit invoice
        Route::get('invoice/view/{invoice_id}', 'invoiceView')->name('invoice/view/page'); // View invoice
        Route::get('invoice/settings/page', 'invoiceSettings')->name('invoice/settings/page'); // Invoice settings
        Route::get('invoice/settings/tax/page', 'invoiceSettingsTax')->name('invoice/settings/tax/page'); // Tax settings
        Route::get('invoice/settings/bank/page', 'invoiceSettingsBank')->name('invoice/settings/bank/page'); // Bank settings
    });

    // Accounts Routes
    Route::controller(AccountsController::class)->group(function () {
        Route::get('account/fees/collections/page', 'index')->name('account/fees/collections/page'); // Account fees collections
        Route::get('add/fees/collection/page', 'addFeesCollection')->name('add/fees/collection/page'); // Add fees collection
        Route::post('fees/collection/save', 'saveRecord')->name('fees/collection/save'); // Save fees collection
    });

    // Course Material Upload Routes
    Route::get('/recent-topics', [FileUploadController::class, 'getRecentTopics']);

    Route::get('faculty/fileupload', [FileUploadController::class, 'index'])->name('faculty/fileupload');
    Route::post('multiple-file-upload', [FileUploadController::class, 'multipleUpload'])->name('multiple.fileupload');
    Route::post('file-delete', [FileUploadController::class, 'destroy'])->name('file.delete');
    Route::post('/delete-multiple-files', [FileUploadController::class, 'destroyMultiple'])->name('file.deleteMultiple');

    Route::get('faculty/tos', [CourseController::class, 'showCourseData'])->name('faculty/tos');

    Route::post('/upload-cis', [CourseController::class, 'uploadCIS'])->name('upload.cis');
    Route::post('/save-topic', [TopicController::class, 'store'])->name('save_topic');
    Route::get('/examtype', [CourseController::class, 'showExamType'])->name('examtype');

    Route::post('/generate-exam-type-table', [TopicController::class, 'generateExamTypeTable'])->name('generate.exam.type.table');
    Route::post('/edit-exam-type', [TopicController::class, 'editExamType'])->name('edit.exam.type');


    Route::get('/exam', function () {
        return view('exam.exam');
    })->name('exam');

    Route::get('/result', function () {
        return view('exam.result');
    })->name('result');
});
