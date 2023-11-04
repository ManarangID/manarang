<?php

use App\Models\Tag;
use App\Models\Post;
use App\Models\Pages;
use App\Models\Theme;
use App\Models\Gallery;
use App\Models\Setting;
use App\Models\Categories;

if (!function_exists('getPicture')) {
	function getPicture($name, $type, $user)
    {
		if ($type == 'medium') {
			if (file_exists('po-content/uploads/medium/medium_'.$name)) {
				return asset('po-content/uploads/medium/medium_'.$name);
			} else {
				return asset('po-content/uploads/users/user-'.$user.'/medium/medium_'.$name);
			}
		} elseif ($type == 'thumb') {
			if (file_exists(Storage::url('gallery/thumbs/'.$name))) {
				return asset(Storage::url('gallery/thumbs/'.$name));
			} else {
				return asset(Storage::url('gallery/thumbs/'.$name));
			}
		} else {
			if (file_exists(Storage::url('gallery/'.$name))) {
				return asset(Storage::url('gallery/'.$name));
			} else {
				return asset(Storage::url('gallery/'.$name));
			}
		}
	}
}

if (!function_exists('getPicturepost')) {
	function getPicturepost($name, $type, $user)
    {
		if ($type == 'medium') {
			if (file_exists('po-content/uploads/medium/medium_'.$name)) {
				return asset('po-content/uploads/medium/medium_'.$name);
			} else {
				return asset('po-content/uploads/users/user-'.$user.'/medium/medium_'.$name);
			}
		} elseif ($type == 'thumb') {
			if (file_exists(Storage::url('post/thumbnail/'.$name))) {
				return asset(Storage::url('post/thumbnail/'.$name));
			} else {
				return asset(Storage::url('post/thumbnail/'.$name));
			}
		} else {
			if (file_exists(Storage::url('post/'.$name))) {
				return asset(Storage::url('post/'.$name));
			} else {
				return asset(Storage::url('post/'.$name));
			}
		}
	}
}

if (!function_exists('getPicturepages')) {
	function getPicturepages($name, $type, $user)
    {
		if ($type == 'medium') {
			if (file_exists('po-content/uploads/medium/medium_'.$name)) {
				return asset('po-content/uploads/medium/medium_'.$name);
			} else {
				return asset('po-content/uploads/users/user-'.$user.'/medium/medium_'.$name);
			}
		} elseif ($type == 'thumb') {
			if (file_exists(Storage::url('pages/thumbnail/'.$name))) {
				return asset(Storage::url('pages/thumbnail/'.$name));
			} else {
				return asset(Storage::url('pages/thumbnail/'.$name));
			}
		} else {
			if (file_exists(Storage::url('pages/'.$name))) {
				return asset(Storage::url('pages/'.$name));
			} else {
				return asset(Storage::url('pages/'.$name));
			}
		}
	}
}

if (!function_exists('getFile')) {
	function getFile($name, $user)
    {
		if (file_exists('po-content/uploads/'.$name)) {
			return asset('po-content/uploads/'.$name);
		} else {
			return asset('po-content/uploads/users/user-'.$user.'/'.$name);
		}
	}
}

if (!function_exists('getSetting')) {
    function getSetting($options)
    {
		$result = Setting::where('options', $options)->first();
		if ($result) {
			return $result->value;
		} else {
			return '';
		}
	}
}

if (!function_exists('getTheme')) {
    function getTheme($files)
    {
		$result = Theme::where('active', 'Y')->first();
		if ($result) {
			return 'frontend.'.$result->folder.'.'.$files;
		} else {
			$resultdef = Theme::where('id', '1')->first();
			return 'frontend.'.$resultdef->folder.'.'.$files;
		}
	}
}

if (!function_exists('getSettingGroup')) {
    function getSettingGroup($groups)
    {
		$result = Setting::where('groups', $groups)->orderBy('id', 'asc')->get();
		if ($result) {
			return $result;
		} else {
			return [];
		}
	}
}

if (!function_exists('categoryTreeOption')) {
    function categoryTreeOption(array $elements, $parentId = 0, $indent = '')
    {
		foreach ($elements as $key => $element) {
			if ($element['parent'] == $parentId) {
				echo '<option value="'.$element['id'].'">'.$indent.' '.$element['title'].'</option>';
				$children = categoryTreeOption($elements, $element['id'], $indent.'-');
			}
		}
	}
}

if (!function_exists('getMenus')) {
    function getMenus()
    {
		$menus = new Menu;
		return $menus->tree();
	}
}

if (!function_exists('getPages')) {
    function getPages($id)
    {
		$result = Pages::where('id', $id)->first();
		return $result;
	}
}

