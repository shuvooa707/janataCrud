<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $req)
    {
        $c = Channel::create([
            "trade_code" => $req->trade_code,
            "date" => $req->date,
            "high" => $req->high,
            "low" => $req->low,
            "open" => $req->open,
            "close" => $req->close,
            "volume" => $req->volume,
        ]);

        return ["msg" => "success", "c" => $c, "last" => Channel::all()->count()];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id = null)
    {

        $file  = Channel::all();
        $clusters = Collection::wrap($file->reduce(function($acc, $e) {
            if ( !isset($acc[$e->trade_code]) ) {
                $acc[$e->trade_code] = [
                    "trade_code" => $e->trade_code,
                    "date" => str_replace(',', '', [$e->date]),
                    "high" => str_replace(',', '', [$e->high]),
                    "low" => str_replace(',', '', [$e->low]),
                    "open" => str_replace(',', '', [$e->open]),
                    "close" => str_replace(',', '', [$e->close]),
                    "volume" => str_replace(',', '', [$e->volume]),
                ];
            } else {
                array_push($acc[$e->trade_code]["date"], str_replace(',', '', $e->date));
                array_push($acc[$e->trade_code]["high"], str_replace(',', '', $e->high));
                array_push($acc[$e->trade_code]["low"], str_replace(',', '', $e->low));
                array_push($acc[$e->trade_code]["open"], str_replace(',', '', $e->open));
                array_push($acc[$e->trade_code]["close"], str_replace(',', '', $e->close));
                array_push($acc[$e->trade_code]["volume"], str_replace(',', '', $e->volume));
            }

            return $acc;
        },[]));



        // dd($clusters->first());
        return view("home", [ "data" => $file->take(10), "clusters" => $clusters]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id = null)
    {
        Channel::find($req->id)->update([
            "trade_code" => $req->trade_code,
            "date" => $req->date,
            "high" => $req->high,
            "low" => $req->low,
            "open" => $req->open,
            "close" => $req->close,
            "volume" => $req->volume,
        ]);

        return [ "msg" => "success"];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $req)
    {
        $c = Channel::find($req->id)->delete();

        return [ "msg" => "success" ];
    }

}
