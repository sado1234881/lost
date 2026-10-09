<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_type',
        'item_id',
        'proof_of_ownership',
        'claim_date',
        'claim_status', // pending, approved, rejected, released, closed
        'qr_code_token',
        'admin_notes',
    ];

    protected $casts = [
        'claim_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        if ($this->item_type === 'found_item') {
            return $this->belongsTo(FoundItem::class, 'item_id');
        }
        return $this->belongsTo(LostItem::class, 'item_id');
    }

    public function recoveryHistory()
    {
        return $this->hasOne(RecoveryHistory::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}
