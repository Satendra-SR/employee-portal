<?php

namespace App\Models\KnowledgeCafe\Library;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;

class Book extends Model
{
    use SoftDeletes;

    protected $table = 'library_books';
    protected $fillable = ['title', 'author', 'categories', 'isbn', 'thumbnail', 'readable_link', 'self_link'];
    protected $dates = ['deleted_at'];

    public function categories()
    {
        return $this->belongsToMany(BookCategory::class, 'library_book_category', 'library_book_id', 'book_category_id');
    }

    public static function getList($filteredString = false)
    {
        return self::with('categories')
                ->orderBy('title')
                ->where(function ($query) use ($filteredString) {
                        ($filteredString) ? $query->where('title', 'LIKE', "%$filteredString%") : '';
                })->paginate(config('constants.pagination_size'));
    }

    public static function getByCategoryName($categoryName) {
        if(!$categoryName) {
            return false;
        }

        return self::whereHas('categories', function ($query) use($categoryName) {
            $query->where('name', $categoryName);
        })->get();

    }

    public function readers() {
        return $this->belongsToMany(User::class, 'book_readers', 'library_book_id', 'user_id');
    }

    public function markBook($read) {
         $result = ($read) ? $this->readers()->attach(auth()->user()) 
                  : $this->readers()->detach(auth()->user());

        return true;
    }

    public static function getRandomUnreadBook() {
        return self::whereDoesntHave('readers', function ($query) {
            $query->where('id', auth()->id());
        })->inRandomOrder()->first();
    }
}
