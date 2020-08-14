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
		$this->title = 'Contacts Management';
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
			'form_title'=>'New Contact Form',
			'base_url' => base_url(),
			'page' => $this->uri->segment(1),
			'csrf_token_name' => $this->security->get_csrf_token_name(),
			'csrf_hash' => $this->security->get_csrf_hash()
		);

		$phonebooks = dropdown_render($this->db->select('id,name')->get('sms_phonebooks')->result_array(),null);

		$fieldset = array(
			array(
				'name'=>'Phone',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-phone',
				'custom_attributes'=>array(
					'data-input_type' => 'numeric',
					"placeholder"=>"Phone Number"
				)
			),
			array(
				'name'=>'first_name',
				'label'=>'Firstname',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-align-left',
				'custom_attributes'=>array("placeholder"=>"Fullname")
			),
			array(
				'name'=>'last_name',
				'label'=>'Lastname',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-align-left',
				'custom_attributes'=>array("placeholder"=>"Lastname")
			),
			array(
				'name'=>'Email',
				'type'=>'email',
				'class'=>'',
				'icon'=>'fa-envelope',
				'custom_attributes'=>array("placeholder"=>"address@email.com")
			),
			array(
				'name'=>'Company',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-align-left',
				'custom_attributes'=>array("placeholder"=>"Company")
			),
			array(
				'name'=>'phonebook_id',
				'label'=>'Phone Book',
				'type'=>'select',
				'class'=>'',
				'icon'=>'fa-layer-group',
				'custom_attributes'=>array(
				),
				'options'=>$phonebooks,
				'default_options'=>''
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

		$view_data = $this->db->where('id',$id)->get('sms_contacts')->row();
		$phonebooks = dropdown_render($this->db->select('id,name')->get('sms_phonebooks')->result_array(),null);

		$fieldset = array(
			array(
				'name'=>'Phone',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-phone',
				'custom_attributes'=>array(
					'data-input_type' => 'numeric',
					"placeholder"=>"Phone Number",
					'value' => $view_data->phone,
					'disabled' => true,
				)
			),
			array(
				'name'=>'first_name',
				'label'=>'Firstname',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-align-left',
				'custom_attributes'=>array(
					"placeholder"=>"Fullname",
					'value' => $view_data->first_name,
					'disabled' => true,
				)
			),
			array(
				'name'=>'last_name',
				'label'=>'Lastname',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-align-left',
				'custom_attributes'=>array(
					"placeholder"=>"Lastname",
					'value' => $view_data->last_name,
					'disabled' => true,
				)
			),
			array(
				'name'=>'Email',
				'type'=>'email',
				'class'=>'',
				'icon'=>'fa-envelope',
				'custom_attributes'=>array(
					"placeholder"=>"address@email.com",
					'value' => $view_data->email,
					'disabled' => true,
				)
			),
			array(
				'name'=>'Company',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-align-left',
				'custom_attributes'=>array(
					"placeholder"=>"Company",
					'value' => $view_data->company,
					'disabled' => true,
				)
			),
			array(
				'name'=>'phonebook_id',
				'label'=>'Phone Book',
				'type'=>'select',
				'class'=>'',
				'icon'=>'fa-layer-group',
				'custom_attributes'=>array(
					'disabled' => true,
				),
				'options'=>$phonebooks,
				'default_options'=>$view_data->phonebook_id
			),
			array(
				'name'=>'Action',
				'type'=>'hidden',
				'class'=>'',
				'icon'=>'',
				'custom_attributes'=>array("value"=>"Create")
			)
		);

		$content_data = array(
			'form_title'=>'View Contact Detail',
			'base_url' => base_url(),
			'page' => $this->uri->segment(1),
			'csrf_token_name' => $this->security->get_csrf_token_name(),
			'csrf_hash' => $this->security->get_csrf_hash()
		);

		$content_data['form'] = form_render('initiate_form', $fieldset, TRUE);
        page_view($this->title, 'view', $content_data);
	}

	function update($id)
	{

		$view_data = $this->db->where('id',$id)->get('sms_contacts')->row();
		$phonebooks = dropdown_render($this->db->select('id,name')->get('sms_phonebooks')->result_array(),null);

		$fieldset = array(
			array(
				'name'=>'Phone',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-phone',
				'custom_attributes'=>array(
					'data-input_type' => 'numeric',
					"placeholder"=>"Phone Number",
					'value' => $view_data->phone,
				)
			),
			array(
				'name'=>'first_name',
				'label'=>'Firstname',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-align-left',
				'custom_attributes'=>array(
					"placeholder"=>"Fullname",
					'value' => $view_data->first_name,
				)
			),
			array(
				'name'=>'last_name',
				'label'=>'Lastname',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-align-left',
				'custom_attributes'=>array(
					"placeholder"=>"Lastname",
					'value' => $view_data->last_name,
				)
			),
			array(
				'name'=>'Email',
				'type'=>'email',
				'class'=>'',
				'icon'=>'fa-envelope',
				'custom_attributes'=>array(
					"placeholder"=>"address@email.com",
					'value' => $view_data->email,
				)
			),
			array(
				'name'=>'Company',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-align-left',
				'custom_attributes'=>array(
					"placeholder"=>"Company",
					'value' => $view_data->company,
				)
			),
			array(
				'name'=>'phonebook_id',
				'label'=>'Phone Book',
				'type'=>'select',
				'class'=>'',
				'icon'=>'fa-layer-group',
				'custom_attributes'=>array(
				),
				'options'=>$phonebooks,
				'default_options'=>$view_data->phonebook_id
			),
			array(
				'name'=>'id',
				'type'=>'hidden',
				'class'=>'',
				'icon'=>'',
				'custom_attributes'=>array("value"=>$view_data->id)
			),
			array(
				'name'=>'Action',
				'type'=>'hidden',
				'class'=>'',
				'icon'=>'',
				'custom_attributes'=>array("value"=>"Update")
			)
		);

		$content_data = array(
			'form_title'=>'View Menu Detail',
			'base_url' => base_url(),
			'page' => $this->uri->segment(1),
			'csrf_token_name' => $this->security->get_csrf_token_name(),
			'csrf_hash' => $this->security->get_csrf_hash()
		);

		$content_data['form'] = form_render('initiate_form', $fieldset, TRUE);
        page_view($this->title, 'update', $content_data);
	}

	function form_submit()
	{
		$table = "sms_contacts";
		$data = $this->input->post();
		$data['updated_by'] = $this->session->userdata('user_id');
		$action = $data['action'];
		unset($data['action']);
		
		if(isset($data['id'])){
			$id = $data['id'];
			unset($data['id']);
		}


		$this->db->trans_start();
		if($action == "Update"){
			$this->db->where('id',$id)->update('sms_contacts',$data);
		}else{
			$this->db->insert('sms_contacts',$data);
		}

		if($this->db->trans_complete()){
			$result = array("status"=>TRUE,"message"=>"Data inserted");
		}else{
			$result = array("status"=>FALSE,"message"=>"Data failed to insert");
		}

		echo json_encode($result);
	}

	function list()
    {
		$table = 'sms_contacts'; //nama tabel dari database
		$column_order = array(null, 'phone','first_name','email','company'); //field yang ada di table user
		$column_search = array('phone','first_name','email','company'); //field yang diizin untuk pencarian 
		$order = array('created_at' => 'desc'); // default order 
		
		$this->load->model('datatable_model');

        $list = $this->datatable_model->get_datatables($table, $column_order, $column_search, $order);
        $data = array();
		$no = $_POST['start'];
		
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->phone;
            $row[] = $field->first_name.' '.$field->last_name;
            $row[] = $field->email;
            $row[] = $field->company;
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

		if($this->db->where('id',$id)->delete('sms_contacts')){
			$result = array("status"=>TRUE,"message"=>"Data has been deleted");
		}else{
			$result = array("status"=>FALSE,"message"=>"Data failed to be deleted");
		}

		echo json_encode($result);
	}
}
