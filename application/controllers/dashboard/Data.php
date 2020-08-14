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
    }
    
	function index()
	{

		if($this->role_id=="3"){
			$limit = $this->db->select('sms_limit')->get_where('users',array('id'=>$this->session->userdata('user_id')))->row()->sms_limit;
			$total_sms = $this->db->select('count(id) as total')->get_where('sms_transactions',array('updated_by'=>$this->session->userdata('user_id')))->row()->total;
			$sms_otomatis = $this->db->select('count(id) as total')->get_where('sms_transactions','schedule > now() and type = "Schedule"')->row()->total;
			$contacts = $this->db->select('count(id) as total')->get('sms_contacts')->row()->total;

			if($total_sms>0 && $limit>0){
				$limit_persent = number_format($total_sms/$limit * 100);
			}else{
				$limit_persent = 0;
			}
			
		}else{
			$limit = $this->db->select('sms_limit')->get_where('users',array('id'=>$this->session->userdata('user_id')))->row()->sms_limit;
			$total_sms = $this->db->select('count(id) as total')->get_where('sms_transactions',array('tenant_id'=>$this->tenant_id))->row()->total;
			$sms_otomatis = $this->db->select('count(id) as total')->get_where('sms_transactions','schedule > now() and type = "Schedule" and tenant_id = '.$this->tenant_id)->row()->total;
			$contacts = $this->db->select('count(id) as total')->get('sms_contacts')->row()->total;
			$limit_persent = 0;
		}

		$content_data = array(
			'base_url' => base_url(),
			'page' => $this->uri->segment(1),
			'limit' => number_format($limit),
			'total_sms' => number_format($total_sms),
			'limit_persent' => $limit_persent,
			'sms_otomatis' => $sms_otomatis,
			'contacts' => $contacts
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
    
	
}
