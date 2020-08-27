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

		$uri = 'https://smsturbo.infomedia.co.id:8106/HERMES.1/Service/TokenRequest';

		// Start session (also wipes existing/previous sessions)
		$this->curl->create($uri);

		// More human looking options
		$this->curl->option('buffersize', 10);

		// Header
		$this->curl->http_header('Content-Type', 'application/json');

		// Post - If you do not use post, it will just run a GET request
		$post = json_encode(array(
			'username'=>'dukcapil',
			'password'=>'dukc@p1LsMs'
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

		$uri = 'https://smsturbo.infomedia.co.id:8106/HERMES.1/Message/restCheckDelivery';

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

	public function get_status(){
		$data = $this->db->select('id,concat("smsd-",id) as uid, guid')
		->from('sms_transactions')
		->where('status','SENDING')
		->or_where('status','QUEING')
		->where('schedule < now()')
		->get()->result();

		foreach ($data as $value) {
			$status = $this->api_delivery_check($value->guid);

			if($status->success == true){
				$this->db->where('id',$value->id)->update('sms_transactions',array('status'=>$status->data[0]->state));
			}else{
				$this->db->where('id',$value->id)->update('sms_transactions',array('status'=>'QUEING'));
			}
		}

		echo json_encode($data);
	}
}
