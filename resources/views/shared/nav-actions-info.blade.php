@if (isset($filter['queries']['status']) && $filter['queries']['status'] == 'deleted')
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
            <a class="disabled">
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
            <a href="#" class="is-tooltip" data-placement="bottom" title="Restaurar" data-toggle="modal" data-target="#modalRestoreRecord">
            <span class="fa-stack fa-lg">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-undo fa-stack-1x fa-inverse"></i>
            </span>
            </a>
        </li>
        <li>
            <a class="disabled">
            <span class="fa-stack fa-lg">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-times fa-stack-1x fa-inverse"></i>
            </span>
            </a>
        </li>
    </ul>
@else
    <ul class="nav nav-actions nav-own navbar-nav navbar-right">
        <li>
            <a href="{{ $navigation['base'].'/save' }}" class="is-tooltip" data-placement="bottom" title="Nuevo">
            <span class="fa-stack fa-lg">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-plus fa-stack-1x fa-inverse"></i>
            </span>
            </a>
        </li>
        <li>
            <a class="disabled">
            <span class="fa-stack fa-lg">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-floppy-o fa-stack-1x fa-inverse"></i>
            </span>
            </a>
        </li>
        @if($model['count'] > 0)
            @if (!empty($model['closed']))
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
            @else
                <li>
                    <a href="{{ $navigation['base'].'/update/'.$model['id'] }}{{ !empty($filter['query']) ? '?'.$filter['query'] : '' }}" class="is-tooltip" data-placement="bottom" title="Editar">
                <span class="fa-stack fa-lg">
                    <i class="fa fa-circle fa-stack-2x"></i>
                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                </span>
                    </a>
                </li>
                <li>
                    <a href="#" id="confirmDeleteButton" class="is-tooltip" data-placement="bottom" title="Eliminar" data-toggle="modal" data-target="#modalDeleteRecord">
                <span class="fa-stack text-danger fa-lg">
                    <i class="fa fa-circle fa-stack-2x"></i>
                    <span class="text-danger"><i class="fa fa-trash fa-stack-1x fa-inverse"></i></span>
                </span>
                    </a>
                </li>
            @endif
        @else
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
        @endif
        <li>
            <a class="disabled">
            <span class="fa-stack fa-lg">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-times fa-stack-1x fa-inverse"></i>
            </span>
            </a>
        </li>
    </ul>
    <ul class="nav nav-actions nav-relation navbar-nav navbar-right hidden">
        <li>
            <a id="navRelationSave" href="#" class="is-tooltip" data-placement="bottom" title="Nuevo" data-toggle="modal" data-target="">
            <span class="fa-stack fa-lg">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-plus fa-stack-1x fa-inverse"></i>
            </span>
            </a>
        </li>
        <li>
            <a class="disabled">
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
            <a class="disabled">
            <span class="fa-stack fa-lg">
                <i class="fa fa-circle fa-stack-2x"></i>
                <i class="fa fa-times fa-stack-1x fa-inverse"></i>
            </span>
            </a>
        </li>
    </ul>
@endif