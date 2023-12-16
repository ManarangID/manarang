@if ((count($menu->children) > 0) && ($menu->parent_id > 0))
	<li data-id="{{ $menu->id }}" class="dd-item dd3-item">
		<div class="dd-handle dd3-handle"></div>
		<div class="dd3-content">
			<div class="d-flex justify-content-between">
				<div>{{ $menu->title }} &nbsp;&nbsp;&nbsp; {{ $menu->url }}</div>
				<form method="POST" action="{{ route('menumanager.destroy', Hashids::encode($menu->id)) }}"> @csrf
                    <input name="_method" type="hidden" value="DELETE">
					<div class="btn-group btn-group-xs">
						<a href="{{ route('menumanager.edit', Hashids::encode($menu->id)) }}" class="btn btn-primary btn-xs btn-icon" title="{{ __('general.edit') }}" data-toggle="tooltip" data-placement="top"><i class="fa fa-edit"></i></a>
						<button type="submit" class="btn btn-danger btn-xs btn-icon" data-toggle="tooltip" title='Delete'><i class="fas fa-trash"></i></button>
					</div>
				</form>
			</div>
		</div>
@else
	<li data-id="{{ $menu->id }}" class="dd-item dd3-item">
		<div class="dd-handle dd3-handle"></div>
		<div class="dd3-content">
			<div class="d-flex justify-content-between">
				<div>{{ $menu->title }} &nbsp;&nbsp;&nbsp; {{ $menu->url }}</div>
				<form method="POST" action="{{ route('menumanager.destroy', Hashids::encode($menu->id)) }}"> @csrf
                    <input name="_method" type="hidden" value="DELETE">
					<div class="btn-group btn-group-xs">
						<a href="{{ route('menumanager.edit', Hashids::encode($menu->id)) }}" class="btn btn-primary btn-xs btn-icon" title="{{ __('general.edit') }}" data-toggle="tooltip" data-placement="top"><i class="fa fa-edit"></i></a> 
						<button type="submit" class="btn btn-danger btn-xs btn-icon" data-toggle="tooltip" title='Delete'><i class="fas fa-trash"></i></button>
					</div>
				</form>
			</div>
		</div>
@endif
	@if (count($menu->children) > 0)
	<ol style="" class="dd-list">
		@foreach($menu->children as $menu)
			@include('admin.menumanager.menu', $menu)
		@endforeach
	</ol>
	@endif
</li>

