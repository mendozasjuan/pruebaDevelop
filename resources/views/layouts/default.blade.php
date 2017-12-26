@extends('layouts.blank')

@section('content')
    <form id="formExcel" method="post" action="{{url('import-excel')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <input type="file" name="excel" id="archivo">
        <br><br>
        <input type="submit" value="Enviar" style="padding: 10px 20px;">
    </form>

    <div id="resultado">
        
    </div>

<div class="table-responsive">
    <table class='table table-bordered table-striped display' id="tableDatos">
        <thead>
            <tr>
                <th>Albaran</th>
                <th>Destinatario</th>
                <th>Direccion</th>
                <th>Poblacion</th>
                <th>CP</th>
                <th>Provincia</th>
                <th>Telefono</th>
                <th>Observaciones</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
    </table>
    </div>
@endsection

@push('scripts')
<script type="text/javascript">

 
$(document).ready(function() {

    $('#formExcel').on("submit",function(event) {
        event.preventDefault();
        var data = new FormData($(this)[0]);
        $.ajax({
            data : data,
            url: '{{ url('import-excel')}}',
            method: 'post',
            dataType: 'json',
            processData: false,
            contentType: false,
            beforeSend: function () {
                $("#resultado").html("Procesando, espere por favor...");
            },
            success:function(data){
                console.log(data);
                $("#resultado").html("");
                //let tabla = $("#tableDatos tbody");
                $.each(data,function(i,campo){
                    console.log(campo)
                });
                $('#tableDatos').DataTable( {
                    data: data,
                    columns: [
                        { data: "albaran" },
                        { data: "destinatario" },
                        { data: "direccion" },
                        { data: "poblacion" },
                        { data: "cp" },
                        { data: "provincia" },
                        { data: "telefono" },
                        { data: "observaciones" },
                        { data: "fecha.date" }
                    ]
                } );
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
                if(jqXHR)
                {
                    //clearMessages();
 
                    var errors = jqXHR.responseJSON;
 
                    for(error in errors)
                    {
                       console.log(errors[error]);
                    }
 
                    
                }
            }

        });
    });
    
} );
</script>
@endpush