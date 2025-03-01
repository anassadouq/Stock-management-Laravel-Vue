<?php

namespace App\Models;

use App\Models\Achat;
use App\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Facture extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'num',
        'date'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function achat()
    {
        return $this->hasMany(Achat::class, 'facture_id');
    }
}