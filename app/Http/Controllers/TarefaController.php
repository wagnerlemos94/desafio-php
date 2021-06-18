<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;
use League\Csv\Writer;
use League\Csv\Reader;
use League\Csv\Statement;
use App\Models\Cliente;
use App\Models\Pessoa;
use App\Models\Contato;
use App\Models\Especie;
use App\Models\Raca;
use App\Models\AnimalNovo;

class TarefaController extends Controller
{
    public function __construct() {
        
    }
 
    public function index()
    {
        $tarefas = [];
        return view('tarefa.index', $tarefas);
    }

   public function exportacaoCliente() {

        $clientes = (Cliente::get());
        $nomeAtributos = $clientes[0]->nomeColunas();
        
        $csv = Writer::createFromString('');
        $csv->insertOne($nomeAtributos);
        foreach($clientes as $key => $cliente){
             $csv->insertOne([
                $cliente->Id,
                $cliente->Nome,
                $cliente->Telefone1,
                $cliente->Telefone2,
                $cliente->Email,
            ]);            
        }
        
        $nome_arquivo = 'lista_de_Cliente.csv';
        $csv->output($nome_arquivo);

    }


   public function exportacaoAnimal() {

        $animais = (Animal::get());
        $nomeAtributos = $animais[0]->nomeColunas();
        
        $csv = Writer::createFromString('');
        $csv->insertOne($nomeAtributos);
        
        foreach($animais as $animal){

            $csv->insertOne([
                $animal->Id,
                $animal->IdCliente,
                $animal->Nome,
                $animal->Raca,
                $animal->Especie,
                $animal->HistoricoClinico,
                $animal->Nascimento,
            ]);            
        }
        $nome_arquivo = 'lista_de_animal.csv';
        $csv->output($nome_arquivo);

    }


   public function importacao(Request $request) {

    
        if($request->option == "cliente" && $request->import){
            $this->importacaoCliente($request);
        }elseif($request->option == "animal" && $request->import){
            $this->importacaoAnimal($request);
        }else{
            echo "Adicione um arquivo";
        }
       
    }  

    
    
    

  

   
}
