@extends("layouts.app")

@section("content")
    <div class="row">
        <aside class="col-sm-4">
           {{-- ユーザー情報 --}}
           @include("users.card")
        </aside>
        <div class="col-sm-8">
                {{--タブ --}}
                @include("users.navtabs")
                @include("users.favorite")
        </div>
    </div>
@endsection    