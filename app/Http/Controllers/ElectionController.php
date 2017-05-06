<?php

namespace App\Http\Controllers;

use App\Election;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ElectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::id();
        return Election::where('creator_id', '=', $id)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $creator = Auth::user();
        $election = new Election();
        $election->name = $request->json()->get('name');
        $election->creator()->associate($creator);
        $election->save();

        return redirect()->route('election.show', ['id' => $election->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Election  $election
     * @return \Illuminate\Http\Response
     */
    public function show(Election $election)
    {
        return $election;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Election  $election
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Election $election)
    {
        $id = Auth::id();
        if (0 === strcmp($id, $election->creator_id)) {
            $election->name = $request->json()->get('name');
            $election->save();
        }
        return $election;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Election  $election
     * @return \Illuminate\Http\Response
     */
    public function destroy(Election $election)
    {
        $id = Auth::id();
        if (0 === strcmp($id, $election->creator_id)) {
            $election->delete();
        }
        return response()->json(new \stdClass());
    }
}