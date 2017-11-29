@if (isset($navigation['pagination']))
    <a href="{{ $navigation['base'] }}{{ isset($navigation['section']) ? $navigation['section'] : '' }}{{ !empty(http_build_query(Request::except('page'))) ? '?'.http_build_query(Request::except('page')) : '' }}" class="btn btn-default btn-xs">
        <span class="fa fa-caret-left"></span><span class="fa fa-caret-left"></span>
    </a>
    <a href="{{ $navigation['pagination']['prev']  }}" class="btn btn-default btn-xs">
        <span class="fa fa-caret-left fa-fw"></span>
    </a>
    {{ $navigation['pagination']['current']  }} / {{ $navigation['pagination']['last']  }}
    <a href="{{ $navigation['pagination']['next']  }}" class="btn btn-default btn-xs">
        <span class="fa fa-caret-right fa-fw"></span>
    </a>
    <a href="{{ $navigation['base'] }}{{ isset($navigation['section']) ? $navigation['section'] : '' }}?page={{ $navigation['pagination']['last'] }}{{ !empty(http_build_query(Request::except('page'))) ? '&'.http_build_query(Request::except('page')) : '' }}" class="btn btn-default btn-xs">
        <span class="fa fa-caret-right"></span><span class="fa fa-caret-right"></span>
    </a>
@endif