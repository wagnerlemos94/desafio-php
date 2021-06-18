<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raca extends Model
{
    protected $table = 'racas';
    protected $connection = 'connections_2';

    public $timestamps = false;

    protected $fillable = ['id','especie_id','nome'];

    public function nomeColunas(){

        return $this->fillable;
    }

   
}
