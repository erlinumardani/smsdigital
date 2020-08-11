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
		$this->title = 'User Guide';
	}

	function index()
	{
		redirect('docsop/data/view');
	}
    
	function view($type = 'invoice_format')
	{

		if($type=="app_guidance"){
		
			switch ($this->session->userdata('logged_in')) {
				case 11:
					$doc = '<object width="100%" height="600" data="'.base_url().'user_guide/vendor_guide.pdf"></object>';
					break;
				
				default:
					$doc = '';
					break;
			}
		
		}elseif($type=="invoice_format"){

			$doc = '<object width="100%" height="600" data="'.base_url().'user_guide/invoice_format.pdf"></object>';

		}else{

			$doc = '';
			
		}


		$content_data = array(
			'base_url' => base_url(),
			'page' => $this->uri->segment(1),
			'doc' => $doc
		);
		
		page_view($this->title, 'view', $content_data);
    }
	
}
