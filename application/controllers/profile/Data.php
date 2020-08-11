<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends CI_Controller {

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

    public function __construct(){
		parent::__construct();
		
        if(!$this->session->userdata('logged_in') == true){
			redirect('auth');
		}
		$this->title = 'My Profile';
    }
    
	function index()
	{
		redirect('profile/data/update');
    }
    
	function update()
	{

		$view_data = $this->session->userdata();
		$roles = dropdown_render($this->db->select('id,name')->get_where('roles',(array("id"=>$this->session->userdata('role_id'))))->result_array(),null);

		$fieldset = array(
			array(
				'name'=>'Username',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-user',
				'custom_attributes'=>array(
					"placeholder"=>"Username",
					"value"=>$view_data['username'],
					"disabled"=>true
				),
				'default_options'=>''
			),
			array(
				'name'=>'Email',
				'type'=>'email',
				'class'=>'',
				'icon'=>'fa-envelope',
				'custom_attributes'=>array(
					"placeholder"=>"address@email.com",
					"value"=>$view_data['email'],
				)
			),
			array(
				'name'=>'Fullname',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-align-left',
				'custom_attributes'=>array(
					"placeholder"=>"Fullname",
					"value"=>$view_data['fullname']
				)
			),
			array(
				'name'=>'Bdate',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-calendar',
				'custom_attributes'=>array(
					'data-inputmask-alias' => 'datetime',
					'data-inputmask-inputformat' => 'yyyy-mm-dd',
					'data-mask' => '',
					'im-insert' => 'false',
					"placeholder"=>"Birth Date",
					"value"=>$view_data['bdate']
				)
			),
			array(
				'name'=>'Phone',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-phone',
				'custom_attributes'=>array(
					'data-input_type' => 'number',
					"placeholder"=>"Phone Number",
					"value"=>$view_data['phone']
				)
			),
			array(
				'name'=>'Role',
				'type'=>'select',
				'class'=>'',
				'icon'=>'fa-layer-group',
				'custom_attributes'=>array(
					"disabled"=>true,
				),
				'options'=>$roles,
				'default_options'=>$view_data['role_id']
			),
			array(
				'name'=>'User_id',
				'type'=>'hidden',
				'class'=>'',
				'icon'=>'',
				'custom_attributes'=>array("value"=>$view_data['user_id'])
			),
			array(
				'name'=>'Person_id',
				'type'=>'hidden',
				'class'=>'',
				'icon'=>'',
				'custom_attributes'=>array("value"=>$view_data['person_id'])
			)
		);

		$fieldset2 = array(
			array(
				'name'=>'old_password',
				'label'=>'Old Password',
				'type'=>'password',
				'class'=>'',
				'icon'=>'fa-lock',
				'custom_attributes'=>array("placeholder"=>"Old Password")
			),
			array(
				'name'=>'Password',
				'type'=>'password',
				'class'=>'',
				'icon'=>'fa-lock',
				'custom_attributes'=>array("placeholder"=>"Password")
			),
			array(
				'name'=>'confirm_password',
				'label'=>'Confirm Password',
				'type'=>'password',
				'class'=>'',
				'icon'=>'fa-lock',
				'custom_attributes'=>array("placeholder"=>"Confirm Password")
			)
		);

		$content_data = array(
			'csrf_token_name' => $this->security->get_csrf_token_name(),
			'csrf_hash' => $this->security->get_csrf_hash(),
			'form_title'=>'User Update Form',
			'base_url' => base_url(),
			'page' => $this->uri->segment(1),
			'form' => form_render('initiate_form', $fieldset, TRUE, TRUE, 'location.reload(true);'),
			'form2' => form_render('change_password', $fieldset2, FALSE, FALSE, 'location.reload(true);')
		);

        page_view($this->title, 'update', $content_data);
	}

	function form_submit()
	{
		$data = $this->input->post();
		$table = 'persons';
		$data['updated_by'] = $this->session->userdata('user_id');

		if(isset($data['person_id'])){
			$id = $data['person_id'];
			unset($data['person_id']);
			unset($data['user_id']);
			unset($data['role']);
			unset($data['username']);
		}

		$this->db->trans_start();
		$this->db->where('id',$id)->update($table,$data);

		if($this->db->trans_complete()){
			$this->session->set_userdata($data);
			$result = array("status"=>TRUE,"message"=>"Data inserted");
		}else{
			$result = array("status"=>FALSE,"message"=>"Data failed to insert");
		}

		echo json_encode($result);
	}

	function change_password()
	{
		$data = $this->input->post();

		$check_password = $this->db->select('password')->get_where('users','id = "'.$data['id'].'"')->row()->password;

		if (password_verify($data['old_password'], $check_password)) {

			if($this->password_check($data['password'])){

				$this->db->trans_start();
				$this->db->where('id',$data['id'])->update('users',array('password'=>password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 10])));

				if($this->db->trans_complete()){
					$this->session->set_userdata($data);
					$result = array("status"=>TRUE,"message"=>"Password Changed");
				}else{
					$result = array("status"=>FALSE,"message"=>"Data failed to update");
				}

			}else{
				$result = array(
					"status" => FALSE,
					"message" => "Password must be minimum length of 8 characters and consists of character and number"
				);
			}
		}else{
			$result = array("status"=>FALSE,"message"=>"Wrong old password");
		}

		echo json_encode($result);
	}

	public function password_check($str)
	{
		if (preg_match('#[0-9]#', $str) && preg_match('#[a-zA-Z]#', $str) && strlen($str)>=8) {
			return TRUE;
		}
		return FALSE;
	}
	
}
