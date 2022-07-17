<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use App\Http\Requests\BookPostRequest;
use App\Models\Author;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;


class BookController extends Controller
{
    public function create()
    {
        $authors = Author::where("status", "1")->get();
        return view("admin.books.add", compact("authors"));
    }
    public function show(Book $book)
    {
        $book = Book::where("id", $book->id)->firstOrFail();
        $authors = Author::where("status", "1")->get();
        return view("admin.books.update", compact("authors", "book"));
    }
    public function index()
    {
        $books = Book::all();
        return view("admin.books.list", compact("books"));
    }

    //FormRequest
    public function store(BookPostRequest $request)
    {
        $book = new Book();
        if ($request->hasFile('image')) {
            $filePath = Storage::disk('storage')->put('books', $request->file("image"), 'public');
        } else {
            $filePath = "no-image/no-image.jpeg";
        }

        $book->name = $request->input('name');
        $book->book_no = $request->input('book_no');
        $book->author_id = $request->input('author_id');
        $book->status = $request->input('status');
        $book->image = $filePath;
        $str = Str::slug($request->name, '-');
        $book->slug = $str;

        if ($book->save()) {
            Session::flash('bookRegistrationSuccessful', 'Kitap Kaydı Başarılı!');
        } else {
            Session::flash('bookRegistrationFailed', 'Kitap Kaydı Başarısız!');
        }
        return redirect()->action([BookController::class, 'index']);
    }
    //FormRequest
    public function edit(Request $request, Book $book)
    {
        $oldbook = Book::where("id", $book->id)->firstOrFail();
        if ($request->hasFile('image')) {
            $filePath = Storage::disk('storage')->put('books', $request->file("image"), 'public');
        } else {
            $filePath = $oldbook->image;
        }
        $str = Str::slug($request->name, '-');
        $bookUpdate = Book::where("id", $book->id)->update([
            "name" => $request->input('name'),
            "author_id" => $request->input('author_id'),
            "book_no" => $request->input('book_no'),
            "status" => $request->input('status'),
            "image" => $filePath,
            "slug" => $str
        ]);
        if ($bookUpdate) {
            if ($oldbook->image != "no-image/no-image.jpeg") {
                Storage::disk('storage')->delete($oldbook->image);
            }
            Session::flash('bookUpdateSuccessful', 'Kitap Güncelleme Başarılı!');
        } else {
            Session::flash('bookUpdateFailed', 'Kitap Güncelleme Başarısız!');
        }
        return $this->show($book);
    }

    public function destroy(Book $book)
    {
        $deletedBook = Book::where("id", $book->id)->firstOrFail();
        $deletedBook->delete();
        if ($deletedBook) {
            if ($book->image != "no-image/no-image.jpeg") {
                Storage::disk('storage')->delete($book->image);
            }
            Session::flash('bookDeletionSuccessful', 'Kitap Silme Başarılı!');
        } else {
            Session::flash('bookDeletionFailed', 'Kitap Silme Başarısız!');
        }
        return redirect()->action([BookController::class, 'index']);
    }
}
