<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';

    protected $fillable = [
        'name',
        'description',
        'description_details',
        'users_id',
        'price',
        'min_order',
        'duration',
        'location',
        'is_event',
        'is_package',
        'ImagesIds',
    ];

    public function User()
    {
        return $this->belongsTo(Models\User::class, 'users_id');
    }

    public function ImagesIds()
    {
        return $this->hasMany(Models\Images::class);
    }

    public function TotalTerjual(){
        $total = Models\Transaction::where("product_id", $this->id)->where('status', 'paid')->count();
        return $total;
    }

}
