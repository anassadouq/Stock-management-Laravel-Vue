<?php

namespace App\Models;

use App\Models\Achat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 'tel', 'adresse'
    ];    
    
    public function achats()
    {
        return $this->hasMany(Achat::class, 'client_id');
    }
}