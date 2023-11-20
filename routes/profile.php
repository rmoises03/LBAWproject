
use Illuminate\Support\Facades\Route;

// Route for the profile page
Route::get('/profile.blade', function () {
    return view('profile.blade');
});
