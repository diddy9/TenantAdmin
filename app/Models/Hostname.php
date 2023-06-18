<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hostname extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'fqdn', 'created_by', 
    ];

    //public function tenantProperties(): HasOne
    //{
      //  return $this->hasOne(App\Models\TenantProperties);
    //}

    public function tenantProperties(){
        return $this->hasOne(TenantProperties::class);
    }



}
