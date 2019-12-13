
<div id="client-page-header" class="row flex-nowrap py-3 border-bottom bg-grey sticky-top">
    <h5 id="drawer-ctrl" class="d-flex align-items-center m-0 px-3">
        <button id="drawer-btn" class="btn btn-circle active" data-toggle="button"><i class="lnr lnr-menu"></i></button>
        <a href="{{ route('home', ['index', Session::has('country') ? session('country') : 1]) }}" class="text-truncate" style="text-decoration:none; color:black;">
            <span class="text-truncate">Poste's Clients</span>
        </a>
    </h5>
    <div class="col col-md-6 ml-auto ml-md-0">
        <div class="input-group shadow-none hover focus rounded-lg">
            <div class="input-group-prepend">
                <button class="btn btn-light bg-white border-0" type="button" id="button-addon1"><i class="lnr lnr-magnifier h5 m-0"></i></button>
            </div>
            <input type="text" id="client-search" class="form-control form-control-lg border-0 no-focus rounded-lg" placeholder="Search client by name or by contact person" aria-describedby="button-addon1">
            
            <div class="w-100 rounded-lg bg-light">
                <div class="w-100" id="search-result"></div>
            </div>
        </div>
    </div>
    
    
    <div class="col-auto ml-auto d-flex align-items-center justify-content-center">
        <div class="dropdown">
            
            @if (!empty($area_name))
            
            <button class="btn btn-outline-primary rounded-pill mr-3 dropdown-toggle" type="button" id="selectRegion" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{ explode('.', $area_name)[0] }}
            </button>
            
            @endif
            
            <div class="dropdown-menu dropdown-menu-right dropleft" aria-labelledby="selectRegion">
                @if (isset($parent_area))
                    
                @foreach ($parent_area as $parent)
                <div class="dropdown-item position-relative sub-menu-btn">
                    <a href="{{ route('home', [Request()->segment(1), $parent->id]) }}" class="nav-link d-flex flex-nowrap align-items-center justify-content-center">
                        {{ $parent->area_name }}
                    </a>
                    
                    @if (count($parent->getCities) > 0)
                    <div class="dropdown-menu dropdown-submenu" id="sub-drop-{{ $parent->id }}">
                        @foreach ($parent->getCities as $child)
                        <a href="{{ route('home', [Request()->segment(1), $child->id]) }}" class="dropdown-item d-flex flex-nowrap align-items-center">
                            {{ $child->area_name }}
                        </a>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endforeach

                @endif
            </div>
        </div>
        
        <div class="dropdown">
            
            <a href="#" class="btn btn-circle border-0 bg-secondary text-white dropdown-toggle" id="user-option" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-user"></i>
            </a>
            
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="user-option">
                
                <div class="dropdown-item">
                    <a href="https://poste-vn.com/account" class="btn">
                        @if (Auth::check())
                        {{ Auth::user()->full_name  }}
                        @endif
                    </a>
                </div>
                
                <div class="dropdown-item">
                    <form action="{{ route('post_logout_route') }}" method="post">
                        @csrf
                        <button type="submit" class="btn"><i class="fas fa-sign-out-alt"></i>&nbsp;Logout</button>
                    </form>
                </div>
                
            </div>
            
        </div>
    </div>
</div>