<header class="mb-4">
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
        <a class="navbar-brand" href="/">Microposts</a>
        
        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#nav-bar">
            
        </button>
            
        <div class="collapse navbar-collapse" id="nav-bar">
            <ul class="navbar-nav mr-auto"></ul>
            <ul class="navbar-nav">
                {{-- ユーザー登録画面へのリンク --}}
                <li>{{!! link_to_route("signup.get", "Signup", [], ["class" => "nav-link"]) !!}</li>
            </ul>
            
                <li class="nav-item"><a href="#" class="nav-link">Signup</a></li>
                
                <li class="nav-item"><a href="#" class="nav-link">Login</a></li>
        </div>    
    </nav>
</header>