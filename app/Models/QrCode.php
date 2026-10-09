<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_number',
        'qr_token',
        'item_type',
        'item_id',
        'status',
        'generated_by',
        'scans_count',
        'last_scanned_at',
        'deactivated_at',
    ];

    protected $casts = [
        'last_scanned_at' => 'datetime',
        'deactivated_at' => 'datetime',
    ];

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function item()
    {
        if ($this->item_type === 'found_item') {
            return $this->belongsTo(FoundItem::class, 'item_id');
        }
        return $this->belongsTo(LostItem::class, 'item_id');
    }

    public function getItemRecordAttribute()
    {
        if ($this->item_type === 'found_item') {
            return FoundItem::find($this->item_id);
        }
        return LostItem::find($this->item_id);
    }
}
