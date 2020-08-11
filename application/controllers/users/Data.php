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
		
        $menu_privilege = $this->db->get_where('menus',"JSON_CONTAINS(`privileges`, '[".$this->session->userdata('role_id')."]') and url like '%".$this->uri->segment(1)."%'")->num_rows();

        if(!$this->session->userdata('logged_in') == true || $menu_privilege < 1){
			redirect('auth');
		}
		$this->title = 'User Management';
    }
    
	function index()
	{
		$content_data = array(
			'base_url' => base_url(),
			'page' => $this->uri->segment(1),
			'csrf_token_name' => $this->security->get_csrf_token_name(),
			'csrf_hash' => $this->security->get_csrf_hash()
		);
		
		page_view($this->title, 'data', $content_data);
    }
    
    function form()
	{
		
		$content_data = array(
			'form_title'=>'New User Form',
			'base_url' => base_url(),
			'page' => $this->uri->segment(1),
			'csrf_token_name' => $this->security->get_csrf_token_name(),
			'csrf_hash' => $this->security->get_csrf_hash()
		);

		$roles = dropdown_render($this->db->select('id,name')->get_where('roles','id !=1')->result_array(),null);

		$fieldset = array(
			array(
				'name'=>'Username',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-user',
				'custom_attributes'=>array("placeholder"=>"Username"),
				'default_options'=>''
			),
			array(
				'name'=>'Password',
				'type'=>'password',
				'class'=>'',
				'icon'=>'fa-lock',
				'custom_attributes'=>array("placeholder"=>"Password")
			),
			array(
				'name'=>'Email',
				'type'=>'email',
				'class'=>'',
				'icon'=>'fa-envelope',
				'custom_attributes'=>array("placeholder"=>"address@email.com")
			),
			array(
				'name'=>'Fullname',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-align-left',
				'custom_attributes'=>array("placeholder"=>"Fullname")
			),
			array(
				'name'=>'Gender',
				'type'=>'select',
				'class'=>'',
				'icon'=>'fa-layer-group',
				'custom_attributes'=>array(
				),
				'options'=>array('Male'=>'Male','Female'=>'Female',),
				'default_options'=>'2'
			),
		/* 	array(
				'name'=>'Bdate',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-calendar',
				'custom_attributes'=>array(
					'data-inputmask-alias' => 'datetime',
					'data-inputmask-inputformat' => 'yyyy-mm-dd',
					'data-mask' => '',
					'im-insert' => 'false',
					"placeholder"=>"Birth Date"
				)
			),
			array(
				'name'=>'Phone',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-phone',
				'custom_attributes'=>array(
					'data-input_type' => 'numeric',
					"placeholder"=>"Phone Number"
				)
			), */
			array(
				'name'=>'role_id',
				'label'=>'Role',
				'type'=>'select',
				'class'=>'',
				'icon'=>'fa-layer-group',
				'custom_attributes'=>array(
				),
				'options'=>$roles,
				'default_options'=>'2'
			),
			array(
				'name'=>'Action',
				'type'=>'hidden',
				'class'=>'',
				'icon'=>'',
				'custom_attributes'=>array("value"=>"Create")
			)
		);

		$content_data['form'] = form_render('initiate_form', $fieldset, TRUE);
        page_view($this->title, 'form', $content_data);
	}

	function view($id)
	{

		$view_data = $this->db->where('id',$id)->get('v_users')->row();
		$roles = dropdown_render($this->db->select('id,name')->get('roles')->result_array(),null);

		$content_data = array(
			'form_title'=>'View User Detail',
			'base_url' => base_url(),
			'page' => $this->uri->segment(1),
			'csrf_token_name' => $this->security->get_csrf_token_name(),
			'csrf_hash' => $this->security->get_csrf_hash()
		);

		$fieldset = array(
			array(
				'name'=>'Username',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-user',
				'custom_attributes'=>array(
					"placeholder"=>"Username",
					"value"=>$view_data->username,
					"disabled"=>true,
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
					"value"=>$view_data->email,
					"disabled"=>true,
				)
			),
			array(
				'name'=>'Fullname',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-align-left',
				'custom_attributes'=>array(
					"placeholder"=>"Fullname",
					"value"=>$view_data->fullname,
					"disabled"=>true,
				)
			),
			array(
				'name'=>'Gender',
				'type'=>'select',
				'class'=>'',
				'icon'=>'fa-layer-group',
				'custom_attributes'=>array(
					"disabled"=>true,
				),
				'options'=>array('Male'=>'Male','Female'=>'Female',),
				'default_options'=>$view_data->gender,
			),
		/* 	array(
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
					"value"=>$view_data->bdate,
					"disabled"=>true,
				)
			),
			array(
				'name'=>'Phone',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-phone',
				'custom_attributes'=>array(
					'data-input_type' => 'numeric',
					"placeholder"=>"Phone Number",
					"value"=>$view_data->phone,
					"disabled"=>true,
				)
			), */
			array(
				'name'=>'role_id',
				'label'=>'Role',
				'type'=>'select',
				'class'=>'',
				'icon'=>'fa-layer-group',
				'custom_attributes'=>array(
					"disabled"=>true,
				),
				'options'=>$roles,
				'default_options'=>$view_data->role_id
			),
			array(
				'name'=>'Action',
				'type'=>'hidden',
				'class'=>'',
				'icon'=>'',
				'custom_attributes'=>array("value"=>"Create")
			)
		);

		$content_data['form'] = form_render('initiate_form', $fieldset, TRUE);
        page_view($this->title, 'view', $content_data);
	}

	function update($id)
	{

		$view_data = $this->db->where('id',$id)->get('v_users')->row();
		$roles = dropdown_render($this->db->select('id,name')->get_where('roles','id !=1')->result_array(),null);

		$content_data = array(
			'form_title'=>'User Update Form',
			'base_url' => base_url(),
			'page' => $this->uri->segment(1),
			'csrf_token_name' => $this->security->get_csrf_token_name(),
			'csrf_hash' => $this->security->get_csrf_hash()
		);

		$fieldset = array(
			array(
				'name'=>'Username',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-user',
				'custom_attributes'=>array(
					"placeholder"=>"Username",
					"value"=>$view_data->username,
					"disabled"=>true,
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
					"value"=>$view_data->email,
				)
			),
			array(
				'name'=>'Fullname',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-align-left',
				'custom_attributes'=>array(
					"placeholder"=>"Fullname",
					"value"=>$view_data->fullname
				)
			),
			array(
				'name'=>'Gender',
				'type'=>'select',
				'class'=>'',
				'icon'=>'fa-layer-group',
				'custom_attributes'=>array(
				),
				'options'=>array('Male'=>'Male','Female'=>'Female',),
				'default_options'=>$view_data->gender,
			),
		/* 	array(
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
					"value"=>$view_data->bdate
				)
			),
			array(
				'name'=>'Phone',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-phone',
				'custom_attributes'=>array(
					'data-input_type' => 'numeric',
					"placeholder"=>"Phone Number",
					"value"=>$view_data->phone
				)
			), */
			array(
				'name'=>'role_id',
				'label'=>'Role',
				'type'=>'select',
				'class'=>'',
				'icon'=>'fa-layer-group',
				'custom_attributes'=>array(
				),
				'options'=>$roles,
				'default_options'=>$view_data->role_id
			),
			array(
				'name'=>'Action',
				'type'=>'hidden',
				'class'=>'',
				'icon'=>'',
				'custom_attributes'=>array("value"=>"Update")
			),
			array(
				'name'=>'person_id',
				'type'=>'hidden',
				'class'=>'',
				'icon'=>'',
				'custom_attributes'=>array("value"=>$view_data->person_id)
			)
		);

		$content_data['form'] = form_render('initiate_form', $fieldset, TRUE);
        page_view($this->title, 'update', $content_data);
	}

	function form_submit()
	{
		$data = $this->input->post();
		$data['updated_by'] = $this->session->userdata('user_id');
		$action = $data['action'];
		unset($data['action']);

		if(isset($data['password'])){
			$data['password'] = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 10]);
		}

		if(isset($data['person_id'])){
			$person_id = $data['person_id'];
			unset($data['person_id']);
		}

			$this->db->trans_start();
			if($action == "Update"){
				$this->db->where('person_id',$person_id)->update('users',array('role_id'=>$data['role_id']));
				unset($data['role_id']);
				$this->db->where('id',$person_id)->update('persons',$data);

				if($this->db->trans_complete()){
					$result = array("status"=>TRUE,"message"=>"Data inserted");
				}else{
					$result = array("status"=>FALSE,"message"=>"Data failed to insert");
				}
			}else{
				if($this->db->get_where('users','username = "'.$data['username'].'"')->num_rows()==0){
					$this->db->insert('persons',array(
						'email' => $data['email'],
						'fullname' => $data['fullname'],
						'gender' => $data['gender'],
						//'bdate' => $data['bdate'],
						//'phone' => $data['phone'],
						'updated_by' => $data['updated_by']
					));
					$this->db->insert('users',array(
						'username' => $data['username'],
						'password' => $data['password'],
						'person_id' => $this->db->insert_id(),
						'role_id'  => $data['role_id'],
						'updated_by'  => $data['updated_by']
					));
					if($this->db->trans_complete()){
						$result = array("status"=>TRUE,"message"=>"Data inserted");
					}else{
						$result = array("status"=>FALSE,"message"=>"Data failed to insert");
					}
				}else{
					$result = array("status"=>FALSE,"message"=>"Username already exist");
				}
			}

		echo json_encode($result);
	}

	function list()
    {
		$table = 'v_users'; //nama tabel dari database
		$column_order = array(null, 'username','email','fullname','bdate','phone','role_id','created_at'); //field yang ada di table user
		$column_search = array('username','email','fullname','bdate','phone','role_id','created_at'); //field yang diizin untuk pencarian 
		$order = array('created_at' => 'desc'); // default order 
		$filter = 'role_id != 1';
		
		$this->load->model('datatable_model');

        $list = $this->datatable_model->get_datatables($table, $column_order, $column_search, $order, $filter);
        $data = array();
		$no = $_POST['start'];
		
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->username;
            $row[] = $field->email;
            $row[] = $field->fullname;
            $row[] = $field->bdate;
            $row[] = $field->phone;
            $row[] = $field->role_name;
            $row[] = date_format(date_create($field->created_at),"Y-m-d");
			$row[] = '
				<button class="btn-sm delete btn-danger" data-id='.$field->id.' data-toggle="tooltip" data-placement="top" title="Delete this row" style="border-radius: 50%;"><i class="fas fa-trash"></i></button>
				<button class="btn-sm update btn-primary" data-id='.$field->id.' data-toggle="tooltip" data-placement="top" title="Edit this row" style="border-radius: 50%;"><i class="fas fa-edit"></i></button>
			';
			$row[] = $field->id;
 
            $data[] = $row;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->datatable_model->count_all($table),
            "recordsFiltered" => $this->datatable_model->count_filtered($table, $column_order, $column_search, $order),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
	}

	function delete(){
		
		$id = $this->input->post('id');

		if($this->db->where('person_id',$id)->delete('users') && $this->db->where('id',$id)->delete('persons')){
			$result = array("status"=>TRUE,"message"=>"Data has been deleted");
		}else{
			$result = array("status"=>FALSE,"message"=>"Data failed to be deleted");
		}

		echo json_encode($result);
	}
}
