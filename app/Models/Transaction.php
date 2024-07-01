<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models;

class Transaction extends Model
{
    use HasFactory;

    protected $table = "transactions";

    protected $fillable = [
        'code',
        'tourism_id',
        'user_id',
        'product_id',
        'price',
        'quantity',
        'total',
        'status',
        'date',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model)
        {
            if(empty($model->code))
            {
                $model->code = self::generateTransactionCode();
            }
            if(empty($model->date))
            {
                $model->date = Carbon::now()->format("Y-m-d H:i:s");
            }
        });

        static::deleting(function ($model)
        {
            if(!in_array($model->status, ["draft", "cancel"]))
            {
                throw new \Exception("Failed delete transaction because state not in draft");
            }

        });
    }

    protected static function generateTransactionCode()
    {
        $timestamp = now()->format('YmdHis'); // Current timestamp in the format YYYYMMDDHHMMSS
        $randomDigits = str_pad(random_int(0, 999), 3, '0', STR_PAD_LEFT); // 3 random digits
        $code = $timestamp . $randomDigits;

        // Ensure the code length matches the required length
        return substr($code, 0, 12);
    }

    public function Product(){
        return $this->belongsTo(Models\Product::class, "product_id");
    }

    public function Customer(){
        return $this->belongsTo(Models\User::class, "user_id");
    }
}
