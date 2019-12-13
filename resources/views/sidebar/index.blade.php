<div id="page-nav-drawer" class="drawer-container flex-fill p-3 border-right show no-transition">
    <div id="page-controler" class="flex-shrink-1 d-flex flex-wrap justify-content-between sticky-top" style="top:calc(43px +3rem);">
        <a href="{{ route('get_edit_client_route', 0) }}" id="add-clien-btn" class="btn btn-danger btn-block d-flex justify-content-center align-items-center rounded-pill mb-3" style="height: 42px;">
            <i class="lnr lnr-user-plus h4 mx-2 mb-0"></i><span class="text-truncate">Add New Client</span>
        </a>
        <div class="client-sidenav w-100">
            <a href="{{ route('home', ['index', !is_numeric(Request()->segment(2)) ? 1 : Request()->segment(2)]) }}" class="btn btn-client-nav {{ Request()->segment(1) == 'index' ? 'active' : ''}}">
                <i class="lnr lnr-users2"></i>
                <span class="text-truncate">Active clients</span>
            </a>
            <a href="{{ route('home', ['all', !is_numeric(Request()->segment(2)) ? 1 : Request()->segment(2)]) }}" class="btn btn-client-nav {{ Request()->segment(1) == 'all' ? 'active' : ''}}">
                <i class="lnr lnr-clipboard-user"></i>
                <span class="text-truncate">All clients</span>
            </a>
            <a href="{{ route('home', ['about-to-expire', !is_numeric(Request()->segment(2)) ? 1 : Request()->segment(2)]) }}" class="btn btn-client-nav {{ Request()->segment(1) == 'about-to-expire' ? 'active' : ''}}">
                <i class="lnr lnr-alarm2"></i>
                <span class="text-truncate">Payment due</span>
            </a>
            <a href="{{ route('home', ['ex-client', !is_numeric(Request()->segment(2)) ? 1 : Request()->segment(2)]) }}" class="btn btn-client-nav {{ Request()->segment(1) == 'ex-client' ? 'active' : ''}}">
                <i class="lnr lnr-folder-user"></i>
                <span class="text-truncate">Ex Client</span>
            </a>
            <a href="{{ route('home', ['baner', !is_numeric(Request()->segment(2)) ? 1 : Request()->segment(2)]) }}" class="btn btn-client-nav {{ Request()->segment(1) == 'baner' ? 'active' : ''}}">
                <i class="lnr lnr-prohibited"></i>
                <span class="text-truncate">Banned</span>
            </a>
            <div class="dropdown-divider border-primary mx-n3"></div>
            <a href="{{ route('get_menu_setting_route') }}" class="btn btn-client-nav {{ Request()->segment(1) == 'sidebar' ? 'active' : ''}}">
                <i class="lnr lnr-cog"></i>
                <span class="text-truncate">Setting</span>
            </a>
            
            {{-- <div class="dropdown-divider border-primary mx-n3"></div>
            <small class="text-white-50">Powered by Poste Team <span class="d-inline-block">&copy; 2019</span></small> --}}
        </div>
    </div>
</div>