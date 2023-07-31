<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\ProfileController;
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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['if.guest'])->group(function () {
    // Здесь находятся защищенные маршруты, требующие авторизации
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');

    Route::get('/lists', [ListController::class, 'index'])->name('list.index');
    Route::get('/lists/show/{list}', [ListController::class, 'show'])->name('list.show');
    Route::delete('/list/delete/{list}', [ListController::class,'destroy'])->name('list.delete');
    Route::get('/list/edit/{list}', [ListController::class, 'edit'])->name('list.edit');
    Route::patch('/list/update/{list}', [ListController::class, 'update'])->name('list.update');
    Route::post('/lists/{list}/share', [ListController::class, 'shareListWithUser'])->name('list.share');
    Route::get('/lists/available', [ListController::class, 'availableLists'])->name('list.available');

    Route::put('/items/{id}/complete', [ItemController::class, 'complete'])->name('item.complete');
    Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('item.edit');
    Route::patch('/items/update/{item}', [ItemController::class, 'update'])->name('item.update');
    Route::get('/items/edit-photo/{item}', [ItemController::class, 'editPhoto'])->name('item.edit.photo');
    Route::patch('/items/photo-update/{item}', [ItemController::class, 'updatePhoto'])->name('item.update.photo');
    Route::get('/items/add-photo/{item}', [ItemController::class, 'addPhoto'])->name('item.add.photo');
    Route::get('/items/filter-by-tags/{list}', [ItemController::class, 'filterByTags'])->name('items.filterByTags');
    Route::get('/items/search/{list}', [ItemController::class, 'searchItems'])->name('items.search');
    Route::delete('/item/delete/{item}', [ItemController::class,'destroy'])->name('item.delete');

});




