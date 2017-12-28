@extends('layouts.blank')

@section('content')
    <form id="formExcel" method="post" action="{{url('import-excel')}}" enctype="multipart/form-data">
        {{csrf_field()}}
        <input class="input" type="file" name="excel" id="archivo">
        <br><br>
        <input class="btn btn-info" type="submit" value="Procesar Fichero" style="padding: 10px 20px;">
    </form>

    <div id="resultado">
        
    </div>

<div id="datos"  style="display: none">

<form id="formExportar" method="post" action="{{url('exportar')}}" >
<input id="btnExportar" class="btn btn-info" type="submit" value="exportar" style="padding: 10px 20px; display:none;">

    <table class='table table-bordered table-striped display' id="tableDatos" width="100%" cellspacing="0">
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

    </form>
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
                if(data.archivoVacio){
                    $("#resultado").html("");
                    alert(data.mensaje);
                    return false;
                }
                $("#resultado").html("");
                let tabla = $("#tableDatos tbody");

                //$('#myTable tbody').append(
                $.each(data,function(i,campo){
                    if(i == 14)
                        console.log(campo.errors);
                    let html = '<tr>';
                    html += (campo.errors.albaran[0].length > 0 ? 
                        '<td><div class="errorCampo editable '+(campo.errors.albaran[0][0].vacio ? 'vacio' : '')+'" data-toggle="tooltip" title="'+campo.errors.albaran[0][0].mensaje+'" data-campo="albaran" data-longitud="10">'+campo.albaran+'</div>'+"<input type='hidden' name='albaran[]' value='"+campo.albaran+"'>"+'</td>' : '<td class="editable" data-campo="albaran" data-longitud="10">'+campo.albaran+'<input type="hidden" name="albaran[]" value="'+campo.albaran+'"></td>' );

                    html += (campo.errors.destinatario[0].length > 0 ? 
                        "<td><div class='errorCampo editable "+(campo.errors.destinatario[0][0].vacio ? 'vacio' : '')+"' data-toggle='tooltip' title='"+campo.errors.destinatario[0][0].mensaje+"' data-campo='destinatario' data-longitud='28'>"+campo.destinatario+"</div><input type='hidden' name='destinatario[]' value='"+campo.destinatario+"'></td>" : "<td class='editable' data-campo='destinatario' data-longitud='28'>"+campo.destinatario+"<input type='hidden' name='destinatario[]' value='"+campo.destinatario+"'></td>" );

                    html += (campo.errors.direccion[0].length > 0 ? 
                        '<td><div class="errorCampo editable '+(campo.errors.direccion[0][0].vacio ? 'vacio' : '')+'" data-toggle="tooltip" title="'+campo.errors.direccion[0][0].mensaje+'" data-campo="direccion" data-longitud="250">'+campo.direccion+'</div>'+"<input type='hidden' name='direccion[]' value='"+campo.direccion+"'>"+'</td>' : '<td class="editable" data-campo="direccion" data-longitud="250">'+campo.direccion+'<input type="hidden" name="direccion[]" value="'+campo.direccion+'"></td>' );

                    html += (campo.errors.poblacion[0].length > 0 ? 
                        '<td><div  class="errorCampo editable '+(campo.errors.poblacion[0][0].vacio ? 'vacio' : '')+'" data-toggle="tooltip" title="'+campo.errors.poblacion[0][0].mensaje+'" data-campo="poblacion" data-longitud="10">'+campo.poblacion+'</div>'+"<input type='hidden' name='poblacion[]' value='"+campo.poblacion+"'>"+'</td>' : '<td class="editable" data-campo="poblacion" data-longitud="10">'+campo.poblacion+'<input type="hidden" name="poblacion[]" value="'+campo.poblacion+'"></td>');

                    html += (campo.errors.cp[0].length > 0 ? 
                        '<td><div class="errorCampo editable '+(campo.errors.cp[0][0].vacio ? 'vacio' : '')+'" data-toggle="tooltip" title="'+campo.errors.cp[0][0].mensaje+'" data-campo="cp" data-longitud="5">'+campo.cp+'</div>'+"<input type='hidden' name='cp[]' value='"+campo.cp+"'>"+'</td>' : '<td class="editable" data-campo="cp" data-longitud="5">'+campo.cp+'<input type="hidden" name="cp[]" value="'+campo.cp+'"></td>' );

                    html += (campo.errors.provincia[0].length > 0 ? 
                        '<td><div class="errorCampo editable '+(campo.errors.provincia[0][0].vacio ? 'vacio' : '')+'" data-toggle="tooltip" title="'+campo.errors.provincia[0][0].mensaje+'" data-campo="provincia" data-longitud="20">'+campo.provincia+'</div>'+"<input type='hidden' name='provincia[]' value='"+campo.provincia+"'>"+'</td>' : '<td class="editable" data-campo="provincia" data-longitud="20">'+campo.provincia+'<input type="hidden" name="provincia[]" value="'+campo.provincia+'"></td>' );

                    html += (campo.errors.telefono[0].length > 0 ? 
                        '<td><div height="5px" class="errorCampo editable '+(campo.errors.telefono[0][0].vacio ? 'vacio' : '')+'" data-toggle="tooltip" title="'+campo.errors.telefono[0][0].mensaje+'" data-campo="telefono" data-longitud="10">'+campo.telefono+'</div>'+"<input type='hidden' name='telefono[]' value='"+campo.telefono+"'>"+'</td>' : '<td class="editable" data-campo="telefono" data-longitud="10">'+campo.telefono+'<input type="hidden" name="telefono[]" value="'+campo.telefono+'"></td>' );

                    html += (campo.errors.observaciones[0].length > 0 ? 
                        '<td><div class="errorCampo editable '+(campo.errors.observaciones[0][0].vacio ? 'vacio' : '')+'" data-toggle="tooltip" title="'+campo.errors.observaciones[0][0].mensaje+'" data-campo="observaciones" data-longitud="500">'+campo.observaciones+'</div>'+"<input type='hidden' name='observaciones[]' value='"+campo.observaciones+"'>"+'</td>' : '<td class="editable" data-campo="observaciones" data-longitud="500">'+campo.observaciones+'<input type="hidden" name="observaciones[]" value="'+campo.observaciones+'"></td>' );

                    html += (campo.errors.fecha[0].length > 0 ? 
                        '<td><div class="errorCampo editable '+(campo.errors.fecha[0][0].vacio ? 'vacio' : '')+'" data-toggle="tooltip" title="'+campo.errors.fecha[0][0].mensaje+'" data-campo="fecha" data-longitud="0">'+campo.fecha+'</div>'+"<input type='hidden' name='fecha[]' value='"+campo.fecha+"'>"+'</td>' : '<td class="editable" data-campo="fecha" data-longitud="0">'+campo.fecha+'<input type="hidden" name="fecha[]" value="'+campo.fecha+'"></td>' );
                    html += '</tr>';

                    tabla.append(html);
                    $("#datos").show();


                });

                $('[data-toggle="tooltip"]').tooltip();

                $('#tableDatos .editable').quickEdit({
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
                        /*url = '{{ route('validar',[
                            'campo' => ':campo',
                            'dato'  => ':dato',
                            'longitud' => ':longitud',
                            'lugar' => ':lugar'
                        ])}}'*/

                        /*newUrl = url.replace(':campo',campo);
                        newUrl = newUrl.replace(':dato',dato);
                        newUrl = newUrl.replace(':longitud',longitud);
                        newUrl = newUrl.replace(':lugar',lugar);
                        console.log(newUrl);*/
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: '{{ url('validar') }}',
                            data: {
                                'campo' : campo,
                                'dato' : dato,
                                'longitud' : longitud,
                                'lugar' : lugar
                            },
                            method: 'post',
                            dataType: 'json',
                            success:function(data){
                                if(data.length == 0){
                                    dom[0].classList.remove("errorCampo");
                                    dom[0].removeAttribute("data-toggle");
                                    //dom[0].removeAttribute("data-campo")
                                    //dom[0].removeAttribute("data-longitud")
                                    dom[0].removeAttribute("data-original-title")
                                    dom[0].removeAttribute("title")
                                    dom.text(newValue);

                                    let campo=dom.data('campo');
                                    dom.parent().children(':input').remove();
                                    dom.parent().append("<input type='hidden' name='"+campo+"[]' value='"+newValue+"'>");
                                   // dom.unbind();
                                    console.log(dom.data('campo'));
                                    if($('.errorCampo').length == 0){
                                        $("#btnExportar").show();
                                    }
                                }else{
                                    dom.html(data[0].dato);
                                    let campo=dom.data('campo');
                                    //dom[0].classList.add("errorCampo")
                                    dom[0].setAttribute("data-toggle", "tooltip");
                                    dom[0].setAttribute("title", data[0].mensaje);
                                    dom.parent().children(':input').remove();
                                    dom.parent().append("<input type='hidden' name='"+campo+"[]' value='"+data[0].dato+"'>");
                                    dom.tooltip();
                                }
                                console.log(data);
                                
                            }   
                        });
                    }
                });
            }
        });
        
    });

    $('#formExportar').on("submit",function(event) {

            event.preventDefault();
            var data = $(this).serialize();
            console.log(data);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data : data,
                url: $(this).attr('action'),
                method: 'post',
                dataType: 'json',
                beforeSend: function () {
                    $("#resultado").html("Procesando, espere por favor...");
                },
                success:function(data){
                    if(data.exito){
                        alert(data.mensaje);
                        $("#resultado").html("");
                    }
                }
            });
    });

    
});

</script>
@endpush