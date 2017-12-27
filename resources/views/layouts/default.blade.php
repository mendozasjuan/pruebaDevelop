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
                    if(i == 14)
                        console.log(campo.errors);
                    let html = '<tr>';
                    html += (campo.errors.albaran[0].length > 0 ? 
                        '<td><div class="errorCampo" data-toggle="tooltip" title="'+campo.errors.albaran[0][0].mensaje+'" data-campo="albaran" data-longitud="10">'+campo.albaran+'</div></td>' : '<td>'+campo.albaran+'</td>' );

                    html += (campo.errors.destinatario[0].length > 0 ? 
                        '<td><div class="errorCampo" data-toggle="tooltip" title="'+campo.errors.destinatario[0][0].mensaje+'" data-campo="destinatario" data-longitud="28">'+campo.destinatario+'</div></td>' : '<td>'+campo.destinatario+'</td>' );

                    html += (campo.errors.direccion[0].length > 0 ? 
                        '<td><div class="errorCampo" data-toggle="tooltip" title="'+campo.errors.direccion[0][0].mensaje+'" data-campo="direccion" data-longitud="250">'+campo.direccion+'</div></td>' : '<td>'+campo.direccion+'</td>' );

                    html += (campo.errors.poblacion[0].length > 0 ? 
                        '<td><div class="errorCampo" data-toggle="tooltip" title="'+campo.errors.poblacion[0][0].mensaje+'" data-campo="poblacion" data-longitud="10">'+campo.poblacion+'</div></td>' : '<td>'+campo.poblacion+'</td>');

                    html += (campo.errors.cp[0].length > 0 ? 
                        '<td><div class="errorCampo" data-toggle="tooltip" title="'+campo.errors.cp[0][0].mensaje+'" data-campo="cp" data-longitud="5">'+campo.cp+'</div></td>' : '<td>'+campo.cp+'</td>' );

                    html += (campo.errors.provincia[0].length > 0 ? 
                        '<td><div class="errorCampo" data-toggle="tooltip" title="'+campo.errors.provincia[0][0].mensaje+'" data-campo="provincia" data-longitud="20">'+campo.provincia+'</div></td>' : '<td>'+campo.provincia+'</td>' );

                    html += (campo.errors.telefono[0].length > 0 ? 
                        '<td><div class="errorCampo" data-toggle="tooltip" title="'+campo.errors.telefono[0][0].mensaje+'" data-campo="telefono" data-longitud="10">'+campo.telefono+'</div></td>' : '<td>'+campo.telefono+'</td>' );

                    html += (campo.errors.observaciones[0].length > 0 ? 
                        '<td><div class="errorCampo" data-toggle="tooltip" title="'+campo.errors.observaciones[0][0].mensaje+'" data-campo="observaciones" data-longitud="500">'+campo.observaciones+'</div></td>' : '<td>'+campo.observaciones+'</td>' );

                    html += (campo.errors.fecha[0].length > 0 ? 
                        '<td><div class="errorCampo" data-toggle="tooltip" title="'+campo.errors.fecha[0][0].mensaje+'" data-campo="fecha" data-longitud="10">'+campo.fecha.date+'</div></td>' : '<td>'+campo.fecha.date+'</td>' );
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
                        let campo = dom[0].dataset.campo;
                        let dato = newValue;
                        let longitud = dom[0].dataset.longitud;
                        let lugar = 'client';
                        url = '{{ route('validar',[
                            'campo' => ':campo',
                            'dato'  => ':dato',
                            'longitud' => ':longitud',
                            'lugar' => ':lugar'
                        ])}}'

                        newUrl = url.replace(':campo',campo);
                        newUrl = newUrl.replace(':dato',dato);
                        newUrl = newUrl.replace(':longitud',longitud);
                        newUrl = newUrl.replace(':lugar',lugar);
                        console.log(newUrl);
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: newUrl,
                            method: 'post',
                            dataType: 'json',
                            success:function(data){
                                if(data.length == 0){
                                    dom[0].classList.remove("errorCampo")
                                    dom[0].removeAttribute("data-toggle")
                                    dom[0].removeAttribute("data-campo")
                                    dom[0].removeAttribute("title")
                                    dom.text(newValue);
                                    console.log(dom);
                                }else{
                                    dom.html(data[0].dato)
                                }
                                console.log(data);
                                
                            }   
                        });
                    }
                });
            }
        });
        
    });
});

</script>
@endpush