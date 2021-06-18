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

    public function importacaoAnimal($request){
        $caminho = $request->file('import')->path();     
        $stream = fopen($caminho, 'r');
        $csv = Reader::createFromStream($stream);
        $csv->setDelimiter(",");
        $csv->setHeaderOffset(0);

       $stmt = (new Statement());

       $dados = $stmt->process($csv);
       
       foreach($dados as $dado){

            try{
                $nascimento =  $dado['Nascimento'];
            }catch(\Exception $e){
                echo "Experado dados de Animal";
                exit;
            }

            
           
            if($nascimento != null || !empty($nascimento)){  
                if(!$this->validateDate($nascimento, 'Y-m-d')){
                   $nascimento = \DateTime::createFromFormat('d/m/Y', $nascimento);
               }
            }else{
                $nascimento =  null;
            }
           

            $especie_id = Especie::firstOrCreate(
            ['nome' => $dado['Especie'],],
            );

            $raca_id = Raca::create([
                'especie_id' => $especie_id->id,
                'nome' => $dado['Raca'],
            ]);

            $pessoa = Pessoa::where('id',$dado['IdCliente'])->value('id');

            if($pessoa != NULL){

                AnimalNovo::create([
                    'pessoa_id' => $dado['IdCliente'],
                    'especie_id' => $especie_id->id,
                    'raca_id' => $raca_id->id,
                    'nome' => $dado['Nome'],
                    'nascimento' => $nascimento,
                ]);
            }
       }
        echo ("importou Animal");
    }
    
    

  

   
}
