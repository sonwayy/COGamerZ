<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FortniteStatsController extends AbstractController
{
    #[Route('/fortnite/stats', name: 'app_fortnite_stats')]
    public function index(): Response
    {
        /** 
         * Getting the fortnite player stats with the FortniteTracker API
         */
        // GET https://api.fortnitetracker.com/v1/profile/{platform}/{epic-nickname}
        define('FN_API_KEY', '9288fe9e-3d0a-4099-96c2-b95e4e38d68d');
        function getPlayerStats($platform, $epicNickname) {
            /**
             * @param string $platform
             * @param string $epicNickname
             * @return array
             */
            $url = "https://api.fortnitetracker.com/v1/profile/{$platform}/{$epicNickname}";

            // headrers that conatains the API key
            $headers = [
                'TRN-Api-Key: ' . FN_API_KEY
            ];
            $ch = curl_init();

            // set the url
            curl_setopt($ch, CURLOPT_URL, $url);

            // set the headers containing the API key
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            // return the transfer as a string
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // $output contains the output string
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            // get the response
            $response = curl_exec($ch);

            // close curl resource to free up system resources
            curl_close($ch);

            // return the response
            return json_decode($response, true);
        }

        // Getting the player stats
        $platform = 'kbm';
        $epicNickname = 'GamerZ';

        // Getting the player stats
        $playerStats = getPlayerStats($platform, $epicNickname);

        // Rendering the view
        echo '<pre>';
        print_r($playerStats);


        return $this->render('fortnite_stats/index.html.twig', [
            'controller_name' => 'FortniteStatsController',
        ]);
    }
}
