@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <form action="{{route('tarefa.importacao')}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <button class="btn btn-outline-secondary" type="submit" id="inputGroupFileAddon03">Importar</button>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" name="import">
                                        </div>
                                    </div>
                                        <div>
                                            <input type="radio" name="option" id="cliente" value="cliente" checked>
                                            <label for="">Cliente</label>
                                            <input type="radio" name="option" id="animal" value="animal">
                                            <label for="">Animal</label>
                                        </div>
                                </div>
                                <div class="col-6">
                                    <div class="float-right">
                                        <a class="btn btn-primary" href="{{route('tarefa.exportacaoCliente')}}">Export Cliente</a>
                                        <a class="btn btn-success" href="{{route('tarefa.exportacaoAnimal')}}">Export Animal</a>
                                    </div>                            
                            </form>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('/jquery/jquery-3.6.0.js') }}"></script>
<script type="text/javascript">

</script>

@endsection
