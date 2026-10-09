<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecoveryHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'claim_id',
        'item_name',
        'category_name',
        'owner_id',
        'finder_id',
        'recovery_date',
        'verification_method',
        'remarks',
    ];

    protected $casts = [
        'recovery_date' => 'datetime',
    ];

    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function finder()
    {
        return $this->belongsTo(User::class, 'finder_id');
    }
}
