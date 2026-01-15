<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ExcelController;
use App\Http\Controllers\Admin\PrintController;

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

use App\Http\Middleware\LogVisitor;


Route::middleware([LogVisitor::class])->group(function () {
Route::get('/', [HomeController::class, 'index']);
});

Route::get('/manual/{file}', [HomeController::class, 'manual']);

Route::get('/load/question', [HomeController::class, 'loadQuestion']);
Route::post('/question', [HomeController::class, 'questionStore']);
Route::get('/load/comments/type', [HomeController::class, 'loadCommentType']);
Route::post('/comment', [HomeController::class, 'commentStore']);
Route::get('/get/banner', [HomeController::class, 'bannerGet']);

Route::get('/complaint', [ComplaintController::class, 'index']);
Route::post('/complaint', [ComplaintController::class, 'store']);
Route::get('/follow', [ComplaintController::class, 'follow']);
Route::post('/follow', [ComplaintController::class, 'followCheck']);
Route::get('/get/district/{province_id}', [ComplaintController::class, 'getDistrict']);
Route::get('/get/subdistrict/{district_id}', [ComplaintController::class, 'getSubDistrict']);
Route::get('/get/zipcode/{subdistrict_id}', [ComplaintController::class, 'getZipcode']);


Route::get('/cookies-policy', [HomeController::class, 'cookiesPolicy']);
Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy']);
Route::get('/security-policy', [HomeController::class, 'securityPolicy']);
Route::get('/web-policy', [HomeController::class, 'webPolicy']);

// Route::get('/test', [ComplaintController::class, 'test']);


