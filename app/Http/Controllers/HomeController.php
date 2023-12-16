<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Post;
use App\Models\User;
use App\Models\Pages;
use App\Models\Theme;
use App\Models\Comment;
use App\Models\Contact;
use App\Models\Gallery;
use App\Models\Component;
use Illuminate\View\View;
use App\Models\Categories;
use App\Models\PostGallery;
use Illuminate\Http\Request;
use Spatie\Analytics\Period;
use Illuminate\Http\Response;
use Spatie\Analytics\Analytics;
use Illuminate\Support\Facades\Auth;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application home.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
		$twitterid = explode('/', getSetting('twitter'));
		SEOTools::setTitle(getSetting('web_name'));
		SEOTools::setDescription(getSetting('web_description'));
		SEOTools::metatags()->setKeywords(explode(',', getSetting('web_keyword')));
		SEOTools::setCanonical(getSetting('web_url'));
		SEOTools::opengraph()->setTitle(getSetting('web_name'));
		SEOTools::opengraph()->setDescription(getSetting('web_description'));
		SEOTools::opengraph()->setUrl(getSetting('web_url'));
		SEOTools::opengraph()->setSiteName(getSetting('web_author'));
		SEOTools::opengraph()->addImage(asset(Storage::url('images/'.getSetting('logo'))));
		SEOTools::twitter()->setSite('@'.$twitterid[count($twitterid)-1]);
		SEOTools::twitter()->setTitle(getSetting('web_name'));
		SEOTools::twitter()->setDescription(getSetting('web_description'));
		SEOTools::twitter()->setUrl(getSetting('web_url'));
		SEOTools::twitter()->setImage(asset(Storage::url('images/'.getSetting('logo'))));
		SEOTools::jsonLd()->setTitle(getSetting('web_name'));
		SEOTools::jsonLd()->setDescription(getSetting('web_description'));
		SEOTools::jsonLd()->setType('WebPage');
		SEOTools::jsonLd()->setUrl(getSetting('web_url'));
		SEOTools::jsonLd()->setImage(asset(Storage::url('images/'.getSetting('logo'))));
		
        return view(getTheme('home'));
    }
	
	public function error404()
    {
		$twitterid = explode('/', getSetting('twitter'));
		SEOTools::setTitle('Not Found - '.getSetting('web_name'));
		SEOTools::setDescription(getSetting('web_description'));
		SEOTools::metatags()->setKeywords(explode(',', getSetting('web_keyword')));
		SEOTools::setCanonical(getSetting('web_url') . '/404');
		SEOTools::opengraph()->setTitle('Not Found - '.getSetting('web_name'));
		SEOTools::opengraph()->setDescription(getSetting('web_description'));
		SEOTools::opengraph()->setUrl(getSetting('web_url') . '/404');
		SEOTools::opengraph()->setSiteName(getSetting('web_author'));
		SEOTools::opengraph()->addImage(asset(Storage::url('images/'.getSetting('logo'))));
		SEOTools::twitter()->setSite('@'.$twitterid[count($twitterid)-1]);
		SEOTools::twitter()->setTitle('Not Found - '.getSetting('web_name'));
		SEOTools::twitter()->setDescription(getSetting('web_description'));
		SEOTools::twitter()->setUrl(getSetting('web_url') . '/404');
		SEOTools::twitter()->setImage(asset(Storage::url('images/'.getSetting('logo'))));
		SEOTools::jsonLd()->setTitle('Not Found - '.getSetting('web_name'));
		SEOTools::jsonLd()->setDescription(getSetting('web_description'));
		SEOTools::jsonLd()->setType('WebPage');
		SEOTools::jsonLd()->setUrl(getSetting('web_url') . '/404');
		SEOTools::jsonLd()->setImage(asset(Storage::url('images/'.getSetting('logo'))));
		
		return response()->view(getTheme('404'), [], 404);
	}
	
	public function subscribe(Request $request)
    {
		$this->validate($request,[
			'email' => 'required|string|max:255|email'
		]);
		
		$name = explode('@', $request->email);
		$finalname = ucfirst($name[0]);
		
		$request->request->add([
			'name' => $finalname,
			'created_by' => 1,
			'updated_by' => 1
		]);
		$requestData = $request->all();

		Subscribe::create($requestData);
		
		return redirect('contact')->with('flash_message', __('subscribe.send_notif'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function dashboard(): Response
    {
		$post = Post::where('active', '=', 'Y')->count();
		$category = Categories::where('active', '=', 'Y')->count();
		$tag = Tag::count();
		$comment = Comment::where('active', '=', 'Y')->count();
		$commentunread = Comment::where('status', '=', 'N')->count();
		$pages = Pages::where('active', '=', 'Y')->count();
		$contactunread = Contact::where('status', '=', 'N')->count();
		$components = Component::where('active', '=', 'Y')->count();
		$theme = Theme::where('active', '=', 'Y')->count();
		$user = User::where('block', '=', 'N')->count();
		$populars = Post::where('active', '=', 'Y')->orderBy('hits', 'desc')->limit(5)->get();
		
		if (Auth::user()->hasRole('member')) {
			$_SESSION['RF']['subfolder'] = 'users/user-'.Auth::user()->id;
		} else {
			$_SESSION['RF']['subfolder'] = '';
		}
		
		return response (view('admin.dashboard', compact('post', 'pages', 'category', 'tag', 'comment', 'commentunread', 'pages', 'contactunread', 'components', 'theme', 'user', 'populars')));
	}
	
	/**
     * Display analytics pages.
     *
     * @return void
     */
    public function analytics()
    {
		$fetchTotalVisitorsAndPageViews = Analytics::fetchTotalVisitorsAndPageViews(Period::days(7));
		$fetchMostVisitedPages = Analytics::fetchMostVisitedPages(Period::days(7), 8);
		$fetchTopBrowsers = Analytics::fetchTopBrowsers(Period::days(7), 8);
		$fetchTopOperatingSystem = $this->fetchTopOperatingSystem(Period::days(7), 8);
		$fetchTopCountry = $this->fetchTopCountry(Period::days(7), 8);
		$fetchRealtimeUsers = $this->fetchRealtimeUsers();
		$fetchTopDevice = $this->fetchTopDevice(Period::days(7), 8);
		
		return view('components.admins.analytics', compact('fetchTotalVisitorsAndPageViews', 'fetchMostVisitedPages', 'fetchTopBrowsers', 'fetchTopOperatingSystem', 'fetchTopCountry', 'fetchRealtimeUsers', 'fetchTopDevice'));
	}
	
	/**
     * Display forbidden pages.
     *
     * @return void
     */
    public function forbidden()
    {
		return view('components.admins.forbiden');
	}
	
	public function fetchRealtimeUsers() {
		$analytics = Analytics::getAnalyticsService();
		$results = $analytics->data_realtime
			->get(
				'ga:'.env('ANALYTICS_VIEW_ID'),
				'rt:activeUsers'
			);

		return $results->rows[0][0] ? $results->rows[0][0] : 0;
	}
	
	public function fetchTopOperatingSystem(Period $period, int $maxResults = 10): Collection
	{
		$response = Analytics::performQuery(
			$period,
			'ga:sessions',
			[
				'dimensions' => 'ga:operatingSystem,ga:operatingSystemVersion',
				'sort' => '-ga:sessions',
			]
		);

		$topOSs = collect($response['rows'] ?? [])->map(function (array $osRow) {
			return [
				'os' => $osRow[0],
				'version' => $osRow[1],
				'sessions' => (int) $osRow[2],
			];
		});

		if ($topOSs->count() <= $maxResults) {
			return $topOSs;
		}

		return $this->summarizeTopOperatingSystem($topOSs, $maxResults);
	}

	protected function summarizeTopOperatingSystem(Collection $topOSs, int $maxResults): Collection
	{
		return $topOSs
			->take($maxResults - 1)
			->push([
				'os' => 'Others',
				'version' => '-',
				'sessions' => $topOSs->splice($maxResults - 1)->sum('sessions'),
			]);
	}
	
	public function fetchTopCountry(Period $period, int $maxResults = 10): Collection
	{
		$response = Analytics::performQuery(
			$period,
			'ga:sessions',
			[
				'dimensions' => 'ga:country',
				'sort' => '-ga:sessions',
			]
		);

		$topCountrys = collect($response['rows'] ?? [])->map(function (array $countryRow) {
			return [
				'country' => $countryRow[0],
				'sessions' => (int) $countryRow[1],
			];
		});

		if ($topCountrys->count() <= $maxResults) {
			return $topCountrys;
		}

		return $this->summarizeTopCountry($topCountrys, $maxResults);
	}

	protected function summarizeTopCountry(Collection $topCountrys, int $maxResults): Collection
	{
		return $topCountrys
			->take($maxResults - 1)
			->push([
				'country' => 'Others',
				'sessions' => $topCountrys->splice($maxResults - 1)->sum('sessions'),
			]);
	}
	
	public function fetchTopDevice(Period $period): Collection
	{
		$response = Analytics::performQuery(
			$period,
			'ga:users',
			[
				'dimensions' => 'ga:deviceCategory'
			]
		);

		return collect($response['rows'] ?? [])->map(function (array $deviceRow) {
			return $deviceRow;
		});
	}
	


    /**
     * Generate Image upload View
     *
     * @return void
     */
    public function dropzone()
    {
        return view('components.subscriber.index');
    }


    /**
     * Image Upload Code
     *
     * @return void
     */
    public function dropzoneStore(Request $request)
    {
        $image = $request->file('file');
        $imageName = time().$image->getClientOriginalName();
		$filename = pathinfo($imageName, PATHINFO_FILENAME);
        $image->move(public_path('storage/post/gallery'),$imageName);

		$imageUpload = new PostGallery();
        $imageUpload->title = $filename;
        $imageUpload->picture = $imageName;
        $imageUpload->save();
        return response()->json(['success'=>$imageName]);
    }
}
