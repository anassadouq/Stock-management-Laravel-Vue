<?php

namespace App\Models;

use App\Models\Achat;
use App\Models\Fournisseur;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'fournisseur_id', 'code', 'designation', 'qte', 'pu'
    ];

    public function fournisseur() 
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function achat() 
    {
        return $this->hasMany(Achat::class);
    }
}