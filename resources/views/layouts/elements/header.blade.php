<header class="main-header">
        <span class="logo">
            <b>Reputera</b>
        </span>
    <nav class="navbar navbar-static-top" role="navigation">
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        {{ Auth::user()->name ?? '' }}
                    </a>
                    <ul class="dropdown-menu" style="width: 138px">
                        <li class="user-header" style="height: 80px">
                            <p>
                                {{ Auth::user()->name ?? ''}}
                                <small>{{ Auth::user()->email ?? ''}}</small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-right">
                                <a href="{{ route('logout') }}" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>