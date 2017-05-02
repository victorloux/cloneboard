<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bookmark;
use App\Tag;

class BookmarkController extends Controller
{
    /**
     * Fetches results for the index page and populates a view with the results
     */
    public function show()
    {
        $recent = Bookmark::orderBy('time_posted', 'desc')
                    ->simplePaginate(config('view.items_per_page'));

        $recent->withPath(config('app.url'));
        return view('list')->with(['bookmarks' => $recent]);
    }
    
    /**
     * Fetches results for a single tag page and populates a view with the results
     */
    public function showTag($tag = null)
    {
        $queryBuilder = Bookmark::whereHas('tags', function ($query) use ($tag) {
                        $query->where('tag', '=', $tag);
                    })
                    ->orderBy('time_posted', 'desc');

        $count = $queryBuilder->get()->count();
        $tagged = $queryBuilder->simplePaginate(config('view.items_per_page'));


        return view('list')->with([
            'bookmarks' => $tagged,
            'tagName'   => $tag,
            'resultsCount' => $count
        ]);
    }
    
    public function searchForm(Request $request)
    {
        if(!$request->has('query')) {
            return redirect()->route('index');
        }
        
        return redirect()->route('search', ['query' => $request->input('query') ]);
    }
    
    public function search(Request $request, $query)
    {
        if(!$query) {
            return redirect()->route('index');
        }

        $queryBuilder = Bookmark::whereHas('tags', function ($q) use ($query) {
                        $q->where('tag', 'like', '%' . $query . '%');
                    })
                    ->orWhere('title', 'like', '%' . $query . '%')
                    ->orWhere('description', 'like', '%' . $query . '%')
                    ->orWhere('url', 'like', '%' . $query . '%')
                    ->orderBy('time_posted', 'desc');

        $count = $queryBuilder->get()->count();
        $results = $queryBuilder->simplePaginate(config('view.items_per_page'));

        return view('list')->with([
            'bookmarks'    => $results,
            'query'        => $query,
            'resultsCount' => $count
        ]);
    }
}
