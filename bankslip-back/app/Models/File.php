<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'name','status'  
    ];

    const STATUS_PROCESSING = 'PROCESSING';
    const STATUS_PROCESSED = 'PROCESSED';
    const STATUS_ERROR = 'ERROR';

    use HasFactory;

    public function bankslips()
    {
        return $this->hasMany(Bankslip::class);
    }
}
