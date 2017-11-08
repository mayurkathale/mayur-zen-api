<?php
namespace TwitterBundle\Helper;

use FOS\RestBundle\Controller\FOSRestController;
use Abraham\TwitterOAuth\TwitterOAuth;

class BaseRestController extends FOSRestController
{
    private $callback_url = 'http://mayurkathale.com';
    private $consumer_key = "WWboRCBkqoRDqwPE26hAAe3pt";
    private $consumer_secret = "Lvo8bbnTbeMh6tV7coXBzoIKLd3imGZ5MbDwQiSLJOOq7mty6V";
    protected $data = array();

    protected function _auth() {
        $connection = new TwitterOAuth($this->consumer_key, $this->consumer_secret);
        $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => $this->callback_url));
        $request_token['oauth_token_secret'];
        return array('auth_token' => $request_token['oauth_token'], 'auth_token_secret' => $request_token['oauth_token_secret']);
    }

    protected function _getAccessToken($auth_token, $auth_token_secret, $oauth_verifier) {
        $connection = new TwitterOAuth($this->consumer_key, $this->consumer_secret, $auth_token, $auth_token_secret);
        return $connection->oauth("oauth/access_token", ["oauth_verifier" => $oauth_verifier]);
    }


    protected function _getUserData($auth_token, $auth_token_secret) {
        $connection = new TwitterOAuth($this->consumer_key, $this->consumer_secret, $auth_token, $auth_token_secret);
        return json_encode($connection->get("account/verify_credentials"));
    }

    public function _searchTweets($auth_token, $auth_token_secret, $q, $latlong, $count) {
        $connection = new TwitterOAuth($this->consumer_key, $this->consumer_secret, $auth_token, $auth_token_secret);
        return json_encode($connection->get("search/tweets", ["q" => $q, "geocode"=> $latlong, "count" => $count]));
    }

    public function _getHistory() {
        return array();
    }

    public function _saveHistory() {
        return array();
    }
}