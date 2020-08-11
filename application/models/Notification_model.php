<?php
 
class Notification_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
    }
 
    public function mail_notif($data)
    {
        //Load email library
        $this->load->library('email');

        //SMTP & mail configuration
        $config = array(
            'protocol'      => 'smtp',
            'smtp_host'     => $this->db->get_where('configs',array('name'=>'smtp_server'))->row()->value,
            'smtp_port'     => $this->db->get_where('configs',array('name'=>'smtp_port'))->row()->value,
            'smtp_user'     => $this->db->get_where('configs',array('name'=>'smtp_user'))->row()->value,
            'smtp_pass'     => $this->db->get_where('configs',array('name'=>'smtp_pass'))->row()->value,
            'smtp_crypto'   => $this->db->get_where('configs',array('name'=>'smtp_crypto'))->row()->value,
            'mailtype' 	    => 'html',
            'charset' 	    => 'iso-8859-1'
        );
        $this->email->initialize($config);
        $this->email->set_mailtype("html");
        $this->email->set_newline("\r\n");

        //Email content
        /* $htmlContent = '<h1>Sending email via SMTP server</h1>';
        $htmlContent .= '<p>This email has sent via SMTP server from CodeIgniter application.</p>'; */

        $this->email->to($data['to']);
        $this->email->from($this->db->get_where('configs',array('name'=>'smtp_sender'))->row()->value,$this->db->get_where('configs',array('name'=>'title'))->row()->value);
        $this->email->subject($data['subject']);
        $this->email->message($data['message']);

        //Send email
        if ($this->email->send()) {
            return array(
                "status"=>TRUE,
                "reason"=>"Sukses! email berhasil dikirim"
            );
        } else {
            return array(
                "status"=>FALSE,    
                "reason"=>"Error! email tidak dapat dikirim"
            ); 
            //return $this->email->print_debugger();
            
        }

    }
 
}