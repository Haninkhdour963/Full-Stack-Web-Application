<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\TechnicianController as AdminTechnicianController;
use App\Http\Controllers\Admin\AdminController as AdminAdminController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\JobPostingController as AdminJobPostingController;
use App\Http\Controllers\Admin\JobBidController as AdminJobBidController;
use App\Http\Controllers\Admin\EscrowPaymentController as AdminEscrowPaymentController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\DisputeController as AdminDisputeController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\AdminActionController as AdminAdminActionController;
use App\Http\Controllers\Client\DashboardController as ClientDashboard;
use App\Http\Controllers\Client\UserController as ClientUserController;
use App\Http\Controllers\Client\JobPostingController as ClientJobPostingController;
use App\Http\Controllers\Client\EscrowPaymentController as ClientEscrowPaymentController;
use App\Http\Controllers\Client\PaymentController as ClientPaymentController;
use App\Http\Controllers\Client\ReviewController as ClientReviewController;
use App\Http\Controllers\Client\ContactController as ClientContactController;
use App\Http\Controllers\Technician\DashboardController as TechnicianDashboard;
use App\Http\Controllers\Technician\TechnicianController as TechnicianTechnicianController;
use App\Http\Controllers\Technician\JobBidController as TechnicianJobBidController;
use App\Http\Controllers\Technician\ReviewController as TechnicianReviewController;
use App\Http\Controllers\Technician\EscrowPaymentController as TechnicianEscrowPaymentController;
use App\Http\Controllers\Technician\PaymentController as TechnicianPaymentController;
use App\Http\Controllers\Technician\ContactController as TechnicianContactController;
use App\Http\Controllers\Page\TechnicianController as PageTechnicianController;
use App\Http\Controllers\Page\ChatController as PageChatController;
use App\Http\Controllers\PaymentController as PaymentController;
use App\Http\Controllers\Page\JobController as PageJobController;
use App\Http\Controllers\Page\ClientController as PageClientController;
use App\Http\Controllers\Page\HomeController as PageHomeController;
use App\Http\Controllers\Page\ContactController as PageContactController;
use App\Http\Controllers\StripePaymentController;

use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
*/

// Home Route
Route::get('/', [PageHomeController::class, 'index'])->name('index');



Route::controller(StripePaymentController::class)->group(function(){

    Route::get('stripe', 'stripe');

    Route::post('stripe', 'stripePost')->name('stripe.post');


    Route::get('stripe', [StripePaymentController::class, 'stripe'])->name('stripe');
Route::post('stripe', [StripePaymentController::class, 'stripePost'])->name('stripe.post');


});

Route::get('/stripe-payment/{bid_id}', [StripePaymentController::class, 'stripePayment'])->name('stripe.payment');

Route::get('/filter-by-location', [PageHomeController::class, 'filterByLocation']);
Route::get('/explore-by-category', [PageHomeController::class, 'exploreByCategory']);

// About Route
Route::get('/about', function () {  return view('about'); })->name('about');

// Client Routes
Route::prefix('client')->group(function () {
    Route::get('/post', [PageClientController::class, 'createJobPost'])->name('page.clients.post');
    Route::post('/post', [PageClientController::class, 'storeJobPost'])->name('page.clients.storeJobPost');
    Route::get('/contract', [PageClientController::class, 'signContract'])->name('page.clients.contract');
    Route::get('/hire', [PageClientController::class, 'hireTechnician'])->name('page.clients.hire');
    Route::get('/contact/{technician_id}', [PageClientController::class, 'showContactForm'])->name('page.clients.showContact');
    Route::post('/send-message', [PageClientController::class, 'sendMessage'])->name('page.clients.sendMessage');
});

Route::post('/technician/handle-bid-response', [PageTechnicianController::class, 'handleBidResponse'])
    ->name('page.technicians.handleBidResponse')
    ->middleware(['auth', 'role:client']);

    Route::post('/technician/respond-to-bid', [PageTechnicianController::class, 'respondToBid'])
    ->name('technician.respondToBid')
    ->middleware('auth');
    
// Technician Routes
Route::prefix('technician')->group(function () {
    Route::get('/profile', [PageTechnicianController::class, 'createProfile'])->name('page.technicians.profile');
    Route::get('/bid', [PageTechnicianController::class, 'bidOnJob'])->name('page.technicians.bid');
    Route::post('/NewBidNotification', [PageTechnicianController::class, 'submitBid'])->name('page.technicians.submitBid');
    Route::post('/accept-contract', [PageTechnicianController::class, 'acceptContract'])->name('page.technicians.acceptContract');
    Route::post('/respond-to-bid', [PageTechnicianController::class, 'respondToBid'])
        ->name('page.technicians.respondToBid')
        ->middleware(['auth', 'role:client']);
    Route::get('/contract', [PageTechnicianController::class, 'manageContracts'])->name('page.technicians.contract');
    Route::get('/form/{jobId?}', [PageTechnicianController::class, 'showForm'])->name('page.technicians.form');
    Route::post('/submitbid', [PageTechnicianController::class, 'submitBid'])->name('page.technicians.submitBid');
    Route::get('/process-payment/{bid_id}', [PageTechnicianController::class, 'processPayment'])->name('page.technicians.processPayment');
    Route::get('/payment-success/{bid_id}', [PageTechnicianController::class, 'paymentSuccess'])->name('page.technicians.paymentSuccess');
    Route::get('/reset-flow', [PageTechnicianController::class, 'resetBidFlow'])->name('page.technicians.resetFlow');
    Route::get('/respond-to-bid/{bid_id}', [PageTechnicianController::class, 'showRespondToBidForm'])
        ->name('page.technicians.showRespondToBidForm')
        ->middleware(['auth', 'role:client']);
        
});

