<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Spaces as SpaceModel;

class Spaces extends Controller {
    public function create(Request $req)
    {
        $req->validate([
            'title' => 'required|min:5',
            'description' => 'required|min:10',
            'lat' => 'required|min:3',
            'lng' => 'required|min:3',
            'region' => 'required|min:5',
            'district' => 'required|min:5',
            'village' => 'required|min:5',
        ]);
        $input = $req->input();
        $input['user_id'] = $req->jwt->id;

        try {
            $space = SpaceModel::create($input);
        } catch (\Exception $e) {
            return response()->json([
                'message'=>'Error when insert to database',
                'errors'=> new \stdClass()
            ]);
        }
        
        return response()->json([
            'message'=>'Space had been stored',
            'success'=> new \stdClass()
        ]);
    }

    public function update(Request $req)
    {
        $req->validate([
            'id' => 'required|numeric',
            'title' => 'required|min:5',
            'description' => 'required|min:10',
            'lat' => 'required|min:3',
            'lng' => 'required|min:3',
            'region' => 'required|min:5',
            'district' => 'required|min:5',
            'village' => 'required|min:5',
        ]);
        $input = $req->except(['id']);
        $input['user_id'] = $req->jwt->id;

        try {
            $space = SpaceModel::where('id', $req->input('id'))->update($input);
        } catch (\Exception $e) {
            return response()->json([
                'message'=>'Error when update to database',
                'errors'=> new \stdClass()
            ]);
        }
        
        return response()->json([
            'message'=>'Space had been updated',
            'success'=> new \stdClass()
        ]);
    }

    public function delete(Request $req)
    {
        $req->validate([
            'id' => 'required|numeric',
        ]);

        try {
            $space = SpaceModel::where('id', $req->input('id'))->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message'=>'Error when delete to database',
                'errors'=> new \stdClass()
            ]);
        }
        
        return response()->json([
            'message'=>'Space had been deleted',
            'success'=> new \stdClass()
        ]);
    }

    public function neighbord(Request $req)
    {
        $req->validate([
            'lat' => 'required',
            'lng' => 'required',
            'rad' => 'required|numeric',
        ]);
        $obj = new \stdClass();

        try {
            $space = new SpaceModel();
            return $space->getNeighbord($req->lat,$req->lng,$req->rad)->get();
        } catch (\Exception $e) {
            $obj->message = $e->getMessage();
            return response()->json([
                'message'=>'Error retrieve neighbord',
                'errors'=> $obj
            ]);
        }
        
        $obj = new \stdClass();
        return response()->json([
            'message'=>'Cannot get neighbord',
            'success'=> $obj
        ]);
    }
}