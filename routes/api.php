<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidateMasterController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DegreeMasterController;
use App\Http\Controllers\InterviewerMasterController;
use App\Http\Controllers\InterviewMasterController;
use App\Http\Controllers\InterviewModeMasterController;
use App\Http\Controllers\InterviewsController;
use App\Http\Controllers\InterviewTypeMasterController;
use App\Http\Controllers\ModeOfWorkMasterController;
use App\Http\Controllers\RecruitmentStatusMasterController;
use App\Http\Controllers\SkillMasterController;
use App\Http\Controllers\SkillTypeMasterController;
use App\Http\Controllers\SourceMasterController;
use App\Models\Interviews;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::get('allusers', [Controller::class, 'allUsers']);
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('remove/{id}', [Controller::class, 'remove']);
Route::post('logout', [AuthController::class, 'logout']);
Route::post('refresh', [AuthController::class, 'refresh']);

// Route::group(['middleware' => 'auth'], function () {

Route::resource('sourcemaster', SourceMasterController::class)->only(['index', 'store', 'update', 'destroy']);
Route::get('getactivesource', [SourceMasterController::class, 'getActiveSource']);

Route::resource('skilltypemaster', SkillTypeMasterController::class)->only(['index', 'store', 'update', 'destroy']);
Route::get('getactiveskilltype', [SkillTypeMasterController::class, 'getActiveSkillType']);

Route::resource('skillmaster', SkillMasterController::class)->only(['index', 'store', 'update', 'destroy']);
Route::get('getactiveskill', [SkillMasterController::class, 'getActiveSkill']);

Route::resource('recruitmentstatusmaster', RecruitmentStatusMasterController::class)->only(['index', 'store', 'update', 'destroy']);
Route::get('getactiverecruitmentstatus', [RecruitmentStatusMasterController::class, 'getActiveRecruitmentStatus']);

Route::resource('modeofworkmaster', ModeOfWorkMasterController::class)->only(['index', 'store', 'update', 'destroy']);
Route::get('getactiveremodeofwork', [ModeOfWorkMasterController::class, 'getAactiveModeOfWork']);

Route::resource('interviewtypemaster', InterviewTypeMasterController::class)->only(['index', 'store', 'update', 'destroy']);
Route::get('getactiveinterviewtype', [InterviewTypeMasterController::class, 'getAactiveInterviewType']);

Route::resource('interviewermaster', InterviewerMasterController::class)->only(['index', 'store', 'update', 'destroy']);
Route::get('getactiveinterviewer ', [InterviewerMasterController::class, 'getAactiveInterviewer']);

Route::resource('interviewmodemaster', InterviewModeMasterController::class)->only(['index', 'store', 'update', 'destroy']);
Route::get('getactiveinterviewmode', [InterviewModeMasterController::class, 'getAactiveInterviewMode']);

Route::resource('degreemaster', DegreeMasterController::class)->only(['index', 'store', 'update', 'destroy']);
Route::get('getactivedegree', [DegreeMasterController::class, 'getAactiveDegree']);

Route::resource('candidatemaster', CandidateMasterController::class)->only(['index', 'store', 'show', 'update', 'destroy']);

// Route::resource('interviews', InterviewsController::class)->only(['index', 'store', 'show', 'update', 'destroy']);

Route::get('interviews', [InterviewsController::class, 'index']);
Route::get('interviews/{id}', [InterviewsController::class, 'show']);
Route::post('interviews', [InterviewsController::class, 'store']);
Route::put('interviews/{interviews}', [InterviewsController::class, 'update']);
Route::delete('interviews/{interviews}', [InterviewsController::class, 'destroy']);

// });
