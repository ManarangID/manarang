<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SettingRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        if(Auth::user()->can('read-settings')) {
			$settings = Setting::leftJoin('users', 'users.id', '=', 'settings.created_by')
			->select('settings.*', 'users.id as uid', 'users.name as uname')->get();
			return view('admin.settings.datatable', compact('settings'));
		} else {
			return abort('401');
		}
    }
	
	/**
	 * Displays datatables front end view
	 *
	 * @return \Illuminate\View\View
	 */
    public function getGroups(): View
	{
		if(Auth::user()->can('read-settings')) {
			$groups = Setting::selectRaw("min(id), `groups`")->groupBy('groups')->orderBy('min(id)', 'asc')->get();
			return view('admin.settings.show', compact('groups'));
		} else {
			return abort('401');
		}
	}

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
		if(Auth::user()->can('create-settings')) {
			return view('admin.settings.create');
		} else {
			return abort('401');
		}
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
		if(Auth::user()->can('create-settings')) {
			$this->validate($request,[
				'groups' => 'required',
				'options' => 'required|string|unique:settings',
				'value' => 'required'
			]);

			$request->request->add([
				'created_by' => Auth::User()->id,
				'updated_by' => Auth::User()->id
			]);
			$requestData = $request->all();

			Setting::create($requestData);

			return redirect()->route('settings.group')->with('success', __('setting.store_notif'));
		} else {
			return abort('401');
		}
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
		if(Auth::user()->can('update-settings')) {
			$ids = Hashids::decode($id);
			$setting = Setting::findOrFail($ids[0]);

			return view('admin.settings.edit', compact('setting'));
		} else {
			return abort('401');
		}
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
		if(Auth::user()->can('update-settings')) {
			$ids = Hashids::decode($id);
			$this->validate($request,[
				'groups' => 'required',
				'options' => 'required|string|unique:settings,options,' . $ids[0],
				'value' => 'required'
			]);
			$request->request->add([
				'updated_by' => Auth::User()->id
			]);
			$requestData = $request->all();

			$setting = Setting::findOrFail($ids[0]);
			$setting->update($requestData);

			return redirect()->route('settings.group')->with('success', __('setting.update_notif'));
		} else {
			return abort('401');
		}
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
		if(Auth::user()->can('delete-settings')) {
			$ids = Hashids::decode($id);
			Setting::destroy($ids[0]);

			return redirect()->route('settings.group')->with('success', __('setting.destroy_notif'));
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
    public function deleteAll(Request $request): RedirectResponse
    {
		if(Auth::user()->can('delete-settings')) {
			if ($request->has('id')) {
				$ids = $request->ids;
        		Setting::whereIn('id',explode(",",$ids))->delete();
				return redirect()->back()->with('success', __('setting.destroy_notif'));
			} else {
				return redirect()->route('dsettings.group')->with('error', __('setting.destroy_error_notif'));
			}
		} else {
			return abor('401');
		}
    }
	
	public function sitemap(Request $request)
    {
		if(Auth::user()->can('read-settings')) {
			$path = str_replace('\po-includes', '', str_replace('/po-includes', '', base_path('sitemap.xml')));
			
			SitemapGenerator::create(url('/'))->hasCrawled(function (Url $url) {
				if(getSetting('sitemap_frequency') == 'daily') {
					$frequency = Url::CHANGE_FREQUENCY_DAILY;
				} elseif(getSetting('sitemap_frequency') == 'monthly') {
					$frequency = Url::CHANGE_FREQUENCY_MONTHLY;
				} else {
					$frequency = Url::CHANGE_FREQUENCY_YEARLY;
				}
				
				$url->setPriority(getSetting('sitemap_priority'));
				$url->setChangeFrequency($frequency);
				
				return $url;
			})->writeToFile($path);
			
			return redirect('dashboard/settings')->with('flash_message', __('setting.sitemap_generate'));
		} else {
			return redirect('forbidden');
		}
	}
}
