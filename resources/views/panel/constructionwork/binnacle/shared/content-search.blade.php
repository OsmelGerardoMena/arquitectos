<form action="{{ $navigation['base'].'/search' }}" method="get">
    <div class="input-group input-group-sm">
        @if (isset($search['query']))
            <input id="search" name="q" class="form-control form-control-plain" placeholder="Busqueda" value="{{ $search['query'] }}">
            @if (isset($filter['queries']))
                <input type="hidden" name="status" value="{{ isset($filter['queries']['status']) ? $filter['queries']['status'] : '' }}">
                <input type="hidden" name="type" value="{{ isset($filter['queries']['type']) ? $filter['queries']['type'] : '' }}">
                <input type="hidden" name="group" value="{{ isset($filter['queries']['group']) ? $filter['queries']['group'] : '' }}">
                <input type="hidden" name="destination" value="{{ isset($filter['queries']['destination']) ? $filter['queries']['destination'] : '' }}">
            @endif
            <span class="input-group-btn">
                <button class="btn btn-default" type="submit">
                    <span class="fa fa-search fa-fw"></span>
                </button>
                <button class="btn btn-default" type="button" data-toggle="modal" data-target="#modalFilter">
                    <span class="fa fa-filter fa-fw"></span>
                </button>
                <a href="{{ $navigation['base'].'/info' }}" class="btn btn-link btn-sm">
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