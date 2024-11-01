<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\belongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employe extends Model
{
    use HasFactory;

    protected $table = 'employees'; // Specify the table name if it's not plural

    // Specify the fillable fields for mass assignment
    protected $fillable = [
        'Mat',
        'name',
        'latitude',
        'longitude',
        'moto'
    ];


    public function transports()
    {
        return $this->belongsToMany(Transport::class, 'trajects', 'employee_id', 'transport_id');
    }

}
