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
    
    public function importacaoCliente(Request $request){
       $caminho = $request->file('import')->path();     
       $stream = fopen($caminho, 'r');
       $csv = Reader::createFromStream($stream);
       $csv->setDelimiter(",");
       $csv->setHeaderOffset(0);

       $stmt = (new Statement());

       $dados = $stmt->process($csv);

       $telefones = [
        'Telefone1',
        'Telefone2'
        ];

        foreach($dados as $dado){
            try{

                $dado['Email'] = filter_var($dado['Email'], FILTER_VALIDATE_EMAIL) ? $dado['Email']: null;
            }catch(\Exception $e){
                echo "Experado dados de cliente";
                exit;
            }
           
           Pessoa::firstOrCreate(
            ['id' => $dado['Id']],
            [
                'nome' => $dado['Nome'],

            ]);

            foreach($telefones as $telefone){
                $dado[$telefone] = preg_replace("/[^0-9]/", "", $dado[$telefone]);
                if(strlen($dado[$telefone]) == 11){
                    $contato = $dado[$telefone];
                    $contato = $this->adicionarMascaraTelefone($contato);
                    $tipo = 'celular';
                    $this->salvarContatoTelefone($dado['Id'],$tipo,$contato);
                }elseif(strlen($dado[$telefone]) == 10 && (substr($dado[$telefone],2,1) == 9 || substr($dado[$telefone],2,1) == 8)){        
                    $contato = substr_replace($dado[$telefone], '9', 2, 0);
                    $contato = $this->adicionarMascaraTelefone($contato);
                    $tipo = 'celular';
                    $this->salvarContatoTelefone($dado['Id'],$tipo, $contato);
                }elseif(strlen($dado[$telefone]) == 10 && (substr($dado[$telefone],2,1) == 2 || substr($dado[$telefone],2,1) == 3)){
                    $contato = $dado[$telefone];
                    $tipo = 'fixo';
                    $this->salvarContatoTelefone($dado['Id'],$tipo, $contato);
                }
            }

            if($dado['Email'] != null){
                Contato::create([
                    'pessoa_id' => $dado['Id'],
                    'tipo' => 'email',
                    'contato' => $dado['Email']
                ]);
            }
           
       }

        echo ("importou Cliente");
    }

    function validateDate($date, $format = 'Y-m-d H:i:s'){
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    function salvarContatoTelefone($pessoa_id, $tipo, $contato){
        Contato::create([
            'pessoa_id' => $pessoa_id,
            'tipo' => $tipo,
            'contato' => $contato
        ]);
    }

    function adicionarMascaraTelefone($telefone){
        $telefone = substr_replace($telefone, '(', 0, 0);
        $telefone = substr_replace($telefone, ')', 3, 0);
        $telefone = substr_replace($telefone, '-', 9, 0);
        return $telefone;
    }

}
