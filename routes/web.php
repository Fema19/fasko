<?php

use App\Http\Controllers\{
    BookingController, CategoryController, FacilityController,
    MessageController, RepairReportController, RoomController, UserController
};
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


/* ======================================================
   WELCOME
======================================================*/
Route::get('/', fn() => view('welcome'));
Route::get('/admin', fn() => redirect()->route('login'));
Route::get('/guru', fn() => redirect()->route('login'));
Route::get('/siswa', fn() => redirect()->route('login'));


/* ======================================================
   LOGIN & AUTH
======================================================*/
Route::get('/login', fn() => view('auth.login'))->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        return match (Auth::user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'guru'  => redirect()->route('guru.dashboard'),
            'siswa' => redirect()->route('siswa.dashboard'),
            default => redirect('/')
        };
    }

    return back()->withErrors(['email'=>'Email atau password salah'])->onlyInput('email');
})->name('login.attempt');


/* ======================================================
   LOGOUT
======================================================*/
Route::post('/logout', function(Request $request){
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/')->with('success','Berhasil logout');
})->name('logout');


/* ======================================================
   PUBLIC
======================================================*/
Route::resource('messages', MessageController::class);


/* ======================================================
   ADMIN PANEL
======================================================*/
Route::middleware(['auth','role:admin'])
    ->prefix('admin')->name('admin.')
    ->group(function () {

        Route::get('/dashboard', function(){
            $data = [
                'totalUsers'       => \App\Models\User::count(),
                'totalRooms'       => \App\Models\Room::count(),
                'totalFacilities'  => \App\Models\Facility::count(),
                'pendingBookings'  => \App\Models\Booking::where('status','pending')->count(),
                'reports'          => \App\Models\RepairReport::where('status','pending')->count(),
            ];

            return view('admin.dashboard', $data);
        })->name('dashboard');

        Route::resource('users', UserController::class);
        Route::resource('rooms', RoomController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('facilities', FacilityController::class);

        // Booking (Admin full access)
        Route::get('/bookings/requests', [BookingController::class,'requests'])->name('bookings.requests');
        Route::get('/bookings/history',  [BookingController::class,'history'])->name('bookings.history');
        Route::get('/bookings/history/export', [BookingController::class,'exportHistory'])->name('bookings.history.export');
        Route::get('/bookings/{booking}',[BookingController::class,'show'])->name('bookings.show');
        Route::delete('/bookings/history/reset', [BookingController::class,'resetHistory'])->name('bookings.history.reset');

        Route::put('/bookings/{booking}/approve',  [BookingController::class,'approve'])->name('bookings.approve');
        Route::put('/bookings/{booking}/reject',   [BookingController::class,'reject'])->name('bookings.reject');
        Route::put('/bookings/{booking}/check-in', [BookingController::class,'checkIn'])->name('bookings.checkin');
        Route::put('/bookings/{booking}/complete', [BookingController::class,'complete'])->name('bookings.complete');
        
        // Admin pantau & ubah status laporan
        Route::get('/repair-reports/export', [RepairReportController::class,'export'])->name('repair-reports.export');
        Route::delete('/repair-reports/reset', [RepairReportController::class,'reset'])->name('repair-reports.reset');
        Route::resource('repair-reports', RepairReportController::class)->only(['index']);
        Route::put('/repair-reports/{report}/status', [RepairReportController::class,'updateStatus'])->name('repair-reports.status');
    });


/* ======================================================
   GURU PANEL (BOOKING + APPROVE JIKA PENGURUS RUANGAN)
======================================================*/
Route::middleware(['auth','role:guru'])
    ->prefix('guru')->name('guru.')
    ->group(function () {

        Route::get('/dashboard', function(){
            $room = Auth::user()->room?->load('facilities');
            return view('guru.dashboard', compact('room'));
        })->name('dashboard');

        // Route khusus penanggung jawab ruangan (diletakkan sebelum resource agar tidak tertabrak oleh /bookings/{booking})
        Route::middleware('check.room.owner')->group(function (){
        Route::get('/bookings/requests', [BookingController::class,'requests'])->name('bookings.requests');
        Route::get('/bookings/history',  [BookingController::class,'history'])->name('bookings.history');
        Route::get('/bookings/history/export', [BookingController::class,'exportHistory'])->name('bookings.history.export');
        Route::delete('/bookings/history/reset', [BookingController::class,'resetHistory'])->name('bookings.history.reset');
        Route::put('/bookings/{booking}/approve',  [BookingController::class,'approve'])->name('bookings.approve');
        Route::put('/bookings/{booking}/reject',   [BookingController::class,'reject'])->name('bookings.reject');
        Route::put('/bookings/{booking}/complete', [BookingController::class,'complete'])->name('bookings.complete');
        Route::resource('categories', CategoryController::class)->except(['show']);
    });

        // Guru level normal => booking seperti siswa
        Route::resource('bookings', BookingController::class);

        Route::resource('facilities', FacilityController::class);
        Route::get('/reports/export', [RepairReportController::class,'export'])->name('reports.export');
        Route::delete('/reports/reset', [RepairReportController::class,'reset'])->name('reports.reset');
        Route::resource('reports', RepairReportController::class);
        Route::put('/reports/{report}/status', [RepairReportController::class,'updateStatus'])->name('reports.status');

        // Check-in pemilik booking (guru biasa)
        Route::put('/bookings/{booking}/check-in', [BookingController::class,'checkIn'])->name('bookings.checkin');
    });


/* ======================================================
   SISWA
======================================================*/
Route::middleware(['auth','role:siswa'])
    ->prefix('siswa')->name('siswa.')
    ->group(function(){

        Route::get('/dashboard', function(){
            $u = Auth::id();
            return view('siswa.dashboard',[
                'totalBookings'=>Booking::where('user_id',$u)->count(),
                'approved'=> Booking::where('user_id',$u)->where('status','approved')->count(),
                'pending'=> Booking::where('user_id',$u)->where('status','pending')->count()
            ]);
        })->name('dashboard');

        Route::resource('bookings', BookingController::class);
        Route::resource('reports', RepairReportController::class);
        Route::get('/facilities', [FacilityController::class,'index'])->name('facilities.index');

        // Check-in pemilik booking (siswa)
        Route::put('/bookings/{booking}/check-in', [BookingController::class,'checkIn'])->name('bookings.checkin');
    });
