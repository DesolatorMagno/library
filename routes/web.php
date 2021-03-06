<?php

use Illuminate\Support\Facades\Route;

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

Route::post('/books', 'BooksController@store')->name('books.store');
Route::patch('/books/{book}', 'BooksController@update')->name('books.update');
Route::delete('/books/{book}', 'BooksController@destroy')->name('books.destroy');
Route::get('/books', 'BooksController@index')->name('books.index');
Route::get('/books/{book}', 'BooksController@show')->name('books.show');

Route::post('/authors', 'AuthorController@store')->name('authors.store')->middleware('auth');
Route::get('/authors/create', 'AuthorController@create')->name('authors.create');

Route::post('/checkout/{book}', 'CheckoutBookController@store')->name('checkout.store')->middleware('auth');
Route::post('/checkin/{book}', 'CheckinBookController@store')->name('checkin.store')->middleware('auth');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
