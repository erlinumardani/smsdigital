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
		$this->title = 'Menu Management';
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
			'form_title'=>'New Menu Form',
			'base_url' => base_url(),
			'page' => $this->uri->segment(1),
			'csrf_token_name' => $this->security->get_csrf_token_name(),
			'csrf_hash' => $this->security->get_csrf_hash()
		);

		$roles = dropdown_render($this->db->select('id,name')->get('roles')->result_array(),null);
		$menus = dropdown_render($this->db->select('id,name')->get_where('menus',array('type'=>'Main'))->result_array(),null);

		$fieldset = array(
			array(
				'name'=>'Sequence',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-sort-numeric-down',
				'custom_attributes'=>array(
					'data-input_type' => 'numeric',
					'maxlength' => '3',
					"placeholder"=>"Sequence"
				),
			),
			array(
				'name'=>'Type',
				'type'=>'select',
				'class'=>'',
				'icon'=>'fa-layer-group',
				'custom_attributes'=>array(
				),
				'options'=>array('Single'=>'Single','Sub'=>'Sub','Main'=>'Main'),
				'default_options'=>'Single'
			),
			array(
				'name'=>'main_id',
				'label'=>'Main Menu',
				'type'=>'select',
				'class'=>'',
				'icon'=>'fa-minus',
				'custom_attributes'=>array(
					"disabled"=>true
				),
				'options'=>$menus,
				'default_options'=>''
			),
			array(
				'name'=>'name',
				'label'=>'Menu Name',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-tag',
				'custom_attributes'=>array("placeholder"=>"Menu Name")
			),
			array(
				'name'=>'URL',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-link',
				'custom_attributes'=>array("placeholder"=>"URL")
			),
			array(
				'name'=>'icon',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-th-large',
				'custom_attributes'=>array("placeholder"=>"Icon Name")
			),
			array(
				'name'=>'Status',
				'type'=>'select',
				'class'=>'',
				'icon'=>'fa-check-square',
				'custom_attributes'=>array(
				),
				'options'=>array('Active'=>'Active','Inactive'=>'Inactive'),
				'default_options'=>'Active'
			),
			array(
				'name'=>'privileges[]',
				'id'=>'privileges',
				'label'=>'Privileges',
				'type'=>'select',
				'class'=>'select2',
				'icon'=>'fa-users',
				'custom_attributes'=>array(
					"multiple"=>"multiple",
				),
				'options'=>$roles,
				'default_options'=>'1'
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

		$view_data = $this->db->where('id',$id)->get('menus')->row();
		$roles = dropdown_render($this->db->select('id,name')->get('roles')->result_array(),null);
		$menus = dropdown_render($this->db->select('id,name')->get_where('menus',array('type'=>'Main'))->result_array(),null);

		$fieldset = array(
			array(
				'name'=>'Sequence',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-sort-numeric-down',
				'custom_attributes'=>array(
					'value' => $view_data->sequence,
					'disabled' => true,
					'data-input_type' => 'numeric',
					'maxlength' => '3',
					"placeholder"=>"Sequence"
				),
			),
			array(
				'name'=>'Type',
				'type'=>'select',
				'class'=>'',
				'icon'=>'fa-layer-group',
				'custom_attributes'=>array(
					'value' => $view_data->type,
					'disabled' => true,
				),
				'options'=>array('Single'=>'Single','Sub'=>'Sub','Main'=>'Main'),
				'default_options'=>$view_data->type
			),
			array(
				'name'=>'main_id',
				'label'=>'Main Menu',
				'type'=>'select',
				'class'=>'',
				'icon'=>'fa-minus',
				'custom_attributes'=>array(
					"disabled"=>true,
					'value' => $view_data->main_id
				),
				'options'=>$menus,
				'default_options'=>$view_data->main_id
			),
			array(
				'name'=>'name',
				'label'=>'Menu Name',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-tag',
				'custom_attributes'=>array(
					"disabled"=>true,
					'value' => $view_data->name,
					"placeholder"=>"Menu Name"
					)
			),
			array(
				'name'=>'URL',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-link',
				'custom_attributes'=>array(
					"placeholder"=>"URL",
					"disabled"=>true,
					'value' => $view_data->url
				)
			),
			array(
				'name'=>'icon',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-th-large',
				'custom_attributes'=>array(
					"placeholder"=>"Icon Name",
					"disabled"=>true,
					'value' => $view_data->icon
				)
			),
			array(
				'name'=>'Status',
				'type'=>'select',
				'class'=>'',
				'icon'=>'fa-check-square',
				'custom_attributes'=>array(
					"disabled"=>true,
					'value' => $view_data->status
				),
				'options'=>array('Active'=>'Active','Inactive'=>'Inactive'),
				'default_options'=>$view_data->status
			),
			array(
				'name'=>'privileges[]',
				'id'=>'privileges',
				'label'=>'Privileges',
				'type'=>'select',
				'class'=>'select2 privileges',
				'icon'=>'fa-users',
				'custom_attributes'=>array(
					"multiple"=>"multiple",
					"disabled"=>true
				),
				'options'=>$roles,
				'default_options'=>'1'
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
			'form_title'=>'View Menu Detail',
			'base_url' => base_url(),
			'page' => $this->uri->segment(1),
			'privileges_check' => '$(".privileges").val('.$view_data->privileges.').trigger("change");',
			'csrf_token_name' => $this->security->get_csrf_token_name(),
			'csrf_hash' => $this->security->get_csrf_hash()
		);

		$content_data['form'] = form_render('initiate_form', $fieldset, TRUE);
        page_view($this->title, 'view', $content_data);
	}

	function update($id)
	{

		$view_data = $this->db->where('id',$id)->get('menus')->row();
		$roles = dropdown_render($this->db->select('id,name')->get('roles')->result_array(),null);
		$menus = dropdown_render($this->db->select('id,name')->get_where('menus',array('type'=>'Main'))->result_array(),null);

		$fieldset = array(
			array(
				'name'=>'Sequence',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-sort-numeric-down',
				'custom_attributes'=>array(
					'value' => $view_data->sequence,
					
					'data-input_type' => 'numeric',
					'maxlength' => '3',
					"placeholder"=>"Sequence"
				),
			),
			array(
				'name'=>'Type',
				'type'=>'select',
				'class'=>'',
				'icon'=>'fa-layer-group',
				'custom_attributes'=>array(
					'value' => $view_data->type,
					
				),
				'options'=>array('Single'=>'Single','Sub'=>'Sub','Main'=>'Main'),
				'default_options'=>$view_data->type
			),
			array(
				'name'=>'main_id',
				'label'=>'Main Menu',
				'type'=>'select',
				'class'=>'',
				'icon'=>'fa-minus',
				'custom_attributes'=>array(
					'value' => $view_data->main_id
				),
				'options'=>$menus,
				'rules'=>array("required"=>FALSE),
				'default_options'=>$view_data->main_id
			),
			array(
				'name'=>'name',
				'label'=>'Menu Name',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-tag',
				'custom_attributes'=>array(
					
					'value' => $view_data->name,
					"placeholder"=>"Menu Name"
					)
			),
			array(
				'name'=>'URL',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-link',
				'custom_attributes'=>array(
					"placeholder"=>"URL",
					
					'value' => $view_data->url
				)
			),
			array(
				'name'=>'icon',
				'type'=>'text',
				'class'=>'',
				'icon'=>'fa-th-large',
				'custom_attributes'=>array(
					"placeholder"=>"Icon Name",
					
					'value' => $view_data->icon
				)
			),
			array(
				'name'=>'Status',
				'type'=>'select',
				'class'=>'',
				'icon'=>'fa-check-square',
				'custom_attributes'=>array(
					
					'value' => $view_data->status
				),
				'options'=>array('Active'=>'Active','Inactive'=>'Inactive'),
				'default_options'=>$view_data->status
			),
			array(
				'name'=>'privileges[]',
				'id'=>'privileges',
				'label'=>'Privileges',
				'type'=>'select',
				'class'=>'select2 privileges',
				'icon'=>'fa-users',
				'custom_attributes'=>array(
					"multiple"=>"multiple"
				),
				'options'=>$roles,
				'default_options'=>'1'
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
			'privileges_check' => '$(".privileges").val('.$view_data->privileges.').trigger("change");',
			'csrf_token_name' => $this->security->get_csrf_token_name(),
			'csrf_hash' => $this->security->get_csrf_hash()
		);

		$content_data['form'] = form_render('initiate_form', $fieldset, TRUE);
        page_view($this->title, 'update', $content_data);
	}

	function form_submit()
	{
		$table = "menus";
		$data = $this->input->post();
		$data['updated_by'] = $this->session->userdata('user_id');
		$action = $data['action'];
		unset($data['action']);
		
		if(isset($data['id'])){
			$id = $data['id'];
			unset($data['id']);
		}

		$data['privileges'] = str_replace('"','',json_encode($data['privileges']));

		$this->db->trans_start();
		if($action == "Update"){
			$this->db->where('id',$id)->update('menus',$data);
		}else{
			$this->db->insert('menus',$data);
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
		$table = 'menus'; //nama tabel dari database
		$column_order = array(null, 'type','name','url','status'); //field yang ada di table user
		$column_search = array('type','name','url','status'); //field yang diizin untuk pencarian 
		$order = array('created_at' => 'desc'); // default order 
		
		$this->load->model('datatable_model');

        $list = $this->datatable_model->get_datatables($table, $column_order, $column_search, $order);
        $data = array();
		$no = $_POST['start'];
		
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $field->type;
            $row[] = $field->name;
            $row[] = $field->url;
            $row[] = $field->status;
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

		if($this->db->where('id',$id)->delete('menus')){
			$result = array("status"=>TRUE,"message"=>"Data has been deleted");
		}else{
			$result = array("status"=>FALSE,"message"=>"Data failed to be deleted");
		}

		echo json_encode($result);
	}
}