Route::middleware('auth')->group(function () {
    Route::get('/admin', [AdminController::class, 'index']);
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin');
    Route::get('/get/complaint/type', [AdminController::class, 'getComplaint']);
    Route::get('/complaint/accept', [AdminController::class, 'accept']);
    Route::get('/complaint/follow', [AdminController::class, 'follow']);
    Route::get('/complaint/receive', [AdminController::class, 'receive']);
    Route::get('/complaint/alter', [AdminController::class, 'alter']);
    Route::get('/complaint/userfollow', [AdminController::class, 'userfollow']);
    Route::get('/complaint/create', [AdminController::class, 'create']);
    Route::post('/complaint/store', [AdminController::class, 'complaintStore']);
    Route::delete('/admin/complaint', [AdminController::class, 'remove']);
    Route::get('/get/master/data', [AdminController::class, 'getMasterData']);
    Route::get('/get/complaint/by/{id}', [AdminController::class, 'getComplaintById']);

    Route::post('/complaint/cancel/{id}', [AdminController::class, 'cancel']);
    Route::post('/complaint/send/{id}', [AdminController::class, 'send']);
    Route::post('/complaint/receive/{id}', [AdminController::class, 'receiveUpdate']);
    Route::post('/complaint/reset/{id}', [AdminController::class, 'resetUpdate']);

    Route::post('/complaint/add/comment/{id}', [AdminController::class, 'addComment']);
    Route::post('/complaint/add/reply/{id}', [AdminController::class, 'addReply']);
    Route::delete('/complaint/delete/comment', [AdminController::class, 'deleteComment']);
    Route::post('/complaint/save/trace/{id}', [AdminController::class, 'saveTrace']);
    Route::post('/complaint/save/answer/{id}', [AdminController::class, 'saveAnswer']);
    Route::post('/complaint/save/report/{id}', [AdminController::class, 'saveReport']);

    Route::post('/complaint/send/reunit/{id}', [AdminController::class, 'sendError']);
    Route::post('/complaint/send/step/{id}', [AdminController::class, 'sendNextStep']);
    
    Route::get('/complaint/print/{code}', [PrintController::class, 'index']);



    
    Route::get('/admin/comment', [CommentController::class, 'index'])->name('comment');


    
    Route::get('/admin/setting', [SettingController::class, 'index']);

    Route::get('/admin/setting/unit', [SettingController::class, 'unit']);
    Route::get('/admin/setting/unit/load', [SettingController::class, 'unitLoad']);
    Route::post('/admin/setting/unit', [SettingController::class, 'unitStore']);
    Route::delete('/admin/setting/unit', [SettingController::class, 'unitRemove']);

    Route::get('/admin/setting/type', [SettingController::class, 'type']);
    Route::get('/admin/setting/type/load', [SettingController::class, 'typeLoad']);
    Route::post('/admin/setting/type', [SettingController::class, 'typeStore']);

    Route::get('/admin/setting/timefinish', [SettingController::class, 'timefinish']);
    Route::post('/admin/setting/timefinish', [SettingController::class, 'timefinishStore']);

    Route::get('/admin/setting/complaint', [SettingController::class, 'complaint']);
    Route::post('/admin/setting/complaint', [SettingController::class, 'complaintStore']);
    
    Route::get('/admin/setting/methods', [SettingController::class, 'methods']);
    Route::get('/admin/setting/methods/load', [SettingController::class, 'methodsLoad']);
    Route::post('/admin/setting/methods', [SettingController::class, 'methodsStore']);

    Route::get('/admin/setting/person', [SettingController::class, 'person']);
    Route::get('/admin/setting/person/load', [SettingController::class, 'personLoad']);
    Route::post('/admin/setting/person', [SettingController::class, 'personStore']);

    Route::get('/admin/setting/severity', [SettingController::class, 'severity']);
    Route::get('/admin/setting/severity/load', [SettingController::class, 'severityLoad']);

    Route::get('/admin/setting/manual', [SettingController::class, 'manual']);

    Route::get('/admin/setting/question', [SettingController::class, 'question']);
    Route::get('/admin/setting/question/load', [SettingController::class, 'questionLoad']);
    Route::post('/admin/setting/question', [SettingController::class, 'questionStore']);

    Route::get('/admin/setting/comment', [SettingController::class, 'comment']);
    Route::get('/admin/setting/comment/load', [SettingController::class, 'commentLoad']);
    Route::post('/admin/setting/comment', [SettingController::class, 'commentStore']);

    Route::get('/admin/setting/banner', [SettingController::class, 'banner']);
    Route::get('/admin/setting/banner/load', [SettingController::class, 'bannerLoad']);
    Route::post('/admin/setting/banner', [SettingController::class, 'bannerStore']);
    Route::delete('/admin/setting/banner', [SettingController::class, 'bannerRemove']);

    Route::get('/admin/setting/popup', [SettingController::class, 'popup']);
    Route::post('/admin/setting/popup', [SettingController::class, 'popupStore']);



    Route::get('/admin/setting/users', [SettingController::class, 'users']);
    Route::get('/admin/setting/users/load', [SettingController::class, 'usersLoad']);
    Route::post('/admin/setting/users', [SettingController::class, 'usersStore']);
    Route::delete('/admin/setting/users', [SettingController::class, 'usersRemove']);

    Route::get('/admin/setting/telegram', [SettingController::class, 'telegram']);
    Route::get('/admin/setting/telegram/load', [SettingController::class, 'telegramLoad']);
    Route::post('/admin/setting/telegram', [SettingController::class, 'telegramStore']);
    Route::delete('/admin/setting/telegram', [SettingController::class, 'telegramRemove']);

    Route::get('/change/password', [SettingController::class, 'changePassword']);
    Route::post('/change/password', [SettingController::class, 'changePasswordStore']);



     Route::get('/admin/report', [ReportController::class, 'index']);
     Route::get('/admin/report/summary', [ReportController::class, 'summary']);
     Route::get('/admin/report/summary/load', [ReportController::class, 'summaryLoad']);
     Route::get('/admin/report/type', [ReportController::class, 'type']);
     Route::get('/admin/report/type/load', [ReportController::class, 'typeLoad']);
     Route::get('/admin/report/methods', [ReportController::class, 'methods']);
     Route::get('/admin/report/methods/load', [ReportController::class, 'methodsLoad']);
     Route::get('/admin/report/person', [ReportController::class, 'person']);
     Route::get('/admin/report/person/load', [ReportController::class, 'personLoad']);
     Route::get('/admin/report/office', [ReportController::class, 'office']);
     Route::get('/admin/report/office/load', [ReportController::class, 'officeLoad']);
     Route::get('/admin/report/history', [ReportController::class, 'history']);
     Route::get('/admin/report/history/year', [ReportController::class, 'historyYear']);
     Route::get('/admin/report/history/month', [ReportController::class, 'historyMonth']);
     Route::get('/admin/report/history/day', [ReportController::class, 'historyDay']);
     Route::get('/admin/report/questionnaire', [ReportController::class, 'questionnaire']);
     Route::get('/admin/report/questionnaire/load', [ReportController::class, 'questionnaireLoad']);
     Route::get('/admin/report/severity', [ReportController::class, 'severity']);
     Route::get('/admin/report/severity/load', [ReportController::class, 'severityLoad']);


     Route::post('/admin/excel/office', [ExcelController::class, 'office']);
     Route::post('/admin/excel/methods', [ExcelController::class, 'methods']);
     Route::post('/admin/excel/history', [ExcelController::class, 'history']);
     Route::post('/admin/excel/type', [ExcelController::class, 'type']);
     Route::post('/admin/excel/person', [ExcelController::class, 'person']);
     Route::post('/admin/excel/summary', [ExcelController::class, 'summary']);
     Route::post('/admin/excel/severity', [ExcelController::class, 'severity']);


     




}); 

require __DIR__.'/auth.php';
