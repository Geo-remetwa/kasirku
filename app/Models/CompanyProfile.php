<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    protected $table = 'company_profile';

    protected $fillable = [
        'image', 'name', 'address', 'contact'
    ];

    protected $hidden = [];
}
