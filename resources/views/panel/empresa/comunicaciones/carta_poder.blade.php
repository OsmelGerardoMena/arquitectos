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
                <a href="" class="list-group-item">
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
                </div>
              </div>
              <div class="panel panel-default">
                <div class="panel-body">
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