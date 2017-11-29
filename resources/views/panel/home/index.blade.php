@extends('layouts.base')
@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-6 col-md-5">
				<div class="panel panel-default">
					<div class="panel-heading" style="background-color: #fff">
						Obras activas
					</div>
					@foreach ($works['all'] as $index => $work)
						<a href="{{ url('/panel/constructionwork') }}/info/{{ $work->tbObraID  }}" class="list-group-item">
							<h4 class="list-group-item-heading">{{ $work->ObraAlias }}</h4>
							<p class="text-muted small">
								{{ $work->ObraNombreCompleto }}
							</p>
						</a>
					@endforeach
				</div>
			</div>
			<div class="col-sm-6 col-md-7">
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
@endsection