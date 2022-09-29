<?php

namespace App\Observers;

use Illuminate\Support\Str;
use App\Models\BookTranslation;

class BookObserver {
    /**
    * Handle the Book 'created' event.
    *
    * @param  \App\Models\Book  $book
    * @return void
    */

    public function creating( BookTranslation $book ) {
        $book->slug = Str::slug( $book->name );

    }

    /**
    * Handle the Book 'updated' event.
    *
    * @param  \App\Models\Book  $book
    * @return void
    */

    public function updating( BookTranslation $book ) {
        $book->slug = Str::slug( $book->name );

    }

    /**
    * Handle the Book 'deleted' event.
    *
    * @param  \App\Models\Book  $book
    * @return void
    */

    public function deleted( Book $book ) {
        //
    }

    /**
    * Handle the Book 'restored' event.
    *
    * @param  \App\Models\Book  $book
    * @return void
    */

    public function restored( Book $book ) {
        //
    }

    /**
    * Handle the Book 'force deleted' event.
    *
    * @param  \App\Models\Book  $book
    * @return void
    */

    public function forceDeleted( Book $book ) {
        //
    }
}
