<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="header">Modules</li>
            @if(Auth::user()->isAdmin())
                <li><a href="{{route('adminUser.get')}}">Create User</a></li>
            @endif
            <li><a href="{{route('instance.index')}}">Data Query</a></li>
            <li><a href="/instanceQuery">Data Query</a></li>
            <li><a href="/sentimentQuery">Sentiment Query</a></li>
        </ul>
    </section>
</aside>
