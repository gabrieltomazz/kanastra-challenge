<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bankslip extends Model
{
    protected $fillable = [
        'name', 'government_id', 'email', 'debt_amount', 'debt_due_date', 'debt_id'
    ];
    use HasFactory;
}
