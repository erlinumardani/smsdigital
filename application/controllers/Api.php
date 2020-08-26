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

        $this->db->select('*');
        $user_data = $this->db->select('
            users.id AS user_id,
            users.*,
            persons.*')
        ->join('persons','persons.id = users.person_id')
        ->get_where('users', array('username' => $inputdata['username']))
        ->result_array();

        if(isset($user_data[0]['username'])){
            if (password_verify($inputdata['password'], $user_data[0]['password'])) {
                $user_data[0]['logged_in'] = TRUE;
                $user_data[0]['password'] = NULL;
                //$this->session->set_userdata($user_data[0]);
                $tokenData['timestamp'] = time();
                $tokenData['data'] = $user_data[0];

                //$output['data'] = $inputdata;
                $output['token'] = AUTHORIZATION::generateToken($tokenData);

                $this->set_response(array_merge(array("status"=>"success"),$output), REST_Controller::HTTP_OK);
            }else{
                $this->set_response(array("status"=>"failed","messages"=>'Wrong Password'), REST_Controller::HTTP_OK);
            }
        }else{
            $this->set_response(array("status"=>"failed","messages"=>'Wrong Username/Password'), REST_Controller::HTTP_OK);
        }

    }

    public function sendsms_post()
    {
        $headers = $this->input->request_headers();
        $inputdata =  json_decode(file_get_contents('php://input'),true);
        
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
            //TODO: Change 'token_timeout' in application\config\jwt.php
            $decodedToken = AUTHORIZATION::validateTimestamp($headers['Authorization']);

            // return response if token is valid
            if ($decodedToken != false) {

                $max_id = (int)$this->db->select("max(id) as id")->get('sms_transactions')->row()->id+1;

                if($this->limit_counter($decodedToken->data->role_id,$decodedToken->data->user_id)>count($inputdata['message'])){

                    $i = 0;
                    foreach ($inputdata['message'] as $data) {

                        if(substr($data['phone'],0,1) == "0"){
                            $data['phone'] = substr_replace($data['phone'],"62",0,1);
                        }

                        $data_api['message'][$i]['content'] = $data['content']; 
                        $data_api['message'][$i]['phone'] = $data['phone']; 
                        $data_api['message'][$i]['schedule'] = $data['schedule'];
                        $data_api['message'][$i]['uid'] = $data['guid']; 
                        $uid[$i] = $max_id;
                        $i++;
                        $max_id++;
                    }

                    if($this->api_sendsms($data_api)->success==true){

                        $this->db->trans_start();
                    
                        foreach ($inputdata['message'] as $data) {

                            if(substr($data['phone'],0,1) == "0"){
                                $data['phone'] = substr_replace($data['phone'],"62",0,1);
                            }

                            $this->db->insert('sms_transactions',array(
                                'type' => "API",
                                'msisdn' => $data['phone'],
                                'message' => $data['content'],
                                'schedule' => $data['schedule'],
                                'guid' => $data['guid'],
                                'tenant_id' => $decodedToken->data->tenant_id,
                                'updated_by'  => $decodedToken->data->user_id
                            ));
                        }

                        $this->db->trans_complete();

                        $this->set_response(array_merge(array("status"=>"success","messages"=>'Sending Messages'),array('uid'=>$uid)), REST_Controller::HTTP_OK);
                        return;

                    }else{
                        $this->set_response(array("status"=>"failed","messages"=>'Messages Send Failed'), REST_Controller::HTTP_OK);
                        return;
                    }
                }else{
                    $this->set_response(array("status"=>"failed","messages"=>'Wrong Password'), REST_Controller::HTTP_OK);
                    return;
                }
            }else{
                $this->set_response(array("status"=>"failed","messages"=>"Unauthorised"), REST_Controller::HTTP_UNAUTHORIZED);
            }
        }else{
            $this->set_response(array("status"=>"failed","messages"=>"Unauthorised"), REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    public function checksms_post()
    {
        $headers = $this->input->request_headers();
        $inputdata =  json_decode(file_get_contents('php://input'),true);

        if(isset($inputdata['guid'])){
            $guid = $inputdata['guid'];
        }else{
            $guid = '';
        }

        if(isset($inputdata['uid'])){
            $uid = $inputdata['uid'];
        }else{
            $uid = '';
        }
        
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
            //TODO: Change 'token_timeout' in application\config\jwt.php
            $decodedToken = AUTHORIZATION::validateTimestamp($headers['Authorization']);

            // return response if token is valid
            if ($decodedToken != false) {

                $data = $this->db->select('id as uid, guid, status')->get_where('sms_transactions','id = "'.$uid.'" or guid = "'.$guid.'"')->result();

                if(count($data)>=1){
                    $this->set_response(array("status"=>"success","data"=>$data), REST_Controller::HTTP_OK);
                    return;
                }else{
                    $this->set_response(array("status"=>"failed","messages"=>"No Data Found"), REST_Controller::HTTP_OK);
                }

            }else{
                $this->set_response(array("status"=>"failed","messages"=>"Unauthorised"), REST_Controller::HTTP_UNAUTHORIZED);
            }
        }else{
            $this->set_response(array("status"=>"failed","messages"=>"Unauthorised"), REST_Controller::HTTP_UNAUTHORIZED);
        }
    }
    
    public function api_get_token(){

		$this->load->library('curl');

		$uri = 'https://smsturbo.infomedia.co.id:8106/HERMES.1/Service/TokenRequest';

		// Start session (also wipes existing/previous sessions)
		$this->curl->create($uri);

		// More human looking options
		$this->curl->option('buffersize', 10);

		// Header
		$this->curl->http_header('Content-Type', 'application/json');

		// Post - If you do not use post, it will just run a GET request
		$post = json_encode(array(
			'username'=>'sms-go',
			'password'=>'infonus@!@#'
		));

		$this->curl->post($post);

		$result = $this->curl->execute();

		$this->db->insert('sms_curl_log',array(
			'uri'=>$uri,
			'method'=>'POST',
			'params'=>$post,
			'response'=>json_encode(json_decode($result))
		));

		// Execute - returns responce
		return json_decode($result);

	}

	public function api_sendsms($data){

		$token = $this->api_get_token();
		$this->load->library('curl');

		$uri = 'https://smsturbo.infomedia.co.id:8106/HERMES.1/Message/restSaveSend';

		// Start session (also wipes existing/previous sessions)
		$this->curl->create($uri);

		// More human looking options
		$this->curl->option('buffersize', 10);

		if($token->success==true){
			// Header
			$this->curl->http_header('Authorization', 'Bearer '.$token->data->token);
			$this->curl->http_header('Content-Type', 'application/json');

			// Post - If you do not use post, it will just run a GET request
			$post = json_encode($data);
			$this->curl->post($post);

			$result = $this->curl->execute();

			$this->db->insert('sms_curl_log',array(
				'uri'=>$uri,
				'method'=>'POST',
				'params'=>$post,
				'response'=>$result
			));

			// Execute - returns responce
			return json_decode($result);
		}else{
			return json_decode(array("success"=>false));
		}
		
    }
    
    function limit_counter($role_id,$user_id){

		if($role_id==3){

			$total = $this->db->select("count(id) as total")->get_where('sms_transactions',array("updated_by"=>$user_id))->row()->total;
			$limit = $this->db->select("sms_limit")->get_where('users',array("id"=>$user_id))->row()->sms_limit;

			return $limit-$total;

		}elseif($role_id==2){

			$total = $this->db->select("count(id) as total")->get_where('sms_transactions',array("tenant_id"=>$this->tenant_id))->row()->total;
			$limit = $this->db->select("sms_limit")->get_where('users',array("id"=>$user_id))->row()->sms_limit;
			$clients_limit = $this->db->select("sum(sms_limit) as sms_limit")->get_where('users','tenant_id = '.$this->tenant_id.' and id !='.$user_id)->row()->sms_limit;

			return $limit-$total-$clients_limit;

		}else{

			return 0;

		}

	}
}