<ul class="nav nav-actions navbar-nav navbar-right">
    <li>
        <a class="disabled">
            <span class="fa-stack fa-lg">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-plus fa-stack-1x fa-inverse"></i>
            </span>
        </a>
    </li>
    <li>
        <a href="#" id="addSubmitButton" class="is-tooltip" data-placement="bottom" title="Guardar">
            <span class="fa-stack fa-lg">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-floppy-o fa-stack-1x fa-inverse"></i>
            </span>
        </a>
    </li>
    <li>
        <a class="disabled">
            <span class="fa-stack fa-lg">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
            </span>
        </a>
    </li>
    <li>
        <a class="disabled">
            <span class="fa-stack fa-lg">
                <i class="fa fa-circle fa-stack-2x"></i>
                <span class="text-danger"><i class="fa fa-trash fa-stack-1x fa-inverse"></i></span>
            </span>
        </a>
    </li>
    <li>
        @if (isset($search['query']))
            <a href="{{ $navigation['base'].'/search/'.$model['id'] }}{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}" class="is-tooltip" data-placement="bottom" title="Cerrar">
                <span class="fa-stack text-danger fa-lg">
                    <i class="fa fa-circle fa-stack-2x"></i>
                    <i class="fa fa-times fa-stack-1x fa-inverse"></i>
                </span>
            </a>
        @else
            <a href="{{ $navigation['base'].'/info/'.$model['id'] }}{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}" class="is-tooltip" data-placement="bottom" title="Cerrar">
            <span class="fa-stack text-danger fa-lg">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-times fa-stack-1x fa-inverse"></i>
            </span>
            </a>
        @endif
    </li>
</ul>