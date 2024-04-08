<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccessToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'app_id',
        'secret_token',
        'active'
    ];

    public function app(): BelongsTo {
        return $this->belongsTo(AccessToken::class);
    }

}
