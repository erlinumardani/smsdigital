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
    }
    
	function index()
	{
		$content_data = array(
			'base_url' => base_url(),
			'page' => $this->uri->segment(1)
		);
		
		page_view($this->title, 'view', $content_data);
	}

	function search()
    {

		$content_data = array(
			'base_url' => base_url(),
			'csrf_token_name' => $this->security->get_csrf_token_name(),
			'csrf_hash' => $this->security->get_csrf_hash(),
			'page' => $this->uri->segment(1)
		);
		
		page_view('Quick Search', 'data', $content_data);

	}

	function list()
    {	
		$column_order = array(null,'flow_ticket_id','field_1', 'field_3', 'field_8','field_10','field_9','field_11','flow_node_name','field_19','field_20','field_22','field_23','field_24','field_25','field_26','field_27','field_31','field_30','field_28','field_29',); //field yang ada di table user
		$column_search = array('flow_ticket_id','field_1', 'field_3', 'field_8','field_10','field_9','field_11','flow_node_name','field_19','field_20','field_22','field_23','field_24','field_25','field_26','field_27','field_31','field_30','field_28','field_29',); //field yang diizin untuk pencarian 
		$order = array('flow_ticket_id' => 'asc'); // default order 
		$table = 'v_request';
		$filter = '';
		
		$this->load->model('datatable_model');

		$list = $this->datatable_model->get_datatables($table, $column_order, $column_search, $order);
        
        $data = array();
		$no = $_POST['start'];
		
        foreach ($list as $field) {

			switch ($field->flow_node_type) {
				case 'Start':
					$label = 'default';
					break;
				case 'IO':
					$label = 'primary';
					break;
				case 'Process':
					$label = 'warning';
					break;
				case 'Decision':
					$label = 'warning';
					break;
				case 'End':
					$label = 'success';
					break;
				
				default:
					$label = 'primary';
					break;
			}

			switch ($field->field_31) {
				case 'Sirkuler TTD':
					$payment_status = 'info';
					break;
				case 'Waiting For Payment':
					$payment_status = 'warning';
					break;
				case 'Paid':
					$payment_status = 'success';
					break;
				
				default:
					$payment_status = 'info';
					break;
			}

            $no++;
            $row = array();
			$row[] = $no;
			$row[] = $field->flow_ticket_id;
			$row[] = $field->field_1==""?"":$this->db->get_where('doctypes','id = "'.$field->field_1.'"')->row()->name;
			$row[] = $field->field_3;
			$row[] = $field->field_8;
			$row[] = $field->field_10;
			$row[] = $field->field_9;
			$row[] = $field->field_11;
			$row[] = '<span class="right badge badge-'.$label.'">'.$field->flow_node_name.'</span>';
			$row[] = $field->field_19;
			$row[] = $field->field_20;
			$row[] = $field->field_22;
			$row[] = $field->field_23;
			$row[] = $field->field_24;
			$row[] = $field->field_25;
			$row[] = $field->field_26;
			$row[] = $field->field_27;
			$row[] = '<span class="right badge badge-'.$payment_status.'">'.$field->field_31.'</span>';
			$row[] = $field->field_30;
			$row[] = $field->field_28;
			$row[] = $field->field_29;
			$row[] = base64_encode($this->encryption->encrypt($field->flow_request_id));
 
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
    
	
}
