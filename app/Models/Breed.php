<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class Breed extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subbreeds',
        'image',
    ];

    public static function getAll(){
        $response = Http::acceptJson()->get('https://dog.ceo/api/breeds/list/all');
        $all_breeds = $response['message'];
        $all_breeds_cache = [];
        foreach($all_breeds as $key => $value){
            $subbreeds = Breed::convertToStr($value);
            array_push($all_breeds_cache, [$key => $subbreeds]);
            Breed::updateOrCreate(
                ['name' => $key],
                ['subbreeds' => $subbreeds]
            );
            
        }
        Cache::set("all_breeds", $all_breeds_cache);        
        return Breed::all();
    }

    public static function convertToStr($array){
        $returnValue = "";
        foreach($array as $value){
            $returnValue .= $value.",";
        }
        return substr($returnValue,0, -1);
    }

    public function users(){
        return $this->morphToMany(User::class, 'userable');
    }

    public function parks(){
        return $this->morphToMany(Park::class, 'parkable');
    }
}
