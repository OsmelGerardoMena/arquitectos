@if (count($errors) > 0)
	<div class="alert alert-danger" role="alert" style="position: fixed; bottom: 40px; right: 40px; width: auto; z-index: 9999">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
		</button>
        {{ $errors->first() }}
	</div>
@endif
@if(session()->has('success'))
    <div class="alert alert-success" style="position: fixed; bottom: 40px; right: 40px; width: auto; z-index: 9999">
    	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
		</button>
        {!! session()->get('success') !!}
    </div>
@endif
@if(session()->has('info'))
   <div class="alert alert-info" style="position: fixed; bottom: 40px; right: 40px; width: auto; z-index: 9999">
    	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
		</button>
        {!! session()->get('info') !!}
    </div>
@endif
@if(session()->has('csrf_error'))
    <div class="alert alert-danger" style="position: fixed; bottom: 40px; right: 40px; width: auto; z-index: 9999">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        {!! session()->get('csrf_error') !!}
    </div>
@endif
