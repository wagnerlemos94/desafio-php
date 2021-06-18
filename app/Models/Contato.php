<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Contato extends Model
{

    protected $table = 'contatos';
    protected $connection = 'connections_2';
    public $timestamps = false;

    protected $fillable = ['id','pessoa_id','tipo','contato'];


    public function nomeColunas(){

        return $this->fillable;
    }

   
}
