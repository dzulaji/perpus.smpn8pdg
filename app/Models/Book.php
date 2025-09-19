<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'code', 'cover', 'author', 'year', 'publisher', 'description', 'category', 'stock', 'call_number', 'pages', 'language', 'isbn_issn', 'content_type', 'media_type', 'link', 'carrier_type', 'edition', 'subject', 'specific_detail_info'
    ];
}
