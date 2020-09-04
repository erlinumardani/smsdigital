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
		$this->title = 'Dashboard';
		$this->role_id = $this->session->userdata('role_id');
		$this->tenant_id = $this->session->userdata('tenant_id');
		$this->user_id = $this->session->userdata('user_id');
		$this->username = $this->session->userdata('username');
    }
    
	function index()
	{

		if($this->role_id=="3"){
			$limit = $this->db->select('sms_limit')->get_where('users',array('id'=>$this->session->userdata('user_id')))->row()->sms_limit;
			$total_sms = $this->db->select("count(id) as total")->get_where('sms_transactions','month(created_at) = month(now()) and status in("RECEIVED","SENDING","SENT","QUEING") and updated_by = "'.$this->user_id.'"')->row()->total;
			$sms_otomatis = $this->db->select('count(id) as total')->get_where('sms_transactions','schedule > now() and type = "Schedule"')->row()->total;
			$contacts = $this->db->select('count(id) as total')->get('sms_contacts')->row()->total;

			$total_sms_received = $this->db->select("count(id) as total")->get_where('sms_transactions','month(created_at) = month(now()) and status in("RECEIVED","SENT") and updated_by = "'.$this->user_id.'"')->row()->total;
			$total_sms_sending = $this->db->select("count(id) as total")->get_where('sms_transactions','month(created_at) = month(now()) and status in("QUEING","SENDING") and updated_by = "'.$this->user_id.'"')->row()->total;
			$total_sms_failed = $this->db->select("count(id) as total")->get_where('sms_transactions','month(created_at) = month(now()) and status in("FAILED","MSGID_NOT_FOUND","UNSENT") and updated_by = "'.$this->user_id.'"')->row()->total;

			if($total_sms>0 && $limit>0){
				$limit_persent = number_format($total_sms/$limit * 100);
			}else{
				$limit_persent = 0;
			}
			
		}else{
			$limit = $this->db->select('sms_limit')->get_where('users',array('id'=>$this->session->userdata('user_id')))->row()->sms_limit;
			$total_sms = $this->db->select("count(id) as total")->get_where('sms_transactions','month(created_at) = month(now()) and status in("RECEIVED","SENDING","SENT","QUEING") and tenant_id = "'.$this->tenant_id.'"')->row()->total;
			$sms_otomatis = $this->db->select('count(id) as total')->get_where('sms_transactions','schedule > now() and type = "Schedule" and tenant_id = '.$this->tenant_id)->row()->total;
			$contacts = $this->db->select('count(id) as total')->get('sms_contacts')->row()->total;
			
			$total_sms_received = $this->db->select("count(id) as total")->get_where('sms_transactions','month(created_at) = month(now()) and status in("RECEIVED","SENT") and tenant_id = "'.$this->tenant_id.'"')->row()->total;
			$total_sms_sending = $this->db->select("count(id) as total")->get_where('sms_transactions','month(created_at) = month(now()) and status in("QUEING","SENDING") and tenant_id = "'.$this->tenant_id.'"')->row()->total;
			$total_sms_failed = $this->db->select("count(id) as total")->get_where('sms_transactions','month(created_at) = month(now()) and status in("FAILED","MSGID_NOT_FOUND","UNSENT") and tenant_id = "'.$this->tenant_id.'"')->row()->total;

			if($total_sms>0 && $limit>0){
				$limit_persent = number_format($total_sms/$limit * 100);
			}else{
				$limit_persent = 0;
			}
		}

		$y_telkomsel = $this->getdata_yearly('Telkomsel');
		$y_indosat = $this->getdata_yearly('Indosat');
		$y_xl = $this->getdata_yearly('xl');
		$y_axis = $this->getdata_yearly('AXIS');
		$y_smartfren = $this->getdata_yearly('Smartfren');
		$y_three = $this->getdata_yearly('Three');
		$y_other = $this->getdata_yearly('');

		$content_data = array(
			'base_url' => base_url(),
			'page' => $this->uri->segment(1),
			'limit' => number_format($limit),
			'total_sms' => number_format($total_sms),
			'total_sms_received' => number_format($total_sms_received),
			'total_sms_sending' => number_format($total_sms_sending),
			'total_sms_failed' => number_format($total_sms_failed),
			'limit_persent' => $limit_persent,
			'sms_otomatis' => $sms_otomatis,
			'contacts' => $contacts,
			'y_telkomsel' => $y_telkomsel,
			'y_indosat' => $y_indosat,
			'y_xl' => $y_xl,
			'y_axis' => $y_axis,
			'y_smartfren' => $y_smartfren,
			'y_three' => $y_three,
			'y_other' => $y_other
		);
		
		page_view($this->title, 'view', $content_data);
	}
	
	function report()
	{
		$content_data = array(
			'base_url' => base_url(),
			'csrf_token_name' => $this->security->get_csrf_token_name(),
			'csrf_hash' => $this->security->get_csrf_hash(),
			'page' => $this->uri->segment(1)
		);
		
		page_view('Report', 'data', $content_data);
	}
	
	function list()
    {
		$table = 'report_historical'; //nama tabel dari database
		$column_order = array(null, 'Nomor_Registrasi','Nama_Vendor','No_TP','Nominal_Invoice','Status','User_PP','Tanggal_Request'); //field yang ada di table user
		$column_search = array('Nomor_Registrasi','Nama_Vendor','No_TP','Nominal_Invoice','Status','User_PP','Tanggal_Request'); //field yang diizin untuk pencarian 
		$order = array('Tanggal_Request' => 'asc'); // default order 
		$filter = "month(Tanggal_Request) = month(now())";
		$data = $this->input->post();
		
		$this->load->model('datatable_model');

		if(isset($data['startdate']) && isset($data['enddate'])){
			$filter = "Tanggal_Request between '".$data['startdate']." 00:00:00' and '".$data['enddate']." 23:59:59'";
		}

        $list = $this->datatable_model->get_datatables($table, $column_order, $column_search, $order, $filter);
        $data = array();
		$no = $_POST['start'];
		
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->Nomor_Registrasi;
            $row[] = $field->Nama_Vendor;
            $row[] = $field->No_TP;
            $row[] = $field->Nominal_Invoice;
            $row[] = $field->Status;
            $row[] = $field->User_PP;
			$row[] = date_format(date_create($field->Tanggal_Request),"Y-m-d");
            $row[] = $field->Registrasi_Ulang;
            $row[] = $field->Checker;
            $row[] = $field->Start_Checking;
            $row[] = $field->End_Checking;
            $row[] = $field->Verificator;
            $row[] = $field->Start_Verification;
            $row[] = $field->End_Verification;
            $row[] = $field->User_Process;
            $row[] = $field->Start_Process;
            $row[] = $field->End_Process;
            $row[] = $field->Validator;
            $row[] = $field->Start_Validation;
            $row[] = $field->End_Validation;
 
            $data[] = $row;
        }
 
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->datatable_model->count_all($table),
            "recordsFiltered" => $this->datatable_model->count_filtered($table, $column_order, $column_search, $order, $filter),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
	}

	function getdata_monthly($month,$provider){

		if($this->role_id=="3"){
			$data = $this->db->select("count(*) as total")
			->get_where('v_sms_transactions','sender = "'.$this->username.'" and month(created_at) = "'.$month.'" and provider = "'.$provider.'"')
			->row()->total;
		}else{
			$data = $this->db->select("count(*) as total")
			->get_where('v_sms_transactions','month(created_at) = "'.$month.'" and provider = "'.$provider.'" and tenant_id = "'.$this->tenant_id.'"')
			->row()->total;
		}

		return $data;
	}

	function getdata_provider($month,$provider){

		if($this->role_id=="3"){
			$data = $this->db->select("count(*) as total")
			->get_where('v_sms_transactions','sender = "'.$this->username.'" and month(created_at) = "'.$month.'" and provider = "'.$provider.'" and tenant_id = "'.$this->tenant_id.'"')
			->row()->total;
		}else{
			$data = $this->db->select("count(*) as total")
			->get_where('v_sms_transactions','month(created_at) = "'.$month.'" and provider = "'.$provider.'" and tenant_id = "'.$this->tenant_id.'"')
			->row()->total;
		}

		echo $data;
	}
	
	function getdata_yearly($provider){

		if($this->role_id=="3"){
			$data = $this->db->select("count(*) as total")
			->get_where('v_sms_transactions','sender = "'.$this->username.'" and year(created_at) = year(now()) and provider = "'.$provider.'" and tenant_id = "'.$this->tenant_id.'"')
			->row()->total;
			$total = $this->db->select("count(*) as total")
			->get_where('v_sms_transactions','sender = "'.$this->username.'" and year(created_at) = year(now()) and tenant_id = "'.$this->tenant_id.'"')
			->row()->total;
		}else{
			$data = $this->db->select("count(*) as total")
			->get_where('v_sms_transactions','year(created_at) = year(now()) and provider = "'.$provider.'" and tenant_id = "'.$this->tenant_id.'"')
			->row()->total;
			$total = $this->db->select("count(*) as total")
			->get_where('v_sms_transactions','year(created_at) = year(now()) and tenant_id = "'.$this->tenant_id.'"')
			->row()->total;
		}

		return round($data/$total * 100);
	}

	function getdata()
	{

		if($this->role_id=="3"){
			$limit = $this->db->select('sms_limit')->get_where('users',array('id'=>$this->session->userdata('user_id')))->row()->sms_limit;
			$total_sms = $this->db->select("count(id) as total")->get_where('sms_transactions','month(created_at) = month(now()) and status in("RECEIVED","SENDING","SENT","QUEING") and updated_by = "'.$this->user_id.'"')->row()->total;
			$sms_otomatis = $this->db->select('count(id) as total')->get_where('sms_transactions','schedule > now() and type = "Schedule"')->row()->total;
			$contacts = $this->db->select('count(id) as total')->get('sms_contacts')->row()->total;

			$total_sms_received = $this->db->select("count(id) as total")->get_where('sms_transactions','month(created_at) = month(now()) and status in("RECEIVED","SENT") and updated_by = "'.$this->user_id.'"')->row()->total;
			$total_sms_sending = $this->db->select("count(id) as total")->get_where('sms_transactions','month(created_at) = month(now()) and status in("QUEING","SENDING") and updated_by = "'.$this->user_id.'"')->row()->total;
			$total_sms_failed = $this->db->select("count(id) as total")->get_where('sms_transactions','month(created_at) = month(now()) and status in("FAILED","MSGID_NOT_FOUND","UNSENT") and updated_by = "'.$this->user_id.'"')->row()->total;

			if($total_sms>0 && $limit>0){
				$limit_persent = number_format($total_sms/$limit * 100);
			}else{
				$limit_persent = 0;
			}
			
		}else{
			$limit = $this->db->select('sms_limit')->get_where('users',array('id'=>$this->session->userdata('user_id')))->row()->sms_limit;
			$total_sms = $this->db->select("count(id) as total")->get_where('sms_transactions','month(created_at) = month(now()) and status in("RECEIVED","SENDING","SENT","QUEING") and tenant_id = "'.$this->tenant_id.'"')->row()->total;
			$sms_otomatis = $this->db->select('count(id) as total')->get_where('sms_transactions','schedule > now() and type = "Schedule" and tenant_id = '.$this->tenant_id)->row()->total;
			$contacts = $this->db->select('count(id) as total')->get('sms_contacts')->row()->total;
			
			$total_sms_received = $this->db->select("count(id) as total")->get_where('sms_transactions','month(created_at) = month(now()) and status in("RECEIVED","SENT") and tenant_id = "'.$this->tenant_id.'"')->row()->total;
			$total_sms_sending = $this->db->select("count(id) as total")->get_where('sms_transactions','month(created_at) = month(now()) and status in("QUEING","SENDING") and tenant_id = "'.$this->tenant_id.'"')->row()->total;
			$total_sms_failed = $this->db->select("count(id) as total")->get_where('sms_transactions','month(created_at) = month(now()) and status in("FAILED","MSGID_NOT_FOUND","UNSENT") and tenant_id = "'.$this->tenant_id.'"')->row()->total;

			if($total_sms>0 && $limit>0){
				$limit_persent = number_format($total_sms/$limit * 100);
			}else{
				$limit_persent = 0;
			}
		}

		$gm_telkomsel = array(
			$this->getdata_monthly('1','Telkomsel'),
			$this->getdata_monthly('2','Telkomsel'),
			$this->getdata_monthly('3','Telkomsel'),
			$this->getdata_monthly('4','Telkomsel'),
			$this->getdata_monthly('5','Telkomsel'),
			$this->getdata_monthly('6','Telkomsel'),
			$this->getdata_monthly('7','Telkomsel'),
			$this->getdata_monthly('8','Telkomsel'),
			$this->getdata_monthly('9','Telkomsel'),
			$this->getdata_monthly('10','Telkomsel'),
			$this->getdata_monthly('11','Telkomsel'),
			$this->getdata_monthly('12','Telkomsel')
		);

		$gm_indosat = array(
			$this->getdata_monthly('1','Indosat'),
			$this->getdata_monthly('2','Indosat'),
			$this->getdata_monthly('3','Indosat'),
			$this->getdata_monthly('4','Indosat'),
			$this->getdata_monthly('5','Indosat'),
			$this->getdata_monthly('6','Indosat'),
			$this->getdata_monthly('7','Indosat'),
			$this->getdata_monthly('8','Indosat'),
			$this->getdata_monthly('9','Indosat'),
			$this->getdata_monthly('10','Indosat'),
			$this->getdata_monthly('11','Indosat'),
			$this->getdata_monthly('12','Indosat')
		);

		$gm_xl = array(
			$this->getdata_monthly('1','XL'),
			$this->getdata_monthly('2','XL'),
			$this->getdata_monthly('3','XL'),
			$this->getdata_monthly('4','XL'),
			$this->getdata_monthly('5','XL'),
			$this->getdata_monthly('6','XL'),
			$this->getdata_monthly('7','XL'),
			$this->getdata_monthly('8','XL'),
			$this->getdata_monthly('9','XL'),
			$this->getdata_monthly('10','XL'),
			$this->getdata_monthly('11','XL'),
			$this->getdata_monthly('12','XL')
		);

		$gm_axis = array(
			$this->getdata_monthly('1','AXIS'),
			$this->getdata_monthly('2','AXIS'),
			$this->getdata_monthly('3','AXIS'),
			$this->getdata_monthly('4','AXIS'),
			$this->getdata_monthly('5','AXIS'),
			$this->getdata_monthly('6','AXIS'),
			$this->getdata_monthly('7','AXIS'),
			$this->getdata_monthly('8','AXIS'),
			$this->getdata_monthly('9','AXIS'),
			$this->getdata_monthly('10','AXIS'),
			$this->getdata_monthly('11','AXIS'),
			$this->getdata_monthly('12','AXIS')
		);

		$gm_smartfren = array(
			$this->getdata_monthly('1','Smartfren'),
			$this->getdata_monthly('2','Smartfren'),
			$this->getdata_monthly('3','Smartfren'),
			$this->getdata_monthly('4','Smartfren'),
			$this->getdata_monthly('5','Smartfren'),
			$this->getdata_monthly('6','Smartfren'),
			$this->getdata_monthly('7','Smartfren'),
			$this->getdata_monthly('8','Smartfren'),
			$this->getdata_monthly('9','Smartfren'),
			$this->getdata_monthly('10','Smartfren'),
			$this->getdata_monthly('11','Smartfren'),
			$this->getdata_monthly('12','Smartfren')
		);

		$gm_three = array(
			$this->getdata_monthly('1','Three'),
			$this->getdata_monthly('2','Three'),
			$this->getdata_monthly('3','Three'),
			$this->getdata_monthly('4','Three'),
			$this->getdata_monthly('5','Three'),
			$this->getdata_monthly('6','Three'),
			$this->getdata_monthly('7','Three'),
			$this->getdata_monthly('8','Three'),
			$this->getdata_monthly('9','Three'),
			$this->getdata_monthly('10','Three'),
			$this->getdata_monthly('11','Three'),
			$this->getdata_monthly('12','Three')
		);

		$gm_other = array(
			$this->getdata_monthly('1',''),
			$this->getdata_monthly('2',''),
			$this->getdata_monthly('3',''),
			$this->getdata_monthly('4',''),
			$this->getdata_monthly('5',''),
			$this->getdata_monthly('6',''),
			$this->getdata_monthly('7',''),
			$this->getdata_monthly('8',''),
			$this->getdata_monthly('9',''),
			$this->getdata_monthly('10',''),
			$this->getdata_monthly('11',''),
			$this->getdata_monthly('12','')
		);

		$y_telkomsel = $this->getdata_yearly('Telkomsel');
		$y_indosat = $this->getdata_yearly('Indosat');
		$y_xl = $this->getdata_yearly('xl');
		$y_axis = $this->getdata_yearly('AXIS');
		$y_smartfren = $this->getdata_yearly('Smartfren');
		$y_three = $this->getdata_yearly('Three');
		$y_other = $this->getdata_yearly('');

		$content_data = array(
			'gm_telkomsel' => $gm_telkomsel,
			'gm_indosat' => $gm_indosat,
			'gm_xl' => $gm_xl,
			'gm_axis' => $gm_axis,
			'gm_smartfren' => $gm_smartfren,
			'gm_three' => $gm_three,
			'gm_other' => $gm_other,
		);
		
		echo json_encode($content_data);
	}
	
}
