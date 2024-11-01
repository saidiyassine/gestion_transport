<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Traject extends Model
{
    use HasFactory;

    // Nom de la table associée
    protected $table = 'trajects';

    // Les attributs qui sont mass assignable
    protected $fillable = [
        'transport_id',
        'employee_id',
        'is_deleted',
    ];

    // Relation avec le modèle Transport
    public function transport()
    {
        return $this->belongsTo(Transport::class, 'transport_id');
    }

    // Relation avec le modèle Employe
    public function employe()
    {
        return $this->belongsTo(Employe::class, 'employee_id');
    }
}