if (!function_exists('getLatestpages')) {
    function getLatestpages($limit)
    {
		$result = Pages::select('id', 'title', 'seotitle')->where('active', '=', 'Y')->limit($limit)->orderBy('id', 'ASC')->get();
		return $result;
	}
}

if (!function_exists('getCategory')) {
    function getCategory($limit)
    {
		$result = Categories::select('id', 'title', 'seotitle')->where('active', '=', 'Y')->limit($limit)->orderBy('id', 'ASC')->withCount('posts')->get();
		return $result;
	}
}

if (!function_exists('getCategorymenu')) {
    function getCategorymenu($limit)
    {
		$result = Categories::select('id', 'title', 'seotitle')->where('active', '=', 'Y')->limit($limit)->orderBy('id', 'ASC')->get();
		return $result;
	}
}

if (!function_exists('latestPost')) {
	function latestPost($limit, $offset = '0')
	{
		$result = Post::leftJoin('users', 'users.id', 'posts.created_by')
			->leftJoin('categories', 'categories.id', 'posts.category_id')
			->where([['posts.active', '=', 'Y']])
			->select('posts.*', 'categories.title as ctitle', 'categories.seotitle as cseotitle', 'users.name')
			->orderBy('posts.id', 'desc')
			->limit($limit)
			->offset($offset)
			->get();
		return $result;
	}
}

if (!function_exists('postByCategory')) {
    function postByCategory($category_id, $limit, $offset = '0')
    {
        $result = Post::leftJoin('users', 'users.id', 'posts.created_by')
            ->leftJoin('categories', 'categories.id', 'posts.category_id')
            ->where([['posts.active', '=', 'Y']])
            ->select('posts.*', 'categories.title as ctitle', 'categories.seotitle as cseotitle', 'users.name')
            ->orderBy('posts.id', 'desc')
            ->where('category_id', $category_id)
            ->limit($limit)
            ->offset($offset)
            ->get();
        return $result;
    }
}

if (!function_exists('latestPostWithPaging')) {
	function latestPostWithPaging($limit)
	{
		$result = Post::leftJoin('users', 'users.id', 'posts.created_by')
			->leftJoin('categories', 'categories.id', 'posts.category_id')
			->where([['posts.active', '=', 'Y']])
			->select('posts.*', 'categories.title as ctitle', 'categories.seotitle as cseotitle', 'users.name')
			->orderBy('posts.id', 'desc')
			->paginate($limit);
		return $result;
	}
}

if (!function_exists('headlinePost')) {
	function headlinePost($limit, $offset = '0')
	{
		$result = Post::leftJoin('users', 'users.id', 'posts.created_by')
			->leftJoin('categories', 'categories.id', 'posts.category_id')
			->where([['posts.active', '=', 'Y'],['posts.headline', '=', 'Y']])
			->select('posts.*', 'categories.title as ctitle', 'categories.seotitle as cseotitle', 'users.name')
			->orderBy('posts.id', 'desc')
			->limit($limit)
			->offset($offset)
			->get();
		return $result;
	}
}

