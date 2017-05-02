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
                    ->simplePaginate(20);

        return view('list')->with(['bookmarks' => $recent]);
    }
    
    /**
     * Fetches results for a single tag page and populates a view with the results
     */
    public function showTag($tag = null)
    {
        $tagged = Bookmark::whereHas('tags', function ($query) use ($tag) {
                        $query->where('tag', '=', $tag);
                    })
                    ->orderBy('time_posted', 'desc')
                    ->simplePaginate(20);

        return view('list')->with(['bookmarks' => $tagged, 'tagName' => $tag]);
    }
    
    public function search(Request $request)
    {
        if(!$request->has('query')) {
            return redirect()->route('index');
        }

        $query = $request->input('query');

        $queryBuilder = Bookmark::whereHas('tags', function ($q) use ($query) {
                        $q->where('tag', 'like', '%' . $query . '%');
                    })
                    ->orWhere('title', 'like', '%' . $query . '%')
                    ->orWhere('description', 'like', '%' . $query . '%')
                    ->orWhere('url', 'like', '%' . $query . '%')
                    ->orderBy('time_posted', 'desc');

        $count = $queryBuilder->get()->count();
        $results = $queryBuilder->simplePaginate(20);

        return view('list')->with([
            'bookmarks'    => $results,
            'query'        => $query,
            'resultsCount' => $count
        ]);
    }
}
