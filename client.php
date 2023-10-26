<?php

require 'vendor/autoload.php';  // Load Guzzle

class Client {

    public static function request($method, $apiEndPoint, $data = null) {

        try {

            /**
             * Define Auth Token
             */
            $authToken = '4f768e19bf2f699bb7380feac71f6130';
            /**
             * Create a Guzzle client
             */
            $client = new GuzzleHttp\Client();

            /**
             * Request headers
             */
            $headers = [
                'Authorization' => "Bearer ". $authToken,
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json'
            ];

            /**
             * Make a request
             */
            return $client->request($method, $apiEndPoint, [
                'headers' => $headers,
                'json' => $data,
            ]);

        } catch (Throwable $e) {
            die(var_export($e->getMessage()));
        }
    }
}