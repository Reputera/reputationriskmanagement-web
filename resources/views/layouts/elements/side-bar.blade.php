<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="header">Modules</li>
            @if(Auth::user()->isAdmin())
                <li class="treeview">
                    <a href="#"><span>User Management</span> <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('admin.users.create.get')}}">Create User</a></li>
                        <li><a href="{{route('admin.users.all.get')}}">Users</a></li>
                    </ul>
                </li>
                <li class="treeview">
                    <a href="#"><span>Company Management</span> <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        <li><a href="{{route('admin.company.create.get')}}">Create Company</a></li>
                        <li><a href="{{route('admin.company.edit')}}">Edit Company</a></li>
                    </ul>
                </li>
                <li><a href="{{route('instance.index')}}">Data Query</a></li>
                <li><a href="/sentimentQuery">Sentiment Query</a></li>
            @endif
        </ul>
    </section>
</aside>
