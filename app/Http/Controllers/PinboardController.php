<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use \kijin\PinboardAPI as PinboardAPI;

class PinboardController extends Controller
{
    private $pinboard;

    protected function connect()
    {
        try {
            $this->pinboard = new \PinboardAPI(
                env('PINBOARD_USER'),
                env('PINBOARD_USER') . ':' . env('PINBOARD_TOKEN')
            );
        }
        catch(PinboardException_ConnectionError $e) {
            Log::error("Error connecting to the Pinboard API, likely due to an outage");
        }
        catch (PinboardException_AuthenticationFailure $e) {
            Log::alert("Authentication failure. You might have the wrong token.");
        }
        catch(PinboardException_TooManyRequests $e) {
            Log::warning("Rate limit was hit. Please wait a bit before trying again!");
        }
        catch(PinboardException_InvalidResponse $e) {
            Log::error("Invalid response received", ['error' => $e]);
        }
        catch (PinboardException $e) {
            Log::error("Error connecting to the Pinboard API", ['error' => $e]);
            return false;
        }

        return true;
    }

    public function show()
    {
        $this->connect();
        $recent = $this->pinboard->get_recent();

        return view('list')->with(['bookmarks' => $recent]);
    }
}
