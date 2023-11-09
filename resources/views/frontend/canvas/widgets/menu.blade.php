
@if((count($menu->children) > 0) && ($menu->parent == 0))
    <li class="menu-item">
        <a class="menu-link" href="{{ url($menu->url) }}"><div><i class="{{ $menu->class }}"></i>{!! $menu->title !!}</div></a>
@else        
    <li class="menu-item">
    @if($menu->target == 'none')
        <a class="menu-link" href="{{ url($menu->url) }}"><div><i class="{{ $menu->class }}"></i>{!! $menu->title !!}</div></a>
    @else    
        <a class="menu-link" target="blank" href="{{ url($menu->url) }}"><div><i class="{{ $menu->class }}"></i>{!! $menu->title !!}</div></a>
    @endif
@endif
    @if (count($menu->children) > 0)   
        <ul class="sub-menu-container">
            @foreach($menu->children as $menu)
                @include(getTheme('widgets.submenu'), $menu)
            @endforeach
        </ul>
    @endif
    </li>
                        