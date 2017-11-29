<ul class="nav nav-pills nav-works">
    <li class="{{ $navigation['current']['child'] == 'home' ? 'active' : '' }}">
            <a href="{{ url('panel/constructionwork/info')  }}/{{ $works['one']->tbObraID }}">Inicio</a>
        </li>
    <li role="presentation" class="dropdown {{ $navigation['current']['father'] == 'coordinations' ? 'active' : '' }}">
        <a href="#" id="cordinationDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            Coordinación
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" aria-labelledby="cordinationDropdownMenu">
            <li class="{{ $navigation['current']['child'] == 'daily' ? 'active' : '' }}">
                <!--<a href="{{ url('panel/constructionwork')  }}/{{  $works['one']->tbObraID }}/estimates">Diario de la obra</a>-->
                <a href="{{ url('panel/constructionwork')  }}/{{  $works['one']->tbObraID }}/dailys_work/info">Diario de la obra</a>
            </li>
        </ul>
    </li>
    <li role="presentation" class="dropdown {{ $navigation['current']['father'] == 'datas' ? 'active' : '' }}">
        <a href="#" id="dataDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            Ubicación
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" aria-labelledby="dataDropdownMenu">
            <li class="{{ $navigation['current']['child'] == 'buildings' ? 'active' : '' }}">
                <a href="{{ url('panel/constructionwork')  }}/{{  $works['one']->tbObraID }}/buildings/info">Edificios</a>
            </li>
            <li class="{{ $navigation['current']['child'] == 'levels' ? 'active' : '' }}">
                <a href="{{ url('panel/constructionwork')  }}/{{  $works['one']->tbObraID }}/levels/info">Niveles</a>
            </li>
            <li class="{{ $navigation['current']['child'] == 'locals' ? 'active' : '' }}">
                <a href="{{ url('panel/constructionwork')  }}/{{  $works['one']->tbObraID }}/locals/info">Locales</a>
            </li>
        </ul>
    </li>
    <li role="presentation" class="dropdown {{ $navigation['current']['father'] == 'directory' ? 'active' : '' }}">
        <a href="#" id="directoriesDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            Directorios
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" aria-labelledby="directoriesDropdownMenu">
            <li class="{{ $navigation['current']['child'] == 'business' ? 'active' : '' }}">
                <a href="{{ url('panel/constructionwork')  }}/{{  $works['one']->tbObraID }}/business/info">Empresas</a>
            </li>
            <li class="{{ $navigation['current']['child'] == 'persons' ? 'active' : '' }}">
                <a href="{{ url('panel/constructionwork')  }}/{{  $works['one']->tbObraID }}/persons/info">Personas</a>
            </li>
        </ul>
    </li>
    <li role="presentation" class="dropdown {{ $navigation['current']['father'] == 'finances' ? 'active' : '' }}">
        <a href="#" id="financesDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            Finanzas
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" aria-labelledby="financesDropdownMenu">
            <li class="{{ $navigation['current']['child'] == 'catalogs' ? 'active' : '' }}">
                <a href="{{ url('panel/constructionwork')  }}/{{  $works['one']->tbObraID }}/catalogs/info">Catálogos</a>
            </li>
            <li class="{{ $navigation['current']['child'] == 'generators' ? 'active' : '' }}">
                <a href="{{ url('panel/constructionwork')  }}/{{  $works['one']->tbObraID }}/generators/info">Generadores</a>
            </li>
            <li class="{{ $navigation['current']['child'] == 'estimates' ? 'active' : '' }}">
                <a href="{{ url('panel/constructionwork')  }}/{{  $works['one']->tbObraID }}/estimates/info">Estimaciones</a>
            </li>
            <!--<li class="{{ $navigation['current']['child'] == 'ivoices' ? 'active' : '' }}">
                <a href="#">Facturas</a>
            </li>
            <li class="{{ $navigation['current']['child'] == 'payments' ? 'active' : '' }}">
                <a href="#">Pagos</a>
            </li>-->
        </ul>
    </li>
    <li role="presentation" class="dropdown {{ $navigation['current']['father'] == 'legal' ? 'active' : '' }}">
        <a href="#" id="legalDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            Legal
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" aria-labelledby="legalDropdownMenu">
            <li class="{{ $navigation['current']['child'] == 'contracts' ? 'active' : '' }}">
                <a href="{{ url('panel/constructionwork')  }}/{{  $works['one']->tbObraID }}/contracts/info">Contratos</a>
            </li>
            <li class="{{ $navigation['current']['child'] == 'binnacles' ? 'active' : '' }}">
                <a href="{{ url('panel/constructionwork')  }}/{{  $works['one']->tbObraID }}/binnacles/info">Bitácoras</a>
            </li>
            <li class="{{ $navigation['current']['child'] == 'trades' ? 'active' : '' }}">
                <a href="{{ url('panel/constructionwork')  }}/{{  $works['one']->tbObraID }}/trades/info">Oficios</a>
            </li>
        </ul>
    </li>
</ul>