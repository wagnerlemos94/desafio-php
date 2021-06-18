<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Animal extends Model
{
    protected $table = 'Animal';
    protected $fillable = ['Id','IdCliente','Nome', 'Raca', 'Especie','HistoricoClinico','Nascimento'];

    public function nomeColunas(){

        return $this->fillable;
    }

   
}
