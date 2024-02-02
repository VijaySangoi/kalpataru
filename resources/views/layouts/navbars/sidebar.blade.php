<div class="sidebar">
    <div class="sidebar-wrapper">
        <ul class="nav">
            @foreach($menu as $ky => $val)
            <li @if ($pageSlug == $val->pageslug) class="active" @endif>
                <a href="{{$val->url}}">
                    <i class="tim-icons {{$val->icon}}"></i>
                    <p>{{$val->name}}</p>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
</div>