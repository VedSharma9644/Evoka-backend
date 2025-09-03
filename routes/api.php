<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SubscriptionController;
use App\Helpers\CorsHelper;
use Illuminate\Http\Request;

// Add CORS headers to all API routes
Route::options('{any}', function () {
    return CorsHelper::handlePreflight();
})->where('any', '.*');

// Test endpoint to verify CORS
Route::get('/test-cors', function () {
    return CorsHelper::corsJson([
        'message' => 'CORS is working!',
        'timestamp' => now(),
        'origin' => request()->header('Origin'),
        'headers' => request()->headers->all()
    ]);
});

// Simple health check endpoint
Route::get('/health', function () {
    return CorsHelper::corsJson([
        'status' => 'ok',
        'message' => 'Backend is running',
        'timestamp' => now(),
        'version' => '1.0.0'
    ]);
});

// Route::middleware(['stateful', 'auth:sanctum'])->get('/user', [AuthController::class, 'user']);
Route::middleware( 'auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware([ 'auth:sanctum'])->get('/login_status', [AuthController::class, 'login_status']);

Route::any('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::any('/google/callback', [AuthController::class, 'google_socialite']);
Route::any('/facebook/callback', [AuthController::class, 'facebook_socialite']);
 
Route::post('/newsletter', [AuthController::class, 'newsletter']);


// 
Route::middleware([ 'auth:sanctum'])->post('/update-profile', [AuthController::class, 'update']);
Route::middleware([ 'auth:sanctum'])->post('/create-event', [EventController::class, 'store']);
Route::middleware([ 'auth:sanctum'])->get('/my-events', [EventController::class, 'myEvents']);

Route::middleware([ 'auth:sanctum'])->post('/events/{id}', [EventController::class, 'update']);
Route::middleware([ 'auth:sanctum'])->post('/events/{id}/change_status', [EventController::class, 'chnage_status']);


Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{id}', [EventController::class, 'singleEvent']);
Route::middleware([ 'auth:sanctum'])->post('/events/{id}/participate', [EventController::class, 'participate']);
Route::middleware([ 'auth:sanctum'])->post('/events/{id}/rate', [EventController::class, 'rate']);
Route::middleware([ 'auth:sanctum'])->get('/events/{id}/my-ratings', [EventController::class, 'my_ratings']);
Route::middleware([ 'auth:sanctum'])->post('/events/{id}/comments', [EventController::class, 'add_comments']);
Route::middleware([ 'auth:sanctum'])->post('/events/{id}/send_chat', [EventController::class, 'send_chat']);
Route::middleware([ 'auth:sanctum'])->get('/my-participation', [EventController::class, 'my_participation']);
Route::middleware([ 'auth:sanctum'])->get('/events/{id}/chat', [EventController::class, 'chat']);


Route::middleware([ 'auth:sanctum'])->post('/subscription/buy', [SubscriptionController::class, 'start']);
Route::middleware([ 'auth:sanctum'])->get('/subscription', [SubscriptionController::class, 'activePlan']);

// 