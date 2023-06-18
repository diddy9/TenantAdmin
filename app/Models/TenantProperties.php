<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenantProperties extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'hostname_id',
        'name', 
        'logo', 
        'bgimage', 
        'subscribed_on',
        'package',
        'days',
        'status',
    ];

    public function hostname(){
        return $this->belongsTo(Hostname::class, 'hostname_id');
    }

   
}
