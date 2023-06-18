<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use softDeletes;

    protected $fillable = ['tenant_id','user_id'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }


}
