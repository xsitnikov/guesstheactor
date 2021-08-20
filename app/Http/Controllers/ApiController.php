<?php

namespace App\Http\Controllers;

use App\TMDBApi;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    public function getActors() {
        $res = [];

        $tmdbApi = new TMDBApi();
        $actors = $tmdbApi->getPopularActors();

        if(count($actors) > 0) {
            foreach ($actors as $key => $value) {
                if($key === 0) {
                    $res['id'] = $value['id'];
                    $res['photo'] = $value['photo'];
                }
                $res['names'][] = $value['name'];
            }
        }

        if(!isset($res['id']) || !isset($res['photo']) || !isset($res['names'])) {
            $res['code'] = 500;
            $res['message'] = "Error, try it later";
            Log::error("Error, try it later");
        } else {
            $res['code'] = 200;
            shuffle($res['names']);
        }
        return response()->json($res);
    }

    public function checkActors($id,$name) {
        $id = (int)$id;

        $tmdbApi = new TMDBApi();
        $answerRight = $tmdbApi->checkName($id,$name);

        return response()->json([
            'result' => $answerRight ? 'right' : 'wrong'
        ]);

    }
}