// Contact Route
Route::get('/contact', [PageContactController::class, 'index'])->name('contact');

// Payment Routes
Route::get('/payment', [PaymentController::class, 'index'])->name('Payment');
Route::get('payment', [PaymentController::class, 'showPaypalPage']);
Route::post('payment', [PaymentController::class, 'payment'])->name('payment');
Route::get('success', [PaymentController::class, 'success'])->name('success');
Route::get('cancel', [PaymentController::class, 'cancel'])->name('cancel');

// Default Dashboard route for authenticated users
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes for role-based access (Admin, Client, Technician)

// Admin Routes
Route::middleware(['auth'])->group(function () {
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');
        Route::resource('users', AdminUserController::class)->names('admin.users');
        Route::resource('admins', AdminAdminController::class)->names('admin.admins');
        Route::resource('technicians', AdminTechnicianController::class)->names('admin.technicians');
        Route::get('/admin/technicians/{id}', [AdminTechnicianController::class, 'show']);
        Route::resource('categories', AdminCategoryController::class)->names('admin.categories');
        Route::resource('jobPostings', AdminJobPostingController::class)->names('admin.jobPostings');
        Route::get('/admin/jobPostings/{id}', [AdminJobPostingController::class, 'show']);
        Route::resource('jobBids', AdminJobBidController::class)->names('admin.jobBids');
        Route::get('/admin/jobBids/{id}', [AdminJobBidController::class, 'show']);
        Route::resource('escrowPayments', AdminEscrowPaymentController::class)->names('admin.escrowPayments');
        Route::get('escrowPayments/{id}/view', [AdminEscrowPaymentController::class, 'view'])->name('escrowPayments.view');
        Route::resource('payments', AdminPaymentController::class)->names('admin.payments');
        Route::resource('reviews', AdminReviewController::class)->names('admin.reviews');
        Route::get('reviews', [AdminReviewController::class, 'index'])->name('admin.reviews.index');
        Route::post('reviews/{id}/softDelete', [AdminReviewController::class, 'softDelete'])->name('admin.reviews.softDelete');
        Route::post('reviews/{id}/restore', [AdminReviewController::class, 'restore'])->name('admin.reviews.restore');
        Route::resource('disputes', AdminDisputeController::class)->names('admin.disputes');
        Route::resource('contacts', AdminContactController::class)->names('admin.contacts');
        Route::get('/contacts/{id}', [AdminContactController::class, 'show'])->name('admin.contacts.show');
        Route::resource('adminActions', AdminAdminActionController::class)->names('admin.adminActions');
        Route::get('admin/adminActions/{id}', [AdminAdminActionController::class, 'show'])->name('admin.adminActions.show');
    });

    // Client Routes
    Route::prefix('client')->middleware('role:client')->group(function () {
        Route::get('/dashboard', [ClientDashboard::class, 'index'])->name('client.dashboard');
        Route::resource('users', ClientUserController::class)->names('client.users');
        Route::get('users/{userId}/view-profile', [ClientUserController::class, 'viewProfile']);
        Route::resource('jobPostings', ClientJobPostingController::class)->names('client.jobPostings');
        Route::resource('payments', ClientPaymentController::class)->names('client.payments');
        Route::resource('reviews', ClientReviewController::class)->names('client.reviews');
        Route::resource('contacts', ClientContactController::class)->names('client.contacts');
    });

    // Technician Routes
    Route::prefix('technician')->middleware('role:technician')->group(function () {
        Route::get('/dashboard', [TechnicianDashboard::class, 'index'])->name('technician.dashboard');
        Route::resource('technicians', TechnicianTechnicianController::class)->names('technician.technicians');
        Route::get('/technician/technicians/{id}', [TechnicianTechnicianController::class, 'show']);
        Route::put('/technician/technicians/{id}', [TechnicianTechnicianController::class, 'update']);
        Route::resource('jobBids', TechnicianJobBidController::class)->names('technician.jobBids');
        Route::resource('escrowPayments', TechnicianEscrowPaymentController::class)->names('technician.escrowPayments');
        Route::resource('payments', TechnicianPaymentController::class)->names('technician.payments');
        Route::resource('reviews', TechnicianReviewController::class)->names('technician.reviews');
        Route::resource('contacts', TechnicianContactController::class)->names('technician.contacts');
    });
});

require __DIR__.'/auth.php';