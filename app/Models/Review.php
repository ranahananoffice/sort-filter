<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory, SoftDeletes;
    protected $dateFormat = 'U';
    const CREATED_AT      = 'createdAt';
    const UPDATED_AT      = 'updatedAt';
    const DELETED_AT      = 'deletedAt';


    protected $fillable = [
        'userId',
        'productId',
        'rating',
        'comment',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'productId');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

}
