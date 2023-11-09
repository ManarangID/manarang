<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;

class ContactController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		config([
			'captcha.secret' => getSetting('recaptcha_secret'),
			'captcha.sitekey' => getSetting('recaptcha_key'),
		]);
		
        // $this->middleware('auth');
    }

    /**
     * Show the application contact.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show()
    {
		$twitterid = explode('/', getSetting('twitter'));
		SEOTools::setTitle('Contact - '.getSetting('web_name'));
		SEOTools::setDescription('Contact - '.getSetting('web_description'));
		SEOTools::metatags()->setKeywords(explode(',', getSetting('web_keyword')));
		SEOTools::setCanonical(getSetting('web_url') . '/contact');
		SEOTools::opengraph()->setTitle('Contact - '.getSetting('web_name'));
		SEOTools::opengraph()->setDescription('Contact - '.getSetting('web_description'));
		SEOTools::opengraph()->setUrl(getSetting('web_url') . '/contact');
		SEOTools::opengraph()->setSiteName(getSetting('web_author'));
		SEOTools::opengraph()->addImage(Storage::url('images/'.getSetting('logo')));
		SEOTools::twitter()->setSite('@'.$twitterid[count($twitterid)-1]);
		SEOTools::twitter()->setTitle('Contact - '.getSetting('web_name'));
		SEOTools::twitter()->setDescription('Contact - '.getSetting('web_description'));
		SEOTools::twitter()->setUrl(getSetting('web_url') . '/contact');
		SEOTools::twitter()->setImage(Storage::url('images/'.getSetting('logo')));
		SEOTools::jsonLd()->setTitle('Contact - '.getSetting('web_name'));
		SEOTools::jsonLd()->setDescription('Contact - '.getSetting('web_description'));
		SEOTools::jsonLd()->setType('WebPage');
		SEOTools::jsonLd()->setUrl(getSetting('web_url') . '/contact');
		SEOTools::jsonLd()->setImage(Storage::url('images/'.getSetting('logo')));
		
        return view(getTheme('contact'));
    }
	
	public function send(Request $request)
    {
		$this->validate($request,[
			'name' => 'required',
			'email' => 'required|string|max:255|email',
			'subject' => 'required',
			'message' => 'required',
			'g-recaptcha-response' => 'required|captcha'
		]);

		$request->request->add([
			'created_by' => 1,
			'updated_by' => 1
		]);
		$requestData = $request->all();

		Contact::create($requestData);
		
		return redirect('contact')->with('flash_message', __('contact.store_notif'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index(Request $request)
    {
		if(Auth::user()->can('read-contacts')) {
			return view('components.contact.datatable');
		} else {
			return redirect('forbidden');
		}
    }
	
	/**
	 * Displays datatables front end view
	 *
	 * @return \Illuminate\View\View
	 */
    public function getIndex()
	{
		if(Auth::user()->can('read-contacts')) {
			return view('components.contact.datatable');
		} else {
			return redirect('forbidden');
		}
	}
	
	/**
	 * Process datatables ajax request.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function anyData()
	{
		$contacts = Contact::leftJoin('users', 'users.id', '=', 'contacts.created_by')
			->select('contacts.*', 'users.id as uid', 'users.name as uname');
		return Datatables::of($contacts)
			->addColumn('check', function ($contact) {
				$check = '<div style="text-align:center;">
					<input type="checkbox" id="titleCheckdel" />
					<input type="hidden" class="deldata" name="id[]" value="'.Hashids::encode($contact->id).'" disabled />
				</div>';
				return $check;
			})
			->addColumn('name', function ($contact) {
				return $contact->name.'<br />'.$contact->email;
			})
			->addColumn('status', function ($contact) {
				return $contact->status == 'Y' ? __('contact.read') : __('contact.unread');
			})
			->addColumn('created_at', function ($contact) {
				return date('d M y H:i', strtotime($contact->created_at));
			})
            ->addColumn('action', function ($contact) {
				$btn = '<div style="text-align:center;"><div class="btn-group">';
				$btn .= '<a href="'.url('dashboard/contacts/'.Hashids::encode($contact->id).'').'" class="btn btn-secondary btn-xs btn-icon" title="'.__('general.view').'" data-toggle="tooltip" data-placement="left"><i class="fa fa-eye"></i></a>';
				$btn .= '<a href="'.url('dashboard/contacts/reply/'.Hashids::encode($contact->id)).'" class="btn btn-success btn-xs btn-icon" title="'.__('comment.reply').'" data-toggle="tooltip" data-placement="left"><i class="fa fa-reply"></i></a>';
				$btn .= '<a href="'.url('dashboard/contacts/'.Hashids::encode($contact->id).'/edit').'" class="btn btn-primary btn-xs btn-icon" title="'.__('general.edit').'" data-toggle="tooltip" data-placement="left"><i class="fa fa-edit"></i></a>';
				$btn .= '<a href="'.url('dashboard/contacts/'.Hashids::encode($contact->id).'').'" class="btn btn-danger btn-xs btn-icon" data-delete="" title="'.__('general.delete').'" data-toggle="tooltip" data-placement="left"><i class="fa fa-trash"></i></a>';
				$btn .= '</div></div>';
				return $btn;
            })
			->addColumn('control', function ($contact) {
				$check = '<div style="text-align:center;"><a href="javascript:void(0);" class="btn btn-secondary btn-xs btn-icon" data-placement="left"><i class="fa fa-plus"></i></a></div>';
				return $check;
			})
			->escapeColumns([])
			->make(true);
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
		if(Auth::user()->can('create-contacts')) {
			return view('components.contact.create');
		} else {
			return redirect('forbidden');
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
		if(Auth::user()->can('create-contacts')) {
			$this->validate($request,[
				'name' => 'required',
				'email' => 'required|string|max:255|email',
				'subject' => 'required',
				'message' => 'required'
			]);

			$request->request->add([
				'created_by' => Auth::User()->id,
				'updated_by' => Auth::User()->id
			]);
			$requestData = $request->all();

			Contact::create($requestData);
			
			return redirect('dashboard/contacts')->with('flash_message', __('contact.store_notif'));
		} else {
			return redirect('forbidden');
		}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function list($id)
    {
		if(Auth::user()->can('read-contacts')) {
			$ids = Hashids::decode($id);
			$contact = Contact::findOrFail($ids[0]);
			$contact->update([
				'status' => 'Y',
				'updated_by' => Auth::User()->id
			]);

			return view('components.contact.show', compact('contact'));
		} else {
			return redirect('forbidden');
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
		if(Auth::user()->can('update-contacts')) {
			$ids = Hashids::decode($id);
			$contact = Contact::findOrFail($ids[0]);

			return view('components.contact.edit', compact('contact'));
		} else {
			return redirect('forbidden');
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
		if(Auth::user()->can('update-contacts')) {
			$ids = Hashids::decode($id);
			$this->validate($request,[
				'name' => 'required',
				'email' => 'required|string|max:255|email',
				'subject' => 'required',
				'message' => 'required',
				'status' => 'required'
			]);
			$request->request->add([
				'updated_by' => Auth::User()->id
			]);
			$requestData = $request->all();

			$contact = Contact::findOrFail($ids[0]);
			$contact->update($requestData);

			return redirect('dashboard/contacts')->with('flash_message', __('contact.update_notif'));
		} else {
			return redirect('forbidden');
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
		if(Auth::user()->can('delete-contacts')) {
			$ids = Hashids::decode($id);
			Contact::destroy($ids[0]);

			return redirect('dashboard/contacts')->with('flash_message', __('contact.destroy_notif'));
		} else {
			return redirect('forbidden');
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
		if(Auth::user()->can('delete-contacts')) {
			if ($request->has('id')) {
				$ids = $request->id;
				foreach($ids as $id){
					$idd = Hashids::decode($id);
					Contact::destroy($idd[0]);
				}
				return redirect('dashboard/contacts')->with('flash_message', __('contact.destroy_notif'));
			} else {
				return redirect('dashboard/contacts')->with('flash_message', __('contact.destroy_error_notif'));
			}
		} else {
			return redirect('forbidden');
		}
    }
	
	public function reply($id)
    {
		if(Auth::user()->can('update-contacts')) {
			$ids = Hashids::decode($id);
			$contact = Contact::findOrFail($ids[0]);

			return view('components.contact.reply', compact('contact'));
		} else {
			return redirect('forbidden');
		}
    }
	
	public function postReply(Request $request)
    {
		if(Auth::user()->can('create-contacts')) {
			$this->validate($request,[
				'id' =>  'required',
				'message' => 'required'
			]);

			$contact = Contact::findOrFail($request->id);
			
			Mail::to($contact->email, $contact->name)
				->queue(new ContactMail([
					'contact' => $contact,
					'content' => $request->message
				]));
			
			return redirect('dashboard/contacts')->with('flash_message', __('contact.reply_notif'));
		} else {
			return redirect('forbidden');
		}
    }
}
