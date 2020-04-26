<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Spaces;

class Pages extends Controller
{
    public function space_index(Request $req)
    {
        $jwt = $req->jwt;
        $spaces = Spaces::where('user_id', $jwt->id)->simplePaginate(10);
        return view('pages.spaces.index', compact('jwt'), compact('spaces'));
    }
    public function space_create(Request $req)
    {
        $jwt = $req->jwt;
        return view('pages.spaces.create', compact('jwt'));
    }
    public function space_edit(Request $req, $id)
    {
        $space = Spaces::where('id', $id)->first();
        $jwt = $req->jwt;
        
        return view('pages.spaces.edit', compact('jwt'), compact('space'));
    }
    public function space_direction(Request $req, $id)
    {
        $space = Spaces::where('id', $id)->first();
        $jwt = $req->jwt;
        
        return view('pages.spaces.direction', compact('jwt'), compact('space'));
    }
}