if (!function_exists('popularPost')) {
	function popularPost($limit, $offset = '0', $date = 'all')
	{
		if($date == 'all') {
			$result = Post::leftJoin('users', 'users.id', 'posts.created_by')
				->leftJoin('categories', 'categories.id', 'posts.category_id')
				->where([['posts.active', '=', 'Y']])
				->select('posts.*', 'categories.title as ctitle', 'categories.seotitle as cseotitle', 'users.name')
				->orderBy('posts.hits', 'desc')
				->limit($limit)
				->offset($offset)
				->get();
		} else {
			$isdate = [];
			if($date == 'monthly') {
				$isdate = [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];
			} else if($date == 'yearly') {
				$isdate = [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()];
			} else {
				$isdate = [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
			}
			$result = Post::leftJoin('users', 'users.id', 'posts.created_by')
				->leftJoin('categories', 'categories.id', 'posts.category_id')
				->where([['posts.active', '=', 'Y']])
				->whereBetween('posts.created_at', $isdate)
				->select('posts.*', 'categories.title as ctitle', 'categories.seotitle as cseotitle', 'users.name')
				->orderBy('posts.hits', 'desc')
				->limit($limit)
				->offset($offset)
				->get();
		}
		return $result;
	}
}

if (!function_exists('trendingPost')) {
	function trendingPost($limit, $offset = '0', $date = 'all')
	{
		if($date == 'all') {
			$result = Post::leftJoin('users', 'users.id', 'posts.created_by')
				->leftJoin('categories', 'categories.id', 'posts.category_id')
				->where([['posts.active', '=', 'Y']])
				->select('posts.*', 'categories.title as ctitle', 'categories.seotitle as cseotitle', 'users.name')
				->selectRaw('(select count(id) as total from comments where post_id = posts.id) as ctotal')
				->orderBy('posts.hits', 'desc')
				->orderBy('ctotal', 'desc')
				->limit($limit)
				->offset($offset)
				->get();
		} else {
			$isdate = [];
			if($date == 'monthly') {
				$isdate = [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];
			} else if($date == 'yearly') {
				$isdate = [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()];
			} else {
				$isdate = [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
			}
			$result = Post::leftJoin('users', 'users.id', 'posts.created_by')
				->leftJoin('categories', 'categories.id', 'posts.category_id')
				->where([['posts.active', '=', 'Y']])
				->whereBetween('posts.created_at', $isdate)
				->select('posts.*', 'categories.title as ctitle', 'categories.seotitle as cseotitle', 'users.name')
				->selectRaw('(select count(id) as total from comments where post_id = posts.id) as ctotal')
				->orderBy('posts.hits', 'desc')
				->orderBy('ctotal', 'desc')
				->limit($limit)
				->offset($offset)
				->get();
		}
		return $result;
	}
}

if (!function_exists('relatedPost')) {
	function relatedPost($id, $tag, $limit, $offset = '0')
	{
		$tags = explode(',', $tag);
		$arrtags = [];
		foreach($arrtags as $a) {
			$arrtags[] = ['posts.tag', 'LIKE', '%'.$a.'%'];
		}
		$result = Post::leftJoin('users', 'users.id', 'posts.created_by')
			->leftJoin('categories', 'categories.id', 'posts.category_id')
			->orWhere($arrtags)
			->where([
				['posts.id', '!=', $id],
				['posts.active', '=', 'Y']
			])
			->select('posts.*', 'categories.title as ctitle', 'categories.seotitle as cseotitle', 'users.name')
			->orderByRaw("RAND()")
			->limit($limit)
			->offset($offset)
			->get();
		return $result;
	}
}

if (!function_exists('getTag')) {
	function getTag($limit, $offset = '0')
	{
		$result = Tag::leftJoin('users', 'users.id', 'tags.created_by')
			->where([['tags.count', '>', '1']])
			->select('tags.*', 'users.name')
			->orderBy('tags.count', 'desc')
			->limit($limit)
			->offset($offset)
			->get();
		return $result;
	}
}

if (!function_exists('latestGallery')) {
	function latestGallery($limit, $offset = '0')
	{
		$result = Gallery::leftJoin('users', 'users.id', 'galleries.created_by')
			->leftJoin('albums', 'albums.id', 'galleries.album_id')
			->where('albums.active', '=', 'Y')
			->select('galleries.*', 'albums.title as atitle', 'albums.seotitle as aseotitle', 'users.name')
			->orderBy('galleries.id', 'desc')
			->limit($limit)
			->offset($offset)
			->get();
		return $result;
	}
}

if (!function_exists('latestSlider')) {
	function latestSlider($limit)
    {
		$result = Gallery::select('id', 'title', 'picture')->where('album_id', 1)->limit($limit)->orderBy('id', 'ASC')->get();
		return $result;
	}
}

if (!function_exists('postWithPagination')) {
    function postWithPagination($paginator, $prev, $next)
    {
		$result = '';
		if($paginator['prev_page_url'] == null) {
			$result .= '<li class="disabled">'.$prev.'</li>';
		} else {
			$result .= '<li><a href="'.$paginator['prev_page_url'].'">'.$prev.'</a></li>';
		}
		for($i=1; $i<=$paginator['total']; $i++) {
			if($i == $paginator['current_page']) {
				$result .= '<li class="active"><span>'.$i.'</span></li>';
			} else {
				$result .= '<li><a href="?segment='.$i.'">'.$i.'</a></li>';
			}
		}
		if($paginator['next_page_url'] == null) {
			$result .= '<li><a class="disabled">'.$next.'</a></li>';
		} else {
			$result .= '<li><a href="'.$paginator['next_page_url'].'">'.$next.'</a></li>';
		}

		return $result;
	}
}

if (!function_exists('getComments')) {
    function getComments($id, $limit)
    {
		$comments = new Comment;
		return $comments->tree($id, $limit);
	}
}

if (!function_exists('prettyUrl')) {
    function prettyUrl($post)
    {
		$url = '';
		if(getSetting('slug') == 'post/slug') {
			$url = url('post/'.$post->seotitle);
		} else if(getSetting('slug') == 'post/slug-id') {
			$url = url('post/'.$post->seotitle.'-'.$post->id);
		} else if(getSetting('slug') == 'article/yyyy/mm/dd/slug') {
			$url = url('article/'.date('Y' , strtotime($post->created_at)).'/'.date('m' , strtotime($post->created_at)).'/'.date('d' , strtotime($post->created_at)).'/'.$post->seotitle);
		} else {
			$url = url('detailpost/'.$post->seotitle);
		}
		return $url;
	}
}