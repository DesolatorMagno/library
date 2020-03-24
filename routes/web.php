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

Route::post('author', 'AuthorController@store')->name('authors.store');
