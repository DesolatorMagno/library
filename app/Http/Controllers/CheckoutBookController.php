<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Book;

class CheckoutBookController extends Controller
{
    public function store(Book $book)
    {
        $book->checkout(auth()->user());
    }
}
