<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AttachmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SenderController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\UserController;
use Nette\Utils\Image;
use App\Http\Controllers\Controller;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function () {

    // current user auth
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/user/update', [AuthController::class, 'update']);
    Route::put('/user/profile', [AuthController::class, 'updateProfileImage']);
    Route::post('/logout', [AuthController::class, 'logout']);

    //general users
    Route::get('/users', [UserController::class, 'index']); // all users
    Route::post('/users', [UserController::class, 'store']); // create user
    Route::get('/users/{id}', [UserController::class, 'show']); // get single user
    Route::put('/users/{id}', [UserController::class, 'update']); // update user
    Route::delete('/users/{id}', [UserController::class, 'destroy']); // delete user
    Route::put('/users/{id}/password', [UserController::class, 'changePassword']); // change password
    Route::put('/users/{id}/role', [UserController::class, 'changeRole']); // change role

    // Mail
    Route::get('/mails', [MailController::class, 'index']); // all mails
    Route::post('/mails', [MailController::class, 'store']); // create mail
    Route::get('/mails/{id}', [MailController::class, 'show']); // get single mail
    Route::put('/mails/{id}', [MailController::class, 'update']); // update mail
    Route::delete('/mails/{id}', [MailController::class, 'destroy']); // delete mail

    // Activity
    Route::get('/mails/{id}/activities', [ActivityController::class, 'index']); // all activities of mail
    Route::post('/mails/{id}/activities', [ActivityController::class, 'store']); // create activity on a mail
    Route::put('/activities/{id}', [ActivityController::class, 'update']); // update an activity
    Route::delete('/activities/{id}', [ActivityController::class, 'destroy']); // delete an activity

    // Tag
    Route::get('/tags', [TagController::class, 'index']); // all tags 
    Route::post('/tags', [TagController::class, 'store']); // create new tag
    Route::get('/mails/{id}/tags', [TagController::class, 'show']); // all tags of mail
    Route::post('/mails/{id}/tags', [TagController::class, 'sync']); // link tags to mail
    Route::delete('/tags/{id}', [TagController::class, 'destroy']); // unlink tag with mail and delete if it not linked to another

    // Role
    Route::get('/roles', [RoleController::class, 'index']); // all roles
    Route::post('/roles', [RoleController::class, 'store']); // create role
    Route::get('/roles/{id}', [RoleController::class, 'show']); // get single role
    Route::put('/roles/{id}', [RoleController::class, 'update']); // update role
    Route::delete('/roles/{id}', [RoleController::class, 'destroy']); // delete role
    Route::post('/users/{id}/roles', [RoleController::class, 'changeRole']); // add role to user

    //general sender
    Route::get('/senders', [SenderController::class, 'index']); // all senders
    Route::post('/senders', [SenderController::class, 'store']); // create sender
    Route::get('/senders/{id}', [SenderController::class, 'show']); // get single sender
    Route::put('/senders/{id}', [SenderController::class, 'update']); // update sender
    Route::delete('/senders/{id}', [SenderController::class, 'destroy']); // delete sender

    //category
    Route::get('/categories', [CategoryController::class, 'index']); // all categories
    Route::post('/categories', [CategoryController::class, 'store']); // create category
    Route::get('/categories/{id}', [CategoryController::class, 'show']); // get single category
    Route::put('/categories/{id}', [CategoryController::class, 'update']); // update category
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']); // delete category

    //status
    Route::get('/statuses', [StatusController::class, 'index']); // all statuses
    Route::post('/statuses', [StatusController::class, 'store']); // create status
    Route::get('/statuses/{id}', [StatusController::class, 'show']); // get single status
    Route::put('/statuses/{id}', [StatusController::class, 'update']); // update status
    Route::delete('/statuses/{id}', [StatusController::class, 'destroy']); // delete status

    //attachment
    Route::get('/attachments', [AttachmentController::class, 'index']); // all attachments
    Route::post('/attachments', [AttachmentController::class, 'store']); // create attachment
    Route::get('/attachments/{imageUrl}', [AttachmentController::class, 'show']); // get single attachment
    Route::put('/attachments/{id}', [AttachmentController::class, 'update']); // update attachment
    Route::delete('/attachments/{id}', [AttachmentController::class, 'destroy']); // delete attachment

    Route::resource('imageadd', 'Api\AttachmentController@addimage');

    //search
    Route::get('/search', [Controller::class, 'search']); // all attachments

    // Route::get('storage/{filename}', function ($filename) {
    //     return Image::make(storage_path('public/' . $filename))->response();
    // });
});
