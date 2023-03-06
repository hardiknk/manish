<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupan extends Model
{
    use HasFactory;
    protected $fillable = ["id","custom_id", "code", "percentage", "is_active"];
    
    public function getRouteKeyName()
    {
        return 'custom_id';
    }
}
