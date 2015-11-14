<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use GuzzleHttp;
use Session;

class SongController extends Controller
{
    //This going to list all songs you have access to.
    public function allSongs()
    {
        echo Session::get('groups');
    }

    public function redirectUrl()
    {
       echo "hej hej";
    }

    public function login()
    {
        $provider = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId' => env('OATH2_CLIENT_ID', ''),    // The client ID assigned to you by the provider
            'clientSecret' => env('OATH2_SECRET', ''),   // The client password assigned to you by the provider
            'redirectUri' => env('OATH2_REDIRECT_URI', ''),
            'urlAuthorize' => 'http://korriban.chalmers.it/oauth/authorize',
            'urlAccessToken' => 'http://korriban.chalmers.it/oauth/token',
            'urlResourceOwnerDetails' => 'http://brentertainment.com/oauth2/lockdin/resource'
        ]);

        // If we don't have an authorization code then get one
        if (!isset($_GET['code'])) {
            $authorizationUrl = $provider->getAuthorizationUrl();
            header('Location: ' . $authorizationUrl);
            exit;
        } elseif (empty($_GET['state']) ){//|| ($_GET['state'] !== $_SESSION['oauth2state'])) {
            exit('Invalid state');

        } else {

            try {

                // Try to get an access token using the authorization code grant.
                $accessToken = $provider->getAccessToken('authorization_code', [
                    'code' => $_GET['code']
                ]);
                //use gruzzle
                $client = new GuzzleHttp\Client();
                $res = $client->request('GET', 'http://korriban.chalmers.it/me.json?access_token='.$accessToken->getToken());
                $user= json_decode($res->getBody());
                Session::put('groups', json_encode($user->groups));
                echo Session::get('groups');

            } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

                // Failed to get the access token or user details.
                exit($e->getMessage());

            }

        }
    }
}
