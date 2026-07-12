<?php

namespace App\Http\Controllers;

use App\Models\Book;


class BookController extends Controller
{
    public function list() {
        $data = [
            'books' => Book::all()
        ];
        return view('book/list', $data);
    }
}
