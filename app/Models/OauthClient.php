<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OauthClient extends Model 
{
    protected $fillable = [
        'name', 'redirect',
    ];
    
    protected $hidden = [
        'password', 'personal_access_client', 'password_client', 'revoked', 'secret'
    ];
}
