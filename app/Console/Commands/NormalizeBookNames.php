<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Book;

class NormalizeBookNames extends Command
{
    protected $signature = 'books:normalize-names';
    protected $description = 'Normalize book names to lowercase';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $books = Book::all();
        foreach ($books as $book) {
            $book->bookname = strtolower($book->bookname);
            $book->save();
        }

        $this->info('Book names have been normalized.');
    }
}
