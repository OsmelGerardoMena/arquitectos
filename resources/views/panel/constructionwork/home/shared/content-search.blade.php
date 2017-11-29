<form action="{{ $navigation['base'].'/search' }}" method="get">
    <div class="input-group input-group-sm">
        @if (isset($search['query']))
            <input id="search" name="q" class="form-control form-control-plain" placeholder="Busqueda" value="{{ $search['query'] }}">
            @if (isset($filter['queries']))
                <input type="hidden" name="status" value="{{ isset($filter['queries']['status']) ? $filter['queries']['status'] : '' }}">
            @endif
            <span class="input-group-btn">
                <button class="btn btn-default" type="submit">
                    <span class="fa fa-search fa-fw"></span>
                </button>
                <button class="btn btn-default" type="button" data-toggle="modal" data-target="#modalFilter">
                    <span class="fa fa-filter fa-fw"></span>
                </button>
                <a href="{{ $navigation['base'].'/home' }}" class="btn btn-link btn-sm">
                    <div class="text-danger"><span class="fa fa-times fa-fw"></span></div>
                </a>
            </span>
        @else
            <input id="search" name="q" type="text" class="form-control form-control-plain" placeholder="Busqueda">
            <span class="input-group-btn">
                <button class="btn btn-default" type="submit">
                    <span class="fa fa-search fa-fw"></span>
                </button>
                <button class="btn btn-default" type="button" data-toggle="modal" data-target="#modalFilter">
                    <span class="fa fa-filter fa-fw"></span>
                </button>
            </span>
        @endif
    </div>
</form>