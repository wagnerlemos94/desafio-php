<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimalNovo extends Model
{
    protected $table = 'animais';
    protected $connection = 'connections_2';

    public $timestamps = false;

    protected $fillable = ['id','pessoa_id','especie_id','raca_id','nome','nascimento'];

    public function nomeColunas(){

        return $this->fillable;
    }

   
}
