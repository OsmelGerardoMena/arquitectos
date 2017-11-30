<div class="modal fade bd-example-modal-lg" id="modalCartaPoder" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document" style="width: 1250px;">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLabel">Cartas Poder</h2>
      </div>
      
      <div class="modal-body">
        <div class="container-fluid"> 
          <div class="row">
            <div class="col-sm-4 col-md-3">
              <div class="panel panel-default">
                <div class="panel-heading" style="background-color: #fff">
                  Listado de cartas
                </div>
                @if(empty($cartas))
                <div class="align-middle">No hay registros</div>
                @else

               @foreach($cartas as $car)
               <input type="hidden" name="" id="carta_id" value="{{ $car->TbCartaPoderID }}">
                <a href="#!" class="list-group-item" id="cartapoder{{ $car->TbCartaPoderID }}" data-cartaid="{{ $car->TbCartaPoderID }}" onclick="return ejecutate({{ $car->TbCartaPoderID }})">
                  <h4 class="list-group-item-heading"></h4>
                  <p class="text-muted small">
                    {{ $car->CPDirigidaA }}
                  </p>
                </a>
                @endforeach
                @endif
              </div>
            </div>
            <div class="col-sm-8 col-md-9">

              <div class="panel panel-default">
                <div class="panel-body">
                  <div class="col-md-12">
                    <p><strong>Localidad:</strong> <span id="localidad"></span></p>
                    <p><strong>Fecha:</strong> <span id="fecha"></span></p>
                    <p><strong>Dirigida a:</strong> <span  id="dira"></span></p>
                    <p ><strong>Poder para que:</strong><span id="paraq"></span></p>  
                  </div>
                  <div class="col-md-12">
                    <div class="col-md-6" style="padding-left: 0px;" ><p><strong>Desde:</strong><span id="desde"></span></p></div>
                    <div class="col-md-6" id="hasta"><p>Hasta:</p></div>
                  </div>
                  <div class="col-md-12">
                    <div class="col-md-6" style="padding-left: 0px;" id="otorga"><p>Otorga:</p></div>
                    <div class="col-md-6" id="recibe"><p>Recibe:</p></div>
                  </div>
                  <div class="col-md-12">
                    <div class="col-md-6" style="padding-left: 0px;" id="testigo1"><p>Testigo #1:</p></div>
                    <div class="col-md-6" id="testigo2"><p>Testigo #2:</p></div>
                  </div>
                  <div id="infohere"></div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" >Confirmar</button>
      </div>
      
    </div>
  </div>
</div>
@push('scripts_footer')
<script>
  var miId =  $('.modal-content #carta_id').val();
  function ejecutate(id) {
    $('#localidad').empty();
    $('#fecha').empty();
    $('#dira').empty();
    $('#paraq').empty();
    $('#desde').empty();
    $('#hasta').empty();
    $('#otorga').empty();
    $('#recibe').empty();
    $('#testigo1').empty();
    $('#testigo2').empty();

    $.get('/cartasPoder/' + id,function(result){

      
      $('#localidad').append( result[0].LocalidadAlias);
    $('#fecha').append('Fecha: ' + result[0].CPFechaExpedicion);
    $('#dira').append('Dirigida a: ' + result[0].CPDirigidaA);
    $('#paraq').append('Poder para que: ' + result[0].CPAsuntoDescripcion);
    $('#desde').append('Desde: ' + result[0].CPFechaInicioPoder);
    $('#hasta').append('Hasta: ' + result[0].CPFechaTerminoPoder);
    $('#otorga').append('Otorga: ' + result[0].CPDirigidaA);
    $('#recibe').append('Recibe: ' + result[0].CPDirigidaA);
    $('#testigo1').append('Testigo #1: ' + result[0].CPDirigidaA);
    $('#testigo2').append('Testigo #2: ' + result[0].CPDirigidaA);
      
    });
  }
</script>
@endpush