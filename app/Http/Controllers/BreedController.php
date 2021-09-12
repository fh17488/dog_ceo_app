<?php

namespace App\Http\Controllers;

use App\Models\Breed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class BreedController extends Controller
{
    public function index()
    {
        return Breed::getAll()->map(function ($breed) {
            return [
                'name'            => $breed->name,
                'subbreeds'         => explode(',',$breed->subbreeds),
            ];
        });;
    }

    public function show($id)
    {
        $breed = Breed::find($id);        
        $users = [];
        $parks = [];
        foreach($breed->users()->get() as $user){
            array_push($users, ['name'=>$user->name,
            'email'=>$user->email,
            'location'=>$user->location]);
        }
        foreach($breed->parks()->get() as $park){
            array_push($parks, ['name'=>$park->name]);
        }
        $response = ['name'=> $breed->name,
                    'subbreeds'=>$breed->subbreeds,
                    'users' => $users,
                    'parks' => $parks];
        return $response;
    }

    public function randomBreed(){        
        return Breed::all()->random(1)->first();
    }

    public function getImageByBreed($id){
        $breed = Breed::find($id); 
        $img_url = "";
        $status = "failure";
        while($status != "success"){
            $response = Http::acceptJson()->get("https://dog.ceo/api/breed/".$breed->name."/images/random");
            $status = $response['status'];
        }
        $img_url = $response['message'];
        $imageData = base64_encode(file_get_contents($img_url));
        return '<img src="data:image/jpeg;base64,'.$imageData.'">';
    }

    public function test(){

    }
    
    public function readFromCache()
    {
        $breeds = Cache::get("all_breeds");
        $resultArray = [];
        foreach($breeds as $breed){
            array_push($resultArray, $breed);            
        }
        return response()->json($resultArray);
    }
}
