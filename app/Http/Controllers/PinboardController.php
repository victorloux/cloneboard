<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use \kijin\PinboardAPI as PinboardAPI;
use Carbon\Carbon;

use App\Bookmark;
use App\Tag;

class PinboardController extends Controller
{
    private $pinboard;
    const CACHE_TIME = 1800;    // half an hour

    /**
     * Attempts to connect to the Pinboard API
     * @return {boolean True if the connection was successful, false otherwise}
     */
    protected function connect()
    {
        // Check if we're already connected
        if($this->pinboard !== null) {
            return true;
        }

        try {
            $this->pinboard = new \PinboardAPI(
                env('PINBOARD_USER'),
                env('PINBOARD_USER') . ':' . env('PINBOARD_TOKEN')
            );
        }
        catch(\PinboardException_ConnectionError $e) {
            Log::error("Error connecting to the Pinboard API, likely due to an outage");
        }
        catch (\PinboardException_AuthenticationFailure $e) {
            Log::alert("Authentication failure. You might have the wrong token.");
        }
        catch(\PinboardException_TooManyRequests $e) {
            Log::warning("Rate limit was hit. Please wait a bit before trying again!");
        }
        catch(\PinboardException_InvalidResponse $e) {
            Log::error("Invalid response received", ['error' => $e]);
        }
        catch (\PinboardException $e) {
            Log::error("Error connecting to the Pinboard API", ['error' => $e]);
            return false;
        }

        return true;
    }
    
    /**
     * Update the bookmarks
     * @param  Carbon  $since Date to get objects from (defaults to all)
     * @param  integer $offset Starting index (defaults to infinite)
     * @param  integer $limit Maximum amount of bookmarks (defaults to infinite)
     * @return {Bookmark[]        Collection of updated bookmarks (may be empty)}
     */
    public function import($since, $offset = null, $limit = null)
    {
        $this->connect();

        // If a start offset is set, convert it to a UNIX timestamp
        if ($since !== null) {
            $since = $since->timestamp;
        }

        try {
            $new_bookmarks = $this->pinboard->get_all(
                $limit,
                $offset,
                null,
                $since,
                null
            );
            
            foreach ($new_bookmarks as $bookmark) {
                $this->importSingleBookmark($bookmark);
            }
            
            return $new_bookmarks;
        }
        catch(\PinboardException $e) {
            Log:error("Error while importing bookmarks", ['error' => $e]);
            return [];
        }
    }

    /**
     * Todo: this should update the read & privacy settings
     * of bookmarks, so that bookmarks who were later marked as
     * read show up in results
     */
    protected function updateReadStatus()
    {
        throw new \App\Exceptions\NotImplementedException();
    }

    /**
     * Converts a bookmark from the API and add it as a model in the database,
     * while adding relevant checks/conversions
     * @param  PinboardBookmark $bookmark The bookmark object
     * @return {Bookmark}                    Eloquent object
     */
    private function importSingleBookmark(\PinboardBookmark $bookmark)
    {
        // Ignore if record already exists
        if(!is_null(Bookmark::withoutGlobalScope('visible')->where('pinboard_id', $bookmark->hash)->first())) {
            return;
        }

        $record = Bookmark::create([
            'pinboard_id' => $bookmark->hash,
            'time_posted' => Carbon::createFromTimestamp($bookmark->timestamp),
            'title'       => $bookmark->title,
            'description' => $bookmark->description,
            'url'         => $bookmark->url,
            'starred'     => false,
            'others'      => $bookmark->others,
            'public'      => $bookmark->is_public,
            'unread'      => $bookmark->is_unread,
        ]);
        
        foreach ($bookmark->tags as $tagName) {
            $tag = Tag::firstOrCreate(array('tag' => $tagName));
            $record->tags()->attach($tag);
        }
        
        return $record;
    }

    /**
     * Check whether we need to fetch updates, and if we do, go fetch them!
     * @return {boolean} Whether we updated the database
     */
    protected function updateIfNeeded()
    {
        // If we're developing or if the cache time has expired,
        // we'll go fetch newer updates
        if(\App::environment('local') || Cache::get('last_update') < time() - self::CACHE_TIME) {        
            // Get the date of the last updated record in the db,
            // and request bookmarks newer than that
            $latest = \DB::table('bookmarks')->max('time_posted');
            
            // If there's no latest, then perhaps we shoud run the 
            // initial import first?
            if($latest === null) {
                throw new \Exception("No record in the database yet. Run `artisan import:all` first. After this initial import, bookmarks will be updated incrementally and automatically.");
                return false;
            }

            \Cache::forever('last_update', time());
            return true;
        }

        return false;
    }

    /**
     * Gets ALL the records in chunks. Useful for the first run;
     * after that, we'll update incrementally
     *
     * This is accessed via `artisan import:all`
     * defined in app/Console/Commands/InitialImport.php
     */
    public function getAllRecords()
    {
        $this->import(null, null, null);
        
        // The code below might ever be needed if you have tons of bookmarks.
        // the 'posts/all' endpoint has a rate limiting of 1 call every 5 minutes
        // but there's no specific limit given on how many bookmarks are returned
        // so we might have to chunk results.
        /*
        $resultsCount = 0;
        $offset = null;
        $limit = 250;

        do {
            $this->import(null, null, null);
            $offset += $limit;
            sleep(310); // avoid rate limit 
        } while ($resultsCount > 0);
        */
    }

    /**
     * Fetches results for the index page and populates a view with the results
     */
    public function show()
    {
        $this->updateIfNeeded();

        $recent = Bookmark::where('public', true)
                    ->orderBy('time_posted', 'desc')
                    ->take(10)
                    ->get();

        return view('list')->with(['bookmarks' => $recent]);
    }
}
