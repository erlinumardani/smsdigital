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
            if (password_verify($inputdata['password'], $user_data[0]['password_api'])) {
                $user_data[0]['logged_in'] = TRUE;
                $user_data[0]['password'] = NULL;
                $user_data[0]['password_api'] = NULL;
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
                    $error = 0;
                    $message='Messages Send Failed';
                    foreach ($inputdata['message'] as $data) {

                        if(substr($data['phone'],0,1) == "0"){
                            $data['phone'] = substr_replace($data['phone'],"62",0,1);
                        }
                        if(strlen($data['phone']) > 14){
                            $error+=1;
                            $message="INVALID_MSISDN";
                        }
                        if(substr($data['phone'],0,3) != '628' && substr($data['phone'],0,4) != '+628'){
                            $error+=1;
                            $message="PREFIX_NOT_EXIST";
                        }
                        if(preg_match ("/[^0-9+]/", $data['phone'])){
                            $error+=1;
                            $message="PHONE_MUST_BE_NUMBER";
                        }
                        if(strlen($data['schedule']) < 2){
                            $error+=1;
                            $message="SCHEDULE_MUST_NOT_EMPTY";
                        }
                        if(strlen($data['guid']) < 2){
                            $error+=1;
                            $message="GUID_MUST_NOT_EMPTY";
                        }
                        if(strlen($data['content']) < 2){
                            $error+=1;
                            $message="CONTENT_MUST_NOT_EMPTY";
                        }
                        if(strlen($data['content']) > 160){
                            $error+=1;
                            $message="CONTENT_MUST_LESS_THAN_160";
                        }
                        if(strlen($data['phone']) < 2){
                            $error+=1;
                            $message="PHONE_MUST_NOT_EMPTY";
                        }

                        $data['phone'] = str_replace("+","",$data['phone']);

                        $data_api['message'][$i]['content'] = $data['content']; 
                        $data_api['message'][$i]['phone'] = $data['phone']; 
                        $data_api['message'][$i]['schedule'] = $data['schedule'];
                        $data_api['message'][$i]['uid'] = $data['guid']; 
                        $uid[$i] = $max_id;
                        $i++;
                        $max_id++;
                    }

                    if($error==0){

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
                            $this->set_response(array("status"=>"failed","messages"=>"Failed"), REST_Controller::HTTP_OK);
                            return;
                        }     

                    }else{
                        $this->set_response(array("status"=>"failed","messages"=>$message), REST_Controller::HTTP_OK);
                        return;
                    }
                }else{
                    $this->set_response(array("status"=>"failed","messages"=>'Limit Exceeded'), REST_Controller::HTTP_OK);
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

		$uri = 'https://smsturbo.infomedia.co.id/HERMES.1/Service/TokenRequest';

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

		$token = $this->token_read();
		$this->load->library('curl');

		$uri = 'https://smsturbo.infomedia.co.id/HERMES.1/Message/restSaveSend';

		// Start session (also wipes existing/previous sessions)
		$this->curl->create($uri);

		// More human looking options
		$this->curl->option('buffersize', 10);

		if(isset($token)){
			// Header
			$this->curl->http_header('Authorization', 'Bearer '.$token);
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

			$total = $this->db->select("count(id) as total")->get_where('sms_transactions','month(created_at) = month(now()) and status in("RECEIVED","SENDING","SENT","QUEING") and updated_by = "'.$user_id.'"')->row()->total;
			$limit = $this->db->select("sms_limit")->get_where('users',array("id"=>$user_id))->row()->sms_limit;

			return $limit-$total;

		}elseif($role_id==2){

			$total = $this->db->select("count(id) as total")->get_where('sms_transactions','month(created_at) = month(now()) and status in("RECEIVED","SENDING","SENT","QUEING") and tenant_id = "'.$tenant_id.'"')->row()->total;
			$limit = $this->db->select("sms_limit")->get_where('users',array("id"=>$user_id))->row()->sms_limit;
			$clients_limit = $this->db->select("sum(sms_limit) as sms_limit")->get_where('users','tenant_id = '.$this->tenant_id.' and id !='.$user_id)->row()->sms_limit;

			return $limit-$total-$clients_limit;

		}else{

			return 0;

		}

    }
    
    public function sendsms_json_post()
    {
        $headers = $this->input->request_headers();
        $data =  json_decode(file_get_contents('php://input'),true);
        
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
            //TODO: Change 'token_timeout' in application\config\jwt.php
            $decodedToken = AUTHORIZATION::validateTimestamp($headers['Authorization']);

            // return response if token is valid
            if ($decodedToken != false) {

                $max_id = (int)$this->db->select("max(id) as id")->get('sms_transactions')->row()->id+1;
                $reason = '';

                if($this->limit_counter($decodedToken->data->role_id,$decodedToken->data->user_id)>1){

                    $i = 0;
                    $error = 0;
                    $message=array();

                    if(substr($data['phone'],0,1) == "0"){
                        $data['phone'] = substr_replace($data['phone'],"62",0,1);
                    }
                    if(strlen($data['phone']) > 14){
                        $error+=1;
                        array_push($message,"INVALID_MSISDN");
                    }
                    if(substr($data['phone'],0,3) != '628' && substr($data['phone'],0,4) != '+628'){
                        $error+=1;
                        array_push($message,"PREFIX_NOT_EXIST");
                    }
                    if(preg_match ("/[^0-9+]/", $data['phone'])){
                        $error+=1;
                        array_push($message,"PHONE_MUST_BE_NUMBER");
                    }
                    if(strlen($data['schedule']) < 2){
                        $error+=1;
                        array_push($message,"SCHEDULE_MUST_NOT_EMPTY");
                    }
                    if(strlen($data['msgid']) < 2){
                        $error+=1;
                        array_push($message,"MSGID_MUST_NOT_EMPTY");
                    }
                    if(strlen($data['content']) < 2){
                        $error+=1;
                        array_push($message,"CONTENT_MUST_NOT_EMPTY");
                    }
                    if(strlen($data['content']) > 160){
                        $error+=1;
                        array_push($message,"CONTENT_MUST_LESS_THAN_160");
                    }
                    if(strlen($data['phone']) < 2){
                        $error+=1;
                        array_push($message,"PHONE_MUST_NOT_EMPTY");
                    }

                    $data['phone'] = str_replace("+","",$data['phone']);

                    $data_api['message'][0]['content'] = $data['content']; 
                    $data_api['message'][0]['phone'] = $data['phone']; 
                    $data_api['message'][0]['schedule'] = $data['schedule'];
                    $data_api['message'][0]['uid'] = $data['msgid']; 
                    $uid = $data['msgid'];

                    if($error==0){

                        $draft = isset($data['draft'])?$data['schedule']:false;

                        if($draft == true){

                            $this->db->trans_start();
    
                            if(substr($data['phone'],0,1) == "0"){
                                $data['phone'] = substr_replace($data['phone'],"62",0,1);
                            }

                            $this->db->insert('sms_transactions',array(
                                'type' => "API",
                                'msisdn' => $data['phone'],
                                'message' => $data['content'],
                                'schedule' => $data['schedule'],
                                'guid' => $data['msgid'],
                                'status' => 'DRAFT',
                                'tenant_id' => $decodedToken->data->tenant_id,
                                'updated_by'  => $decodedToken->data->user_id
                            ));

                            $this->db->trans_complete();

                            $this->set_response(array_merge(array("code"=>true,"status"=>'DRAFT'),array('msgid'=>$uid)), REST_Controller::HTTP_OK);
                            return;

                        }else{

                            $status = $this->api_sendsms($data_api);

                            if($status->success==true){

                                if(count($status->data->fail)>0){

                                    if(substr($data['phone'],0,1) == "0"){
                                        $data['phone'] = substr_replace($data['phone'],"62",0,1);
                                    }

                                    switch ($status->data->fail[0]->$data['msgid']) {
                                        case '007001':
                                            $reason = "INVALID_SCHEDULE_DATETIME_FORMAT";
                                            break;
                                        case '007002':
                                            $reason = "INVALID_MSISDN";
                                            break;
                                        case '007003':
                                            $reason = "NO_CREDIT_AVAILABLE";
                                            break;
                                        default:
                                            $reason = $status->data->fail[0]->$data['msgid'];
                                            break;
                                    }

                                    $this->db->insert('sms_transactions',array(
                                        'type' => "API",
                                        'msisdn' => $data['phone'],
                                        'message' => $data['content'],
                                        'schedule' => $data['schedule'],
                                        'guid' => $data['msgid'],
                                        'status' => 'FAILED',
                                        'reason' => $reason,
                                        'tenant_id' => $decodedToken->data->tenant_id,
                                        'updated_by'  => $decodedToken->data->user_id
                                    ));

                                    $this->set_response(array("code"=>false,"err_desc"=>$reason), REST_Controller::HTTP_OK);
                                    return;

                                }else{
                                
                                    $this->db->trans_start();

                                    if(substr($data['phone'],0,1) == "0"){
                                        $data['phone'] = substr_replace($data['phone'],"62",0,1);
                                    }

                                    $this->db->insert('sms_transactions',array(
                                        'type' => "API",
                                        'msisdn' => $data['phone'],
                                        'message' => $data['content'],
                                        'schedule' => $data['schedule'],
                                        'guid' => $data['msgid'],
                                        'tenant_id' => $decodedToken->data->tenant_id,
                                        'updated_by'  => $decodedToken->data->user_id
                                    ));

                                    $this->db->trans_complete();

                                    $this->set_response(array_merge(array("code"=>true,"status"=>'SENDING'),array('msgid'=>$uid)), REST_Controller::HTTP_OK);
                                    return;
                                }

                            }else{

                                if(substr($data['phone'],0,1) == "0"){
                                    $data['phone'] = substr_replace($data['phone'],"62",0,1);
                                }

                                $this->db->insert('sms_transactions',array(
                                    'type' => "API",
                                    'msisdn' => $data['phone'],
                                    'message' => $data['content'],
                                    'schedule' => $data['schedule'],
                                    'guid' => $data['msgid'],
                                    'status' => 'FAILED',
                                    'reason' => 'INVALID_INPUT_PARAMETER',
                                    'tenant_id' => $decodedToken->data->tenant_id,
                                    'updated_by'  => $decodedToken->data->user_id
                                ));

                                $this->set_response(array("code"=>false,"err_desc"=>$message), REST_Controller::HTTP_OK);
                                return;

                            }
                        }

                    }else{
                        $this->set_response(array("code"=>false,"err_desc"=>$message), REST_Controller::HTTP_OK);
                        return;
                    }
                }else{
                    $this->set_response(array("code"=>false,"err_desc"=>'LIMIT_EXCEEDED'), REST_Controller::HTTP_OK);
                    return;
                }
            }else{
                $this->set_response(array("code"=>false,"err_desc"=>"UNAUTHORISED"), REST_Controller::HTTP_UNAUTHORIZED);
            }
        }else{
            $this->set_response(array("code"=>false,"err_desc"=>"UNAUTHORISED"), REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    public function sendsms_txt_post()
    {
        $headers = $this->input->request_headers();
        $data =  json_decode(file_get_contents('php://input'),true);
        
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
            //TODO: Change 'token_timeout' in application\config\jwt.php
            $decodedToken = AUTHORIZATION::validateTimestamp($headers['Authorization']);

            // return response if token is valid
            if ($decodedToken != false) {

                $max_id = (int)$this->db->select("max(id) as id")->get('sms_transactions')->row()->id+1;
                $reason = '';

                if($this->limit_counter($decodedToken->data->role_id,$decodedToken->data->user_id)>1){

                    $i = 0;
                    $error = 0;
                    $message=array();

                    if(substr($data['phone'],0,1) == "0"){
                        $data['phone'] = substr_replace($data['phone'],"62",0,1);
                    }
                    if(strlen($data['phone']) > 14){
                        $error+=1;
                        array_push($message,"INVALID_MSISDN");
                    }
                    if(substr($data['phone'],0,3) != '628' && substr($data['phone'],0,4) != '+628'){
                        $error+=1;
                        array_push($message,"PREFIX_NOT_EXIST");
                    }
                    if(preg_match ("/[^0-9+]/", $data['phone'])){
                        $error+=1;
                        array_push($message,"PHONE_MUST_BE_NUMBER");
                    }
                    if(strlen($data['schedule']) < 2){
                        $error+=1;
                        array_push($message,"SCHEDULE_MUST_NOT_EMPTY");
                    }
                    if(strlen($data['msgid']) < 2){
                        $error+=1;
                        array_push($message,"MSGID_MUST_NOT_EMPTY");
                    }
                    if(strlen($data['content']) < 2){
                        $error+=1;
                        array_push($message,"CONTENT_MUST_NOT_EMPTY");
                    }
                    if(strlen($data['content']) > 160){
                        $error+=1;
                        array_push($message,"CONTENT_MUST_LESS_THAN_160");
                    }
                    if(strlen($data['phone']) < 2){
                        $error+=1;
                        array_push($message,"PHONE_MUST_NOT_EMPTY");
                    }

                    $data['phone'] = str_replace("+","",$data['phone']);

                    $data_api['message'][0]['content'] = $data['content']; 
                    $data_api['message'][0]['phone'] = $data['phone']; 
                    $data_api['message'][0]['schedule'] = $data['schedule'];
                    $data_api['message'][0]['uid'] = $data['msgid']; 
                    $uid = $data['msgid'];

                    if($error==0){

                        $draft = isset($data['draft'])?$data['schedule']:false;

                        if($draft == true){

                            $this->db->trans_start();
    
                            if(substr($data['phone'],0,1) == "0"){
                                $data['phone'] = substr_replace($data['phone'],"62",0,1);
                            }

                            $this->db->insert('sms_transactions',array(
                                'type' => "API",
                                'msisdn' => $data['phone'],
                                'message' => $data['content'],
                                'schedule' => $data['schedule'],
                                'guid' => $data['msgid'],
                                'status' => 'DRAFT',
                                'tenant_id' => $decodedToken->data->tenant_id,
                                'updated_by'  => $decodedToken->data->user_id
                            ));

                            $this->db->trans_complete();

                            echo "SUCCESS|DRAFT|".$uid;
                            return;

                        }else{

                            $status = $this->api_sendsms($data_api);

                            if($status->success==true){

                                if(count($status->data->fail)>0){
    
                                    if(substr($data['phone'],0,1) == "0"){
                                        $data['phone'] = substr_replace($data['phone'],"62",0,1);
                                    }
    
                                    switch ($status->data->fail[0]->$data['msgid']) {
                                        case '007001':
                                            $reason = "INVALID_SCHEDULE_DATETIME_FORMAT";
                                            break;
                                        case '007002':
                                            $reason = "INVALID_MSISDN";
                                            break;
                                        case '007003':
                                            $reason = "NO_CREDIT_AVAILABLE";
                                            break;
                                        default:
                                            $reason = $status->data->fail[0]->$data['msgid'];
                                            break;
                                    }
    
                                    $this->db->insert('sms_transactions',array(
                                        'type' => "API",
                                        'msisdn' => $data['phone'],
                                        'message' => $data['content'],
                                        'schedule' => $data['schedule'],
                                        'guid' => $data['msgid'],
                                        'status' => 'FAILED',
                                        'reason' => $reason,
                                        'tenant_id' => $decodedToken->data->tenant_id,
                                        'updated_by'  => $decodedToken->data->user_id
                                    ));
    
                                    echo "FAILED|".json_encode($reason);
    
                                }else{
                                
                                    $this->db->trans_start();
    
                                    if(substr($data['phone'],0,1) == "0"){
                                        $data['phone'] = substr_replace($data['phone'],"62",0,1);
                                    }
    
                                    $this->db->insert('sms_transactions',array(
                                        'type' => "API",
                                        'msisdn' => $data['phone'],
                                        'message' => $data['content'],
                                        'schedule' => $data['schedule'],
                                        'guid' => $data['msgid'],
                                        'tenant_id' => $decodedToken->data->tenant_id,
                                        'updated_by'  => $decodedToken->data->user_id
                                    ));
    
                                    $this->db->trans_complete();
    
                                    echo "SUCCESS|SENDING|".$uid;
                                    return;
                                }
    
                            }else{
    
                                if(substr($data['phone'],0,1) == "0"){
                                    $data['phone'] = substr_replace($data['phone'],"62",0,1);
                                }
    
                                $this->db->insert('sms_transactions',array(
                                    'type' => "API",
                                    'msisdn' => $data['phone'],
                                    'message' => $data['content'],
                                    'schedule' => $data['schedule'],
                                    'guid' => $data['msgid'],
                                    'status' => 'FAILED',
                                    'reason' => 'INVALID_INPUT_PARAMETER',
                                    'tenant_id' => $decodedToken->data->tenant_id,
                                    'updated_by'  => $decodedToken->data->user_id
                                ));
    
                                echo "FAILED|".json_encode($message);
                                return;
                            }

                        }

                    }else{
                        echo "FAILED|".json_encode($message);
                        return;
                    }
                }else{
                    echo "FAILED|LIMIT_EXCEEDED";
                    return;
                }
            }else{
                echo "FAILED|UNAUTHORISED";
            }
        }else{
            echo "FAILED|UNAUTHORISED";
        }
    }

    public function sendinglist_post()
    {
        $headers = $this->input->request_headers();
        $inputdata =  json_decode(file_get_contents('php://input'),true);
        
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
            //TODO: Change 'token_timeout' in application\config\jwt.php
            $decodedToken = AUTHORIZATION::validateTimestamp($headers['Authorization']);

            // return response if token is valid
            if ($decodedToken != false) {

                if(isset($inputdata['limit'])){
                    $limit = $inputdata['limit'];
                }else{
                    $limit = 0;
                }

                $result = array();

                if(isset($inputdata['status'])){
                    $data = $this->db->select('guid as uid, msisdn, message, schedule')
                    ->from('sms_transactions')
                    ->where('status',$inputdata['status'])
                    ->where('schedule < now()')
                    ->order_by('schedule','asc')
                    ->limit($limit)
                    ->get()->result();
                }else{
                    $data = $this->db->select('guid as uid, msisdn, message, schedule')
                    ->from('sms_transactions')
                    ->where('status','SENDING')
                    ->or_where('status','QUEING')
                    ->where('schedule < now()')
                    ->order_by('rand()')
                    ->limit($limit)
                    ->get()->result();
                }

                foreach ($data as $value) {
                    array_push($result,$value->uid);
                }

                $this->set_response(array("success"=>true,"data"=>array("uid"=>$result,"detail"=>$data)), REST_Controller::HTTP_OK);
                    
            }else{
                $this->set_response(array("success"=>false,"messages"=>"Unauthorised"), REST_Controller::HTTP_UNAUTHORIZED);
            }
        }else{
            $this->set_response(array("success"=>false,"messages"=>"Unauthorised"), REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    public function updatestatus_post()
    {
        $headers = $this->input->request_headers();
        $inputdata =  json_decode(file_get_contents('php://input'),true);
        $result = array();
        
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
            //TODO: Change 'token_timeout' in application\config\jwt.php
            $decodedToken = AUTHORIZATION::validateTimestamp($headers['Authorization']);

            // return response if token is valid
            if ($decodedToken != false) {

                if(isset($inputdata['data'])){
                    foreach ($inputdata['data'] as $input) {

                        if($this->db->select('count(*) as total')->get_where('sms_transactions',array("guid"=>$input["uid"]))->row()->total > 0){
                            if($input["status"]=="RECEIVED"){
                                $this->db->where('guid',$input["uid"])->update('sms_transactions',array('status'=>'RECEIVED'));
                                array_push($result,array("uid"=>$input["uid"],"status"=>"RECEIVED"));
                            }else{
                                $this->db->where('guid',$input["uid"])->update('sms_transactions',array('status'=>$input["status"],'reason'=>$input["reason"]));
                                array_push($result,array("uid"=>$input["uid"],"status"=>$input["status"]));
                            }
                        }else{
                            array_push($result,array("uid"=>$input["uid"],"status"=>"UID NOT FOUND"));
                        }
                        //array_push($result,$input["uid"]);
                    }
                }else{
                    $result = array();
                }

               /*  $result = array();

                $data = $this->db->select('guid as uid')
                ->from('sms_transactions')
                ->where('status','SENDING')
                ->or_where('status','QUEING')
                ->where('schedule < now()')
                ->limit($limit)
                ->get()->result();

                foreach ($data as $value) {
                    array_push($result,$value->uid);
                } */

                $this->set_response(array("success"=>true,"data"=>$result), REST_Controller::HTTP_OK);
                    
            }else{
                $this->set_response(array("success"=>false,"messages"=>"Unauthorised"), REST_Controller::HTTP_UNAUTHORIZED);
            }
        }else{
            $this->set_response(array("success"=>false,"messages"=>"Unauthorised"), REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    function token_refresh_get(){

		$token_file = fopen("assets/docs/token.txt", "w") or die("Unable to open file!");
		$token = $this->api_get_token()->data->token;
		fwrite($token_file, $token);
		fclose($token_file);
		
		echo $token;
	}
	
	function token_read(){

		$token_file = fopen("assets/docs/token.txt", "r") or die("Unable to open file!");
		$token = fread($token_file,filesize("assets/docs/token.txt"));
		fclose($token_file);

		return $token;
		
    }
    
    function token_read_get(){

		$token_file = fopen("assets/docs/token.txt", "r") or die("Unable to open file!");
		$token = fread($token_file,filesize("assets/docs/token.txt"));
		fclose($token_file);

		echo $token;
		
	}

}