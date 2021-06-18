<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especie extends Model
{
    protected $table = 'especies';
    protected $connection = 'connections_2';

    public $timestamps = false;

    protected $fillable = ['id','nome'];

    public function nomeColunas(){

        return $this->fillable;
    }

   
}
