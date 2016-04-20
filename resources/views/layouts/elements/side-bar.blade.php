<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="header">Modules</li>
            @if(Auth::user()->isAdmin())
                <li class="treeview">
                    <a href="#"><span>User Management</span> <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('adminUser.get')}}">Create User</a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#"><span>Company Management</span> <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('companyCreate.get')}}">Create Company</a></li>
                    </ul>
                </li>
                <li><a href="{{route('instance.index')}}">Data Query</a></li>
                <li><a href="/sentimentQuery">Sentiment Query</a></li>
            @endif
        </ul>
    </section>
</aside>
