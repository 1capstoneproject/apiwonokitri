<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models;

class Images extends Model
{
    use HasFactory;

    protected $table = 'images';

    protected $fillable = [
        'name',
        'path',
        'image',
        'product_id',
    ];

    public function ProductId()
    {
        return $this->belongsTo(Models\Product::class);
    }
}
