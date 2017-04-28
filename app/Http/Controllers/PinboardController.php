<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \kijin\PinboardAPI as PinboardAPI;

class PinboardController extends Controller
{
    private $pinboard;

    protected function connect()
    {
        $this->pinboard = new \PinboardAPI(env('PINBOARD_USER'), env('PINBOARD_USER') . ':' . env('PINBOARD_TOKEN'));
    }

    public function show()
    {
        $this->connect();
        $recent = $this->pinboard->get_recent();

        return view('list')->with(['bookmarks' => $recent]);
    }
}
