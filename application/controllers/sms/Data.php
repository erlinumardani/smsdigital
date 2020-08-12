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
		$this->title = 'SMS';
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
    
    function quick()
	{
		
		$content_data = array(
			'form_title'=>'Send Quick SMS',
			'base_url' => base_url(),
			'page' => $this->uri->segment(1),
			'csrf_token_name' => $this->security->get_csrf_token_name(),
			'csrf_hash' => $this->security->get_csrf_hash()
		);

		$roles = dropdown_render($this->db->select('id,name')->get_where('roles','id !=1')->result_array(),null);

		$fieldset = array(
			array(
				'name'=>'msisdn',
				'label'=>'Receipents',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-users',
				'custom_attributes'=>array(
					"placeholder"=>"Use Comma(,) As Separator EX. 8801721900000,8801721900001",
					'data-input_type' => 'numeric_bulk'),
				'default_options'=>''
			),
			array(
				'name'=>'Message',
				'type'=>'textarea',
				'class'=>'',
				'icon'=>'fa-align-left',
				'custom_attributes'=>array("placeholder"=>"Message (max 160 character)","maxlength"=>"160")
			),
			array(
				'name'=>'type',
				'type'=>'hidden',
				'class'=>'',
				'icon'=>'',
				'custom_attributes'=>array("value"=>"Quick")
			)
		);

		$content_data['form'] = form_render('initiate_form', $fieldset, FALSE);
        page_view($this->title, 'quick', $content_data);
	}

	function quick_insert($data){

		$msisdn_list = explode(",",$data['msisdn']);
		$max_id = (int)$this->db->select("max(id) as id")->get('sms_transactions')->row()->id+1;

		$i = 0;
		foreach ($msisdn_list as $msisdn) {

			if(substr($msisdn,0,1) == "0"){
				$msisdn = substr_replace($msisdn,"62",0,1);
			}

			$data_api['message'][$i]['content'] = $data['message']; 
			$data_api['message'][$i]['phone'] = $msisdn; 
			$data_api['message'][$i]['schedule'] = date("Y-m-d H:i:s"); 
			$data_api['message'][$i]['uid'] = "smsd-".$max_id; 
			$i++;
			$max_id++;
		}

		if($this->api_sendsms($data_api)->success==true){

			$this->db->trans_start();
		
			foreach ($msisdn_list as $msisdn) {

				if(substr($msisdn,0,1) == "0"){
					$msisdn = substr_replace($msisdn,"62",0,1);
				}

				$this->db->insert('sms_transactions',array(
					'msisdn' => $msisdn,
					'message' => $data['message'],
					'updated_by'  => $data['updated_by']
				));
			}

			return $this->db->trans_complete();

		}else{
			return false;
		}

	}

	function form_submit()
	{
		$data = $this->input->post();
		$data['updated_by'] = $this->session->userdata('user_id');
		$action = false;

		if($data['type']=="Quick"){

			$action = $this->quick_insert($data);

		}
		
		if($action){
			$result = array("status"=>TRUE,"message"=>"Data inserted, Sending");
		}else{
			$result = array("status"=>FALSE,"message"=>"Data failed to insert");
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
}
