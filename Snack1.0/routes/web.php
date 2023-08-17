<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\SnackController;
use App\Http\Controllers\LikeController;
use App\Http\Middleware\LoginMemberCheck;
use App\Http\Middleware\AdministratorCheck;
use App\Http\Middleware\SnackSuggest;
use App\Http\Middleware\LikeProcess;
use App\Http\Middleware\SnackEditCheck;
use App\Http\Controllers\MemberIntegrateController;
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

Route::get('/', function () {
    return view('welcome');
});
//2023.6.4 vueのチェック
Route::get('/practice1', function () {
    return view('vue');
});

//2023.3.6 mypage にアクセスするものには、ミドルウェアでお菓子のsugestをするか？？
Route::get('mypage/login',[MainController::class,'login_index']);
Route::post('mypage/home',[MainController::class,'login'])->middleware(SnackSuggest::class);
Route::get('mypage/home',[MainController::class,'mypage_index'])->middleware(LoginMemberCheck::class)->middleware(SnackSuggest::class);

Route::get('member/add',[MemberController::class,'add']);
Route::post('member/add',[MemberController::class,'create']);

Route::get('mypage/member_delete',[MemberController::class,'delete_index'])->middleware(LoginMemberCheck::class);
Route::post('mypage/member_delete',[MemberCOntroller::class,'delete']);

Route::get('mypage/member_edit',[MemberController::class,'edit_index'])->middleware(LoginMemberCheck::class);
Route::post('mypage/member_edit',[MemberController::class,'edit']);

Route::get('mypage/member_pass_change',[MemberController::class,'pass_change_index'])->middleware(LoginMemberCheck::class);
Route::post('mypage/member_pass_change',[MemberController::class,'pass_change']);

Route::get('mypage/search',[SnackController::class,'get_search'])->middleware(LoginMemberCheck::class)->middleware(SnackSuggest::class);
Route::post('mypage/search',[SnackController::class,'search'])->middleware(SnackSuggest::class);

Route::get('mypage/like_search',[SnackController::class,'like_search'])->middleware(LoginMemberCheck::class)->middleware(SnackSuggest::class);
Route::post('mypage/like_search',[SnackController::class,'like_search'])->middleware(SnackSuggest::class);

Route::get('mypage/recomend_search',[SnackController::class,'recomend_search'])->middleware(LoginMemberCheck::class)->middleware(SnackSuggest::class);
Route::post('mypage/recomend_search',[SnackController::class,'recomend_search'])->middleware(SnackSuggest::class);

Route::get('mypage/recomender_search',[SnackController::class,'recomend_search'])->middleware(LoginMemberCheck::class)->middleware(SnackSuggest::class);

Route::get('mypage/recomend_add',[SnackController::class,'add'])->middleware(LoginMemberCheck::class);
Route::post('mypage/recomend_add',[SnackController::class,'create']);

//2023.3.6 ここ改良の余地あり
Route::get('mypage/snack_delete',[SnackController::class,'delete_index'])->middleware(LoginMemberCheck::class,SnackEditCheck::class);
Route::post('mypage/snack_delete',[SnackController::class,'delete'])->middleware(SnackSuggest::class);

Route::get('mypage/snack_edit',[SnackController::class,'edit_index'])->middleware(LoginMemberCheck::class,SnackEditCheck::class);
Route::post('mypage/snack_edit',[SnackController::class,'edit']);

//2023.3.6 イイネ機能 laravel php のみを使う同期通信。使わない。
//Route::get('mypage/like',[LikeController::class,'like_add_delete'])->middleware(LoginMemberCheck::class)->middleware(SnackSuggest::class)->middleware(LikeProcess::class);
//2023.6.5 イイネ機能jqueryを使う
Route::post('mypage/like', [LikeController::class,'likes'])->name('snacks.like');



Route::get('administrator/index',[MainController::class,'administrator_index'])->middleware(AdministratorCheck::class);

Route::post('administrator/home1',[MainController::class,'administrator_1']);
Route::get('administrator/home1',[MainController::class,'administrator_1_1'])->middleware(AdministratorCheck::class);
Route::get('administrator/home2',[MainController::class,'administrator_2'])->middleware(AdministratorCheck::class);

Route::get('administrator/snack',[MainController::class,'get_administrator_snack'])->middleware(AdministratorCheck::class);
Route::post('administrator/snack',[MainController::class,'administrator_snack']);

Route::get('administrator/snack_recomend',[MainController::class,'administrator_snack_recomender'])->middleware(AdministratorCheck::class);

Route::get('administrator/member',[MainController::class,'get_administrator_member'])->middleware(AdministratorCheck::class);
Route::post('administrator/member',[MainController::class,'administrator_member']);

//同期処理による制限機能snack
// Route::post('administrator/snack_limit',[MainController::class,'snack_limit']);
// Route::post('administrator/snack_unlimit',[MainController::class,'snack_unlimit']);

//2023.6.10 非同期処理による制限機能 snack
Route::post('administrator/snack_limit',[MainController::class,'snack_limit_process']);

//同期による制限機能member
// Route::post('administrator/member_limit',[MainController::class,'member_limit']);
// Route::post('administrator/member_unlimit',[MainController::class,'member_unlimit']);
// Route::get('administrator/member_limit',[MainController::class,'get_administrator_member']);
// Route::get('administrator/member_unlimit',[MainController::class,'get_administrator_member']);

//2023.6.11 非同期による制限機能 member
Route::post('administrator/member_limit',[MainController::class,'member_limit_process']);


Route::get('guest/home',[MainController::class,'guest_index'])->middleware(SnackSuggest::class);
Route::get('guest/search',[SnackController::class,'get_guest_search'])->middleware(SnackSuggest::class);
Route::post('guest/search',[SnackController::class,'guest_search'])->middleware(SnackSuggest::class);
Route::get('guest/recomender_search',[SnackController::class,'guest_recomend_search'])->middleware(SnackSuggest::class);


//member統合情報
Route::get('member/integrate',[MemberIntegrateController::class,'memberIntegrate_index']);
Route::post('member/integrate',[MemberIntegrateController::class,'getMemberFromChat']);