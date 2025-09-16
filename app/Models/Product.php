<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $dateFormat = 'U';
    const CREATED_AT      = 'createdAt';
    const UPDATED_AT      = 'updatedAt';
    const DELETED_AT      = 'deletedAt';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'userId',
        'image',
        'title',
        'tag',
        'description',
        'originalPrice',
        'discountPrice',
        'totalSales',
        'isTopSeller',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
