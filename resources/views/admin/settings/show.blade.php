<x-admin-layout>
@section('title', __('setting.datatable_title'))

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ __('general.settings') }}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/">{{ __('general.dashboard') }}</a></li>
              <li class="breadcrumb-item"><a href="{{ route('users.index') }}">{{ __('general.settings') }}</a></li>
              <li class="breadcrumb-item active">{{ __('setting.datatable_list') }}</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary card-outline">
			<div class="card-header">
                <h3 class="card-title">{{ __('setting.datatable_list') }}</h3>
                  <div class="card-tools">
                    <button onclick="location.href='{{ route('settings.create') }}'" type="button" class="btn btn-app"> <i class="fas fa-plus"></i> {{ __('general.add') }}</button>
                  	<button onclick="location.href='{{ route('settings.group') }}'" type="button" class="btn btn-app"> <i class="fas fa-table"></i> {{ __('setting.table') }}</button>
					<button onclick="location.href='{{ route('backups.index') }}'" type="button" class="btn btn-app"> <i class="fas fa-database"></i> {{ __('setting.backup') }}</button>
                  </div>
              </div>
              <div class="card-body">
		        <div class="row">
		          <div class="col-12 col-sm-12">
		            <div class="card card-primary card-outline card-outline-tabs">
		              <div class="card-header p-0 pt-1">
		                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
						@foreach($groups as $group)
		                  <li class="nav-item">
		                    <a class="nav-link {{ $group->groups == 'General' ? 'active' : '' }}" id="custom-tabs-one-{{ strtolower($group->groups) }}-tab" data-toggle="pill" href="#custom-tabs-one-{{ strtolower($group->groups) }}" role="tab" aria-controls="custom-tabs-one-{{ strtolower($group->groups) }}" aria-selected="true">{{ $group->groups }}</a>
		                  </li>
		                @endforeach
		                </ul>
		              </div>
		              <div class="card-body">
		                <div class="tab-content" id="custom-tabs-one-tabContent">
						  @foreach($groups as $group)
		                  <div class="tab-pane fade {{ $group->groups == 'General' ? 'show active' : '' }}" id="custom-tabs-one-{{ strtolower($group->groups) }}" role="tabpanel" aria-labelledby="custom-tabs-one-{{ strtolower($group->groups) }}-tab">
						  	<div class="table-responsive">
								<table class="table table-bordered table-striped">
									<tbody>
										@foreach(getSettingGroup($group->groups) as $option)
											@if($option->options == 'sitemap')
												<tr>
													<th>{{ $option->options }}</th>
													<td>{{ url('/'.$option->value) }}</td>
													<td class="text-center"><a href="{{ route('settings.sitemap') }}" class="btn btn-sm btn-info" title="{{ __('setting.generate') }}" data-toggle="tooltip" data-placement="left"><i class="fa fa-edit"></i> {{ __('setting.generate') }}</a></td>
												</tr>
											@elseif($option->options == 'backup')
												<tr>
													<th>{{ $option->options }}</th>
													<td>public/storage</td>
													<td class="text-center"><a href="{{ route('backups.create') }}" class="btn btn-sm btn-info" title="{{ __('setting.generate') }}" data-toggle="tooltip" data-placement="left"><i class="fa fa-edit"></i> {{ __('setting.backup') }}</a></td>
												</tr>
											@else
												<tr>
													<th>{{ $option->options }}</th>
													<td>{{ $option->value }}</td>
													<td class="text-center"><a href="{{ route('settings.edit', Hashids::encode($option->id)) }}" class="btn btn-sm btn-info" title="{{ __('general.edit') }}" data-toggle="tooltip" data-placement="left"><i class="fa fa-edit"></i> {{ __('general.edit') }}</a></td>
												</tr>
											@endif
										@endforeach
									</tbody>
								</table>
							</div>
		                  </div>
						  @endforeach
		                </div>
		              </div>
		              <!-- /.card -->
		            </div>
		          </div>
		        </div>
              </div>
              <!-- /.card -->
            </div>
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
	
</x-admin-layout>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>