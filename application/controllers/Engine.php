<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Engine extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$data = array(
			"base_url" => base_url(),
			"captcha" => $this->gen_captcha(),
			"csrf_token_name" => $this->security->get_csrf_token_name(),
			"csrf_hash" => $this->security->get_csrf_hash()
		);

		$configs = array();
		foreach($this->db->get_where('configs')->result_array() as $config){
			$configs[$config['name']] = $config['value'];
		}

		$this->parser->parse('auth',array_merge($data,$configs));
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
		/* $post = json_encode(array(
			'username'=>'dukcapil',
			'password'=>'dukc@p1LsMs'
		)); */
		$post = json_encode(array(
			'username'=>'sms-go',
			'password'=>'infonus@!@#'
		));

		$this->curl->post($post);

		$result = $this->curl->execute();

		/* $this->db->insert('sms_curl_log',array(
			'uri'=>$uri,
			'method'=>'POST',
			'params'=>$post,
			'response'=>json_encode(json_decode($result))
		)); */

		// Execute - returns responce
		return json_decode($result);

	}

	public function api_delivery_check($uid){

		$token = $this->api_get_token();
		$this->load->library('curl');

		$uri = 'https://smsturbo.infomedia.co.id/HERMES.1/Message/restCheckDelivery';

		// Start session (also wipes existing/previous sessions)
		$this->curl->create($uri);

		// More human looking options
		$this->curl->option('buffersize', 10);

		if($token->success==true){
			// Header
			$this->curl->http_header('Authorization', 'Bearer '.$token->data->token);
			$this->curl->http_header('Content-Type', 'application/json');

			// Post - If you do not use post, it will just run a GET request
			$post = json_encode(array(
				'uid'=>$uid
			));
			$this->curl->post($post);

			$result = $this->curl->execute();

			/* $this->db->insert('sms_curl_log',array(
				'uri'=>$uri,
				'method'=>'POST',
				'params'=>$post,
				'response'=>$result
			)); */

			// Execute - returns responce
			return json_decode($result);
		}else{
			return json_decode(array("success"=>false));
		}
		
	}

	public function api_send_check($uid){

		$token = $this->api_get_token();
		$this->load->library('curl');

		$uri = 'https://smsturbo.infomedia.co.id/HERMES.1/Message/restCheckSend';

		// Start session (also wipes existing/previous sessions)
		$this->curl->create($uri);

		// More human looking options
		$this->curl->option('buffersize', 10);

		if($token->success==true){
			// Header
			$this->curl->http_header('Authorization', 'Bearer '.$token->data->token);
			$this->curl->http_header('Content-Type', 'application/json');

			// Post - If you do not use post, it will just run a GET request
			$post = json_encode(array(
				'uid'=>array($uid)
			));
			$this->curl->post($post);

			$result = $this->curl->execute();

			/* $this->db->insert('sms_curl_log',array(
				'uri'=>$uri,
				'method'=>'POST',
				'params'=>$post,
				'response'=>$result
			)); */

			// Execute - returns responce
			return json_decode($result);
		}else{
			return json_decode(array("success"=>false));
		}
		
	}

	public function get_status(){
		$data = $this->db->select('id,concat("smsd-",id) as uid, guid, status')
		->from('sms_transactions')
		->where('status','SENDING')
		->or_where('status','QUEING')
		->where('schedule < now()')
		->get()->result();

		foreach ($data as $value) {
			$guid = $value->guid;
			$state = $value->status;
			$status = $this->api_send_check($guid);
			$status2 = $this->api_delivery_check($guid);

			if($status->success == true){
				if($status2->success == true){
					$this->db->where('id',$value->id)->update('sms_transactions',array('status'=>$status2->data[0]->state));
				}else{
					switch ($status2->error[0]) {
						case '006001':
							$reason = "TOKEN EMPTY";
							break;
						case '006002':
							$reason = "FALSE TOKEN";
							break;
						case '006003':
							$reason = "TOKEN DATA EMPTY";
							break;
						case '007001':
							$reason = "EMPTY TOKEN";
							break;
						case '007002':
							$reason = "MSGID NOT FOUND";
							break;
						case '007003':
							$reason = "MSGID NOT FOUND";
							break;
						case '007004':
							$reason = "GATEWAY NOT FOUND";
							break;
						case '007005':
							$reason = "GATEWAY DATA EMPTY";
							break;
						case '008005':
							$reason = "FALSE GET DATA FROM GATEWAY";
							break;
						default:
							$reason = $status2->error[0];
							break;
					}

					if($status2->error[0] == "007002"){
						$this->db->where('id',$value->id)->update('sms_transactions',array('status'=>'SENDING','reason'=>''));
					}else{
						$this->db->where('id',$value->id)->update('sms_transactions',array('status'=>'FAILED','reason'=>$reason));
					}
				}
			}else{
				switch ($status->error[0]) {
					case '007001':
						$reason = "MSGID NOT FOUND";
						break;
					case '007002':
						$reason = "IN QUEUE";
						break;
					default:
						$reason = $status->error[0];
						break;
				}

				if($status->error[0] == "007002"){
					$this->db->where('id',$value->id)->update('sms_transactions',array('status'=>'QUEING','reason'=>''));
				}else{
					$this->db->where('id',$value->id)->update('sms_transactions',array('status'=>'FAILED','reason'=>$reason));
				}
				
			}
		}

		echo json_encode($data);
	}
}
