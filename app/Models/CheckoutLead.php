<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckoutLead extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'address',
        'ip',
        'status',
        'admin_note',
        'contacted_at',
        'contacted_by',
        'cart_data',
        'total_amount'
    ];

    protected $casts = [
        'cart_data' => 'array',
        'contacted_at' => 'datetime',
        'total_amount' => 'decimal:2'
    ];

    /**
     * Get the user who contacted this lead
     */
    public function contactedBy()
    {
        return $this->belongsTo(User::class, 'contacted_by');
    }

    /**
     * Scope to filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter pending leads
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to filter contacted leads
     */
    public function scopeContacted($query)
    {
        return $query->where('status', 'contacted');
    }

    /**
     * Scope to filter converted leads
     */
    public function scopeConverted($query)
    {
        return $query->where('status', 'converted');
    }

    /**
     * Scope to filter abandoned leads
     */
    public function scopeAbandoned($query)
    {
        return $query->where('status', 'abandoned');
    }

    /**
     * Get formatted created date
     */
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('d M Y, h:i A');
    }

    /**
     * Get formatted contacted date
     */
    public function getFormattedContactedAtAttribute()
    {
        return $this->contacted_at ? $this->contacted_at->format('d M Y, h:i A') : 'Not contacted';
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'contacted' => 'info',
            'converted' => 'success',
            'abandoned' => 'danger',
            default => 'secondary'
        };
    }
}
