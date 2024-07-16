<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDevelopmentReport extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'transaction_id',
        'development_report_id',
        'status',
        'expires_at',
    ];

    const STATUS_AVAILABLE = 'available';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_EXPIRED = 'expired';
    const STATUS_PENDING = 'pending';
    const STATUS_PRORATED = 'prorated';

    const EXPIRES_IN_DAYS = 366;

    protected $dates = [
        'expires_at',
    ];


    public function developmentReport()
    {
        return $this->belongsTo(DevelopmentReport::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function summaries()
    {
        return $this->morphMany(UserSummary::class, 'target');
    }
}
