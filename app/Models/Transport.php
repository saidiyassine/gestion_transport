<?php

namespace App\Models;
use App\Models\Employe;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transport extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'is_deleted'
    ];

    public function employees()
    {
        return $this->belongsToMany(Employe::class, 'trajects', 'transport_id', 'employee_id')
                    ->wherePivot('is_deleted', 0);
    }
}
