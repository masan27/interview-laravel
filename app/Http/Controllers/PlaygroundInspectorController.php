<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

require_once app_path('Support/PlaygroundEngine.phar');

use App\Support\PlaygroundEngine;

class PlaygroundInspectorController extends Controller
{
    public function inspectSoal3()
    {
        return PlaygroundEngine::inspectSoal3();
    }

    public function inspectSoal5()
    {
        return PlaygroundEngine::inspectSoal5();
    }

    public function inspectSoal6(Request $request)
    {
        return PlaygroundEngine::inspectSoal6($request);
    }

    public function inspectSoal7(Request $request)
    {
        return PlaygroundEngine::inspectSoal7($request);
    }

    public function simulateCheckIn(Request $request)
    {
        return PlaygroundEngine::simulateCheckIn($request);
    }

    public function resetCheckInCounter()
    {
        return PlaygroundEngine::resetCheckInCounter();
    }

    public function resetDatabase()
    {
        return PlaygroundEngine::resetDatabase();
    }
}
