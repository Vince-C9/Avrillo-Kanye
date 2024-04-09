<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\App as APIApp;

class AccessToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'app_id',
        'secret_token',
        'expiration_date',
        'last_accessed',
        'active'
    ];

    public function app(): BelongsTo {
        return $this->belongsTo(AccessToken::class);
    }

}
