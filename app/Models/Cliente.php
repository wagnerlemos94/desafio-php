<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{

    protected $table = 'Cliente';

    protected $fillable = ['Id','Nome', 'Telefone1', 'Telefone2','Email'];


    public function nomeColunas(){

        return $this->fillable;
    }

   
}
