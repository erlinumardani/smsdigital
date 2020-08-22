<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

/*
 * Changes:
 * 1. This project contains .htaccess file for windows machine.
 *    Please update as per your requirements.
 *    Samples (Win/Linux): http://stackoverflow.com/questions/28525870/removing-index-php-from-url-in-codeigniter-on-mandriva
 *
 * 2. Change 'encryption_key' in application\config\config.php
 *    Link for encryption_key: http://jeffreybarke.net/tools/codeigniter-encryption-key-generator/
 * 
 * 3. Change 'jwt_key' in application\config\jwt.php
 * 3. Change 'token_timeout' in application\config\jwt.php
 *
 */

class Api extends REST_Controller
{
    /**
     * URL: http://localhost/CodeIgniter-JWT-Sample/authtimeout/token
     * Method: GET
     */
    public function auth_post()
    {
        $inputdata =  json_decode(file_get_contents('php://input'),true);
        $tokenData = array();

        /* Date helper
        * https://www.codeigniter.com/user_guide/helpers/date_helper.html
        * Added helper "date" in application\config\autoload.php line 92
        * Notice - 'timestamp' is part of $tokenData
        */
        $tokenData['timestamp'] = time();
        $tokenData['data'] = $inputdata; //TODO: Replace with data for token

        $output['data'] = $inputdata;
        $output['token'] = AUTHORIZATION::generateToken($tokenData);

        $this->set_response($output, REST_Controller::HTTP_OK);
    }

    /**
     * URL: http://localhost/CodeIgniter-JWT-Sample/authtimeout/token
     * Method: POST
     * Header Key: Authorization
     * Value: Auth token generated in GET call
     */
    public function sendsms_post()
    {
        $headers = $this->input->request_headers();
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
            //TODO: Change 'token_timeout' in application\config\jwt.php
            $decodedToken = AUTHORIZATION::validateTimestamp($headers['Authorization']);

            // return response if token is valid
            if ($decodedToken != false) {
                $this->set_response($decodedToken, REST_Controller::HTTP_OK);
                return;
            }
        }

        $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
    }
}