<?php

namespace App\Models;

use App\Models\Achat;
use App\Models\Facture;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 'tel', 'adresse'
    ];   
    
    public function facture()
    {
        return $this->hasMany(Facture::class, 'client_id');
    }
    
    public function achat()
    {
        return $this->hasMany(Achat::class, 'client_id');
    }
}