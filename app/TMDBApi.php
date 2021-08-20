<?php


namespace App;


use App\Interfaces\ExternalApiInterface;
use Illuminate\Support\Facades\Log;

class TMDBApi implements ExternalApiInterface
{
    private $apiBaseUrl = "https://api.themoviedb.org/3";
    private $photoBaseUrl = "https://www.themoviedb.org/t/p/w600_and_h900_bestv2";
    private $apiKey;

    /**
     * @throws \Exception
     */
    public function __construct() {
        if(env("TMDB_API_KEY")) {
            $this->apiKey = env("TMDB_API_KEY");
        } else {
            Log::error('Empty api key');
        }
    }

    public function getPopularActors()
    {
        $actors = [];

        $url = $this->apiBaseUrl . "/person/popular?language=en-US&page=1&api_key=" . $this->apiKey;

        try {
            $response = json_decode(file_get_contents($url),1);
            if(isset($response['results'])) {
                foreach ($response['results'] as $el) {
                    $actors[] = [
                        'id' => $el['id'],
                        'name' => $el['name'],
                        'photo' => $this->photoBaseUrl . $el['profile_path']
                    ];
                }
                shuffle($actors);
                $actors = array_slice($actors,0,5);
            }

        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return $actors;

    }

    public function checkName($id,$name)
    {
        $url = $this->apiBaseUrl . "/person/" . $id . "?language=en-US&api_key=" . $this->apiKey;

        try {
            $response = json_decode(file_get_contents($url),1);
            return isset($response['name']) && $response['name'] === $name;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

        return false;
    }
}
