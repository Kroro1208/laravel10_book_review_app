<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['review', 'rating']; // マスアサインメント。複数の属性を一度に代入可能


    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
