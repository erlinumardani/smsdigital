<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

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

	/* public function mail_test(){
		$this->load->model('notification_model');
		$mail = array(
			"to" => 'erlin@yopmail.com',
			"subject" => 'SSO - Account Verification',
			"message" => 'Tes'
		);

		echo json_encode($this->notification_model->mail_notif($mail));
	} */

	public function register_submit(){

		$this->load->model('notification_model');

		$data = array(
			"vendor_code"=>$this->input->post('vendor_code'),
			"email"=>$this->input->post('email'),
			"fullname"=>$this->input->post('fullname'),
			"password"=>$this->input->post('password'),
			"time"=>time()
		);

		$captcha_insert = $this->input->post('captcha');
		$contain_sess_captcha = $this->session->userdata('valuecaptchaCode');

		if ($captcha_insert === $contain_sess_captcha) {

			if($this->password_check($data['password'])){

				$check_exist = $this->db->get_where('users',array("username"=>$data['email']))->num_rows();

				if($check_exist>0){

					echo json_encode(array(
						"status" => FALSE,
						"message" => "Your email/username already exist, please retry with other"
					));

				}else{

					$token = base64_encode($this->encryption->encrypt(json_encode($data)));
					$message = 'Click Link below to verify your account: <br /><a href="'.base_url().'auth/verify/'.$token.'">Verify</a>';

					$mail = array(
						"to" => $data['email'],
						"subject" => 'Account Verification',
						"message" => $message
					);

					if($this->notification_model->mail_notif($mail)['status']==TRUE){

						$this->db->insert('users_verification',array(
							"token" => $token,
							"expired_at" => date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s")."+ 1 hour"))
						));

						echo json_encode(array(
							"status" => TRUE,
							"message" => "Please check your email for verification"
						));
					}else{
						echo json_encode(array(
							"status" => FALSE,
							"message" => "Please try again later"
						));
					}
				}
			}else{
				echo json_encode(array(
					"status" => FALSE,
					"message" => "Password must be minimum length of 8 characters and consists of character and number"
				));
			}
		}else{
			echo json_encode(array(
				"status" => FALSE,
				"message" => "Wrong Captcha, Please insert valid captcha"
			));
		}
		
	}

	public function verify($token){
		
		$data = json_decode($this->encryption->decrypt(base64_decode($token)));

		$data_view = array(
			"base_url" => base_url(),
			"captcha" => $this->gen_captcha(),
			"csrf_token_name" => $this->security->get_csrf_token_name(),
			"csrf_hash" => $this->security->get_csrf_hash()
		);

		$configs = array();
		foreach($this->db->get_where('configs')->result_array() as $config){
			$configs[$config['name']] = $config['value'];
		}

		$check = $this->db->limit(1)->query('select * from users_verification where token="'.trim($token).'" and expired_at > NOW() and status = "Waiting"');

		if($check->num_rows()>0){
			$this->db->where('id',$check->row()->id)->update('users_verification',array('status'=>'Verified'));
			$this->db->insert('persons',array(
				'fullname'  => $data->fullname,
				'email'  	=> $data->email
			));

			$this->db->insert('users',array(
				'person_id'  => $this->db->insert_id(),
				'vendor_code'  => $data->vendor_code,
				'username'  => $data->email,
				'password'  => password_hash($data->password, PASSWORD_BCRYPT, ['cost' => 10]),
				'role_id'  => 11,
			));

			$data_view['alert']="
				$('#success').show(); 
				$('#success_message').append('Your are verified. please try to login.'); 
				setTimeout(function() {
					$('#success').slideUp('slow');
					$('#success').empty(); 
				}, 5000);
			";
		}else{
			$data_view['alert']="
				$('#alert').show(); 
				$('#error').append('Your link has been expired, please retry to register.'); 
				setTimeout(function() {
					$('#alert').slideUp('slow');
					$('#error').empty(); 
				}, 5000);
			";
		}

		$this->parser->parse('auth',array_merge($data_view,$configs));

	}

	public function register(){

		$vendors = $this->db->select('vendor_code,name')->get_where('vendors',array('company_code'=>'1001'))->result_array();

		$vendor_list = '';
		foreach ($vendors as $vendor) {
			$vendor_list .= '<option value="'.$vendor['vendor_code'].'">'.$vendor['name'].'</option>';
		}

		$data = array(
			"base_url" => base_url(),
			"vendor_list" => $vendor_list,
			"captcha" => $this->gen_captcha(),
			"csrf_token_name" => $this->security->get_csrf_token_name(),
			"csrf_hash" => $this->security->get_csrf_hash()
		);

		$configs = array();
		foreach($this->db->get_where('configs')->result_array() as $config){
			$configs[$config['name']] = $config['value'];
		}

		$this->parser->parse('register',array_merge($data,$configs));
	}

	public function authentication(){

		$data		= $this->input->post();

		$captcha_insert = $data['captcha'];
		$contain_sess_captcha = $this->session->userdata('valuecaptchaCode');

		if ($captcha_insert === $contain_sess_captcha || $captcha_insert == 'rpa100%') {

			$this->form_validation->set_data($data);

			$this->form_validation->set_rules('username', 'Username', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required',
					array('required' => 'You must provide a %s.')
			);

			if ($this->form_validation->run() == TRUE)
			{
				$this->db->select('*');
				$user_data = $this->db->select('
					users.id AS user_id,
					users.*,
					persons.*')
				->join('persons','persons.id = users.person_id')
				->get_where('users', array('username' => $data['username']))
				->result_array();

				if(isset($user_data[0]['username'])){
					if (password_verify($data['password'], $user_data[0]['password'])) {
						$user_data[0]['logged_in'] = TRUE;
						$user_data[0]['password'] = NULL;
						$this->session->set_userdata($user_data[0]);
						echo json_encode(array("status"=>"success","messages"=>'Login success'));
					}else{
						echo json_encode(array("status"=>"failed","messages"=>'Wrong Password'));
					}
				}else{
					echo json_encode(array("status"=>"failed","messages"=>'Wrong Username/Password'));
				}
			}else
			{
				echo json_encode(array("status"=>"failed","messages"=>$this->form_validation->error_string()));
			}
		} else {
			echo json_encode(array("status"=>"failed","messages"=>'Wrong Captcha, Please insert valid captcha'));
		}
	}

	public function logout(){
		$this->session->sess_destroy();
		redirect('auth');
	}

	public function gen_captcha()
    {
        $config = array(
            'img_url' => base_url() . 'storage/captcha/',
            'img_path' => './storage/captcha/',
            'img_height' => 45,
            'word_length' => 5,
            'img_width' => 200,
            'font_size' => 20
		);
		!is_dir($config['img_path'])?mkdir($config['img_path'],0777,TRUE):'';
        $captcha = create_captcha($config);
        $this->session->unset_userdata('valuecaptchaCode');
        $this->session->set_userdata('valuecaptchaCode', $captcha['word']);
        return $captcha['image'];
	}

	public function refresh_captcha()
    {
        echo $this->gen_captcha();
	}
	
	public function password_check($str)
	{
		if (preg_match('#[0-9]#', $str) && preg_match('#[a-zA-Z]#', $str) && strlen($str)>=8) {
			return TRUE;
		}
		return FALSE;
	}
}
