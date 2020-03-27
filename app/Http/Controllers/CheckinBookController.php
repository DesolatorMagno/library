<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Book;

class CheckinBookController extends Controller
{
    public function store(Book $book)
    {
        try {
            $book->checkin(auth()->user());
        } catch (\Exception $th) {
            return response([], 404);
        }
    }
}
