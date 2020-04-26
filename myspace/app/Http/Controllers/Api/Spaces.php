<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Model\Spaces as SpaceModel;
use App\Model\SpacePhotos;

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
            'img' => 'required|max:2048'
        ]);


        // store input data
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

        // store image
        try {
            foreach ($req->file('img') as $file) {
                $path = 'spaces/photos/';
                $storage = Storage::disk('public')->put($path, $file);
                $storage = explode('//', $storage);
                SpacePhotos::create(['path'=>$path, 'filename'=>$storage[1], 'space_id'=>$space->id]);
            }
        } catch (\Exception $e) {
            SpaceModel::destroy($space->id);

            return response()->json([
                'message'=>'Error store image, please upload another image',
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
        $input = $req->except(['id', 'img']);

        try {
            $space = SpaceModel::where('id', $req->input('id'))->update($input);
        } catch (\Exception $e) {
            return response()->json([
                'message'=>'Error when update',
                'errors'=> new \stdClass()
            ]);
        }

        if ($req->hasFile('img')) {
            try {
                $photos = SpacePhotos::where('space_id', $req->input('id'))->get();
                foreach ($photos as $photo) {
                    Storage::disk('public')->delete($photo->path.$photo->filename);
                }
                SpacePhotos::where('space_id', $req->input('id'))->delete();
                
                foreach ($req->file('img') as $file) {
                    $path = 'spaces/photos/';
                    $storage = Storage::disk('public')->put($path, $file);
                    $storage = explode('//', $storage);
                    SpacePhotos::create(['path'=>$path, 'filename'=>$storage[1], 'space_id'=>$req->input('id')]);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'message'=>'Error when upload image, please try another image',
                    'errors'=> new \stdClass()
                ]);
            }
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
            // SpaceModel::where('id', $req->input('id'))->delete();
            $photos = SpacePhotos::where('space_id', $req->input('id'))->get();
            foreach ($photos as $photo) {
                Storage::disk('public')->delete($photo->path.$photo->filename);
            }
            SpacePhotos::where('space_id', $req->input('id'))->delete();
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