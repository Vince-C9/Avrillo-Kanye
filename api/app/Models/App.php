<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class App extends Model
{
    use HasFactory;

    protected $fillable = [
        'app_name',
        'app_access_id'
    ];
    public function accessToken(): HasMany { 
        return $this->hasMany(AccessToken::class);
    }
}
