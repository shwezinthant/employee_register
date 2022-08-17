<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'employee_id',
        'status',
        'file',
        'department_id',
        'accommodation_requests'
    ];
    public function department(){
        return $this->belongsTo(Department::class);
    }
}