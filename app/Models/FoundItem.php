<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoundItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'item_name',
        'color',
        'description',
        'date_found',
        'location_found',
        'storage_location',
        'image',
        'status',
        'approval_status',
        'rejection_reason',
        'is_frozen',
        'freeze_reason',
        'frozen_at',
        'frozen_by',
        'matched_item_id',
    ];

    protected $casts = [
        'date_found' => 'date',
        'is_frozen' => 'boolean',
        'frozen_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function claims()
    {
        return $this->hasMany(Claim::class, 'item_id')->where('item_type', 'found_item');
    }

    public function frozenBy()
    {
        return $this->belongsTo(User::class, 'frozen_by');
    }

    public function matchedLostItem()
    {
        return $this->belongsTo(LostItem::class, 'matched_item_id');
    }

    public function qrCode()
    {
        return $this->hasOne(QrCode::class, 'item_id')->where('item_type', 'found_item')->latestOfMany();
    }
}
