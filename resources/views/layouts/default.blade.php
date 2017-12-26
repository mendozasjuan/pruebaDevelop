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
                let tabla = $("#tableDatos tbody");

                //$('#myTable tbody').append(
                $.each(data,function(i,campo){
                    let html = '<tr>';
                    html += (campo.errors.albaran.length > 0 ? 
                        '<td><div class="errorCampo">'+campo.albaran+'</div></td>' : '<td>'+campo.albaran+'</td>' );
                    html += (campo.errors.destinatario.length > 0 ? 
                        '<td><div class="errorCampo">'+campo.destinatario+'</div></td>' : '<td>'+campo.destinatario+'</td>' );
                    html += (campo.errors.direccion.length > 0 ? 
                        '<td><div class="errorCampo">'+campo.direccion+'</div></td>' : '<td>'+campo.direccion+'</td>' );
                    html += (campo.errors.poblacion.length > 0 ? 
                        '<td><div class="errorCampo">'+campo.poblacion+'</div></td>' : '<td>'+campo.poblacion+'</td>');
                    html += (campo.errors.cp.length > 0 ? 
                        '<td><div class="errorCampo">'+campo.cp+'</div></td>' : '<td>'+campo.cp+'</td>' );
                    html += (campo.errors.provincia.length > 0 ? 
                        '<td><div class="errorCampo">'+campo.provincia+'</div></td>' : '<td>'+campo.provincia+'</td>' );
                    html += (campo.errors.telefono.length > 0 ? 
                        '<td><div class="errorCampo">'+campo.telefono+'</div></td>' : '<td>'+campo.telefono+'</td>' );
                    html += (campo.errors.observaciones.length > 0 ? 
                        '<td><div class="errorCampo">'+campo.observaciones+'</div></td>' : '<td>'+campo.observaciones+'</td>' );
                    html += (campo.errors.fecha.length > 0 ? 
                        '<td><div class="errorCampo">'+campo.fecha.date+'</div></td>' : '<td>'+campo.fecha.date+'</td>' );
                    html += '</tr>';

                    tabla.append(html);

                });


                $('#tableDatos .errorCampo').quickEdit({
                    blur: false,
                    checkold: true,
                    space: false,
                    maxLength: 50,
                    showbtn: false,
                    submit: function (dom, newValue) {
                        alert('Modificado');
                        dom.text(newValue);
                    }
                });
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


    

        
    });

</script>
@endpush