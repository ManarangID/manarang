<?php

namespace App\Http\Controllers;

use App\Models\Component;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Vinkla\Hashids\Facades\Hashids;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class ComponentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index(Request $request)
    {
		if(Auth::user()->can('read-components')) {
			$components = Component::all();
			return view('admin.components.datatable', compact('components'));
		} else {
			return abort('401');
		}
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
		if(Auth::user()->can('create-components')) {
			return view('admin.components.create');
		} else {
			return abort('401');
		}
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
		if(Auth::user()->can('create-components')) {
			$this->validate($request,[
				'title' => 'required',
				'author' => 'required',
				'folder' => 'required',
				'type' => 'required'
			]);

			$request->request->add([
				'created_by' => Auth::User()->id,
				'updated_by' => Auth::User()->id
			]);
			$requestData = $request->all();

			Component::create($requestData);
			
			return redirect()->route('components.index')->with('success', __('component.store_notif'));
		} else {
			return abort('401');
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
		if(Auth::user()->can('read-components')) {
			$ids = Hashids::decode($id);
			$component = Component::findOrFail($ids[0]);

			return view('components.components.show', compact('component'));
		} else {
			return abort('401');
		}
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
		if(Auth::user()->can('update-components')) {
			$ids = Hashids::decode($id);
			$component = Component::findOrFail($ids[0]);

			return view('components.components.edit', compact('component'));
		} else {
			return abort('401');
		}
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
		if(Auth::user()->can('update-components')) {
			$ids = Hashids::decode($id);
			$this->validate($request,[
				'title' => 'required',
				'author' => 'required',
				'folder' => 'required',
				'type' => 'required',
				'active' => 'required'
			]);
			$request->request->add([
				'updated_by' => Auth::User()->id
			]);
			$requestData = $request->all();

			$component = Component::findOrFail($ids[0]);
			$component->update($requestData);

			return redirect()->route('components.index')->with('success', __('component.update_notif'));
		} else {
			return abort('401');
		}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
		if(Auth::user()->can('delete-components')) {
			$ids = Hashids::decode($id);
			Component::destroy($ids[0]);

			return redirect()->route('components.index')->with('success', __('component.destroy_notif'));
		} else {
			return abort('401');
		}
    }
	
	/**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return void
     */
    public function deleteAll(Request $request)
    {
		if(Auth::user()->can('delete-components')) {
			if ($request->has('id')) {
				$ids = $request->ids;
        		Component::whereIn('id',explode(",",$ids))->delete();
				return redirect()->back()->with('success', __('component.destroy_notif'));
			} else {
				return redirect()->route('components.index')->with('error', __('component.destroy_error_notif'));
			}
		} else {
			return abort('401');
		}
    }
	
	public function install(Request $request)
    {
		if(Auth::user()->can('read-components')) {
			return view('components.component.install');
		} else {
			return  abort('401');
		}
    }
	
	public function processInstall(Request $request)
    {
		if(Auth::user()->can('create-components')) {
			$this->validate($request, [
				'files' => 'required|mimetypes:application/zip,application/octet-stream'
			]);
			
			if ($request->file('files')->isValid()) {
				$filename = rand(111,999).date('YmdHis');
				$extention = $request->file('files')->getClientOriginalExtension();
				$filenamewithext = $filename.'.'.$extention;
				
				if(!File::isDirectory(str_replace('\po-includes', '', str_replace('/po-includes', '', base_path('po-content/installer/components/'.$filename))))){
					File::makeDirectory(str_replace('\po-includes', '', str_replace('/po-includes', '', base_path('po-content/installer/components/'.$filename))), 0777, true, true);
					$upload = $request->file('files')->move('po-content/installer/components/'.$filename, $filenamewithext);
					
					if($upload) {
						$zip = new \ZipArchive;
						$res = $zip->open('po-content/installer/components/'.$filename.'/'.$filenamewithext);
						
						if($res===TRUE) {
							$zip->extractTo('po-content/installer/components/'.$filename);
							$zip->close();
							
							$info = json_decode(file_get_contents('po-content/installer/components/'.$filename.'/info.json'), true);
							if($info) {
								$checkcomponent = Component::where('folder', '=', $info['folder'])->count();
								if ($checkcomponent > 0) {
									return back()->with('error', __('component.install_error_notif'));
								} else {
									Component::create([
										'title' => $info['title'],
										'author' => $info['author'],
										'folder' => $info['folder'],
										'type' => $info['type'],
										'active' => 'Y',
										'created_by' => Auth::User()->id,
										'updated_by' => Auth::User()->id
									]);
									
									$kebabname = Str::kebab($info['title']);
									$camelname = ucfirst(Str::camel($info['title']));
									
									$this->importMigrations($filename);
									$this->importRoutes($kebabname, $camelname);
									$this->importModels($filename);
									$this->importControllers($filename);
									$this->importViews($filename);
									
									File::deleteDirectory(str_replace('\po-includes', '', str_replace('/po-includes', '', base_path('po-content/installer/components/'.$filename))));
									
									return redirect('dashboard/'.$kebabname.'/install');
								}
							} else {
								return back()->with('error', __('component.install_error_notif'));
							}
						} else {
							return back()->with('error', __('component.install_error_notif'));
						}
					} else {
						return back()->with('error', __('component.install_error_notif'));
					}
				}
			} else {
				return back()->with('error', __('component.install_error_notif'));
			}
		} else {
			return abort('401');
		}
    }
	
	protected function importMigrations($filename)
    {
		$directory = str_replace('\po-includes', '', str_replace('/po-includes', '', base_path('po-content/installer/components/'.$filename.'/Migration')));
		$files = File::allFiles($directory);
		foreach($files as $file){
			$pathinfo = pathinfo($file);
			$oldpath = $pathinfo['dirname'].'/'.$pathinfo['basename'];
			$newpath = base_path('database/migrations/'.$pathinfo['basename']);
			if(!File::isFile($newpath)){
				File::move($oldpath, $newpath);
			}
		}
		
		Artisan::call('migrate', array('--path' => 'app/migrations', '--force' => true));
	}
	
	protected function importRoutes($kebabname, $camelname)
    {
		$routefile = base_path('routes/web.php');
		$oldcontent = file_get_contents($routefile);
		
		$search = '//-----replace-----//';
		$replace = "Route::get('dashboard/{$kebabname}/index','Backend\/{$camelname}Controller@index');\n";
		$replace .= "\tRoute::get('dashboard/{$kebabname}/table','Backend\/{$camelname}Controller@getIndex');\n";
		$replace .= "\tRoute::get('dashboard/{$kebabname}/data','Backend\/{$camelname}Controller@anyData');\n";
		$replace .= "\tRoute::get('dashboard/{$kebabname}/install','Backend\/{$camelname}Controller@install');\n";
		$replace .= "\tRoute::post('dashboard/{$kebabname}/deleteall', 'Backend\/{$camelname}Controller@deleteAll');\n";
		$replace .= "\tRoute::resource('dashboard/{$kebabname}', 'Backend\/{$camelname}Controller');\n\t\n";
		$replace .= "\t//-----replace-----//\n\t";
		
		$newcontent = str_replace($search, $replace, $oldcontent);
		file_put_contents($routefile, $newcontent);
    }
	
	protected function importModels($filename)
    {
		$directory = str_replace('\po-includes', '', str_replace('/po-includes', '', base_path('po-content/installer/components/'.$filename.'/Model')));
		$files = File::allFiles($directory);
		foreach($files as $file){
			$pathinfo = pathinfo($file);
			$oldpath = $pathinfo['dirname'].'/'.$pathinfo['basename'];
			$newpath = base_path('app/'.$pathinfo['basename']);
			if(!File::isFile($newpath)){
				File::move($oldpath, $newpath);
			}
		}
	}
	
	protected function importControllers($filename)
    {
		$directory = str_replace('\po-includes', '', str_replace('/po-includes', '', base_path('po-content/installer/components/'.$filename.'/Controller')));
		$files = File::allFiles($directory);
		foreach($files as $file){
			$pathinfo = pathinfo($file);
			$oldpath = $pathinfo['dirname'].'/'.$pathinfo['basename'];
			$newpath = base_path('app/Http/Controllers/Backend/'.$pathinfo['basename']);
			if(!File::isFile($newpath)){
				File::move($oldpath, $newpath);
			}
		}
	}
	
	protected function importViews($filename)
    {
		$directory = str_replace('\po-includes', '', str_replace('/po-includes', '', base_path('po-content/installer/components/'.$filename.'/View')));
		$files = File::directories($directory);
		foreach($files as $file){
			$pathinfo = pathinfo($file);
			$oldpath = $pathinfo['dirname'].'/'.$pathinfo['basename'];
			$newpath = base_path('resources/views/backend/'.$pathinfo['basename']);
			if(!File::isDirectory($newpath)){
				File::moveDirectory($oldpath, $newpath);
			}
		}
	}
}
