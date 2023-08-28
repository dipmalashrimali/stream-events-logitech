<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchSale extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'login_id',
        'item_name',
        'amount',
        'price',
        'read'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
