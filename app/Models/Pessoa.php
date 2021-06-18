<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{

    protected $table = 'pessoas';
    protected $connection = 'connections_2';

    public $timestamps = false;

    protected $fillable = ['id','nome'];


    public function nomeColunas(){

        return $this->fillable;
    }

   
}
