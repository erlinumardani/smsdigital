<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function page_view($title = '', $view_name = '', $content_data = array(), $extra_data = array()){
    $CI = &get_instance();

    $configs = array();
    foreach($CI->db->get_where('configs')->result_array() as $config){
        $configs[$config['name']] = $config['value'];
    }

    $data = array(
        'content' => $CI->load->view($CI->uri->segment(1).'/'.$view_name, $content_data, TRUE),
        'extrascript' => $CI->load->view($CI->uri->segment(1).'/'.'js', $content_data, TRUE),
        'content_title' => $title,
        'base_url' => base_url(),
        'menus' => get_menus(),
        "csrf_token_name" => $CI->security->get_csrf_token_name(),
        "csrf_hash" => $CI->security->get_csrf_hash()
    );
    
    return $CI->parser->parse('layout', array_merge($data, $extra_data, $configs));
}

function get_menus(){
    $CI = &get_instance();

    $get_menus = $CI->db
        ->select('*')
        ->from('menus')
        ->where('type in("Single","Main")')
        ->where('status','Active')
        ->where("JSON_CONTAINS(`privileges`, '[".$CI->session->userdata('role_id')."]')")
        ->order_by('sequence','asc')
        ->get()
        ->result();

    $menus = '';

    if(isset($get_menus)){
        foreach ($get_menus as $menu) {

            if(strpos($menu->url,'http://')>0){
                $url = $menu->url;
            }else{
                $url = base_url().$menu->url;
            }

            if($menu->type == 'Main'){

                $menus .= '

                    <li class="dropdown"><a href="#"><i class="'.$menu->icon.'"></i> '.$menu->name.'</a>                              
                    <ul>

                ';

                $get_submenus = $CI->db
                    ->select('*')
                    ->from('menus')
                    ->where('type','Sub')
                    ->where('main_id',$menu->id)
                    ->where('status','Active')
                    ->where("JSON_CONTAINS(`privileges`, '[".$CI->session->userdata('role_id')."]')")
                    ->order_by('sequence','asc')
                    ->get()->result();
                
                if(isset($get_submenus)){
                    foreach ($get_submenus as $submenu) {
                        if(strpos($submenu->url,'http://')>0){
                            $url = $submenu->url;
                        }else{
                            $url = base_url().$submenu->url;
                        }

                        if(isset(explode("/",$submenu->url)[2])){
                            $subid = '-'.explode("/",$submenu->url)[2];
                        }else{
                            $subid = '';
                        }

                        $menus .= '

                            <li id="'.explode("/",$submenu->url)[0].$subid.'">
                                <a href="'.$url.'">
                                <i class="'.$submenu->icon.'"></i> '.$submenu->name.'</a>
                            </li>
                        '; 
                    }
                }

                $menus .= '</ul></li>';

            }else{

                $menus .= '

                    <li class="dropdown">
                        <a href="#">
                        <i class="'.$menu->icon.'"></i> '.$menu->name.'</a>
                        <ul>
                            <li id="'.explode("/",$menu->url)[0].'">
                                <a href="'.$url.'">
                                <i class="'.$menu->icon.'"></i> '.$menu->name.'</a>
                            </li>
                        </ul>
                    </li>
                '; 

            }
        }
    }

    return $menus;
}   

function form_render($form_id = '', $fieldset = array(), $split = FALSE, $validate=true, $then="window.history.go(-1);", $url = "form_submit", $extended = array()){
    $CI = &get_instance();
    $result = '<input name="'.$CI->security->get_csrf_token_name().'" type="hidden" value="'.$CI->security->get_csrf_hash().'">';
    $fieldcount = 0;

    foreach ($fieldset as $field) {
        if($field['type']!="hidden"){
            $fieldcount++;
        }
    }

    $sequence = 1;
    foreach ($fieldset as $field){
        $field_name = str_replace(' ', '_', strtolower($field['name']));

        if(isset($field['label'])){
            $label = $field['label'];
        }else{
            $label = $field['name'];
        }
        if(isset($field['class'])){
            $field['class'] = $field['class'];
        }else{
            $field['class'] = "";
        }
        if(isset($field['icon'])){
            $field['icon'] = $field['icon'];
        }else{
            $field['icon'] = "fa-terminal";
        }
        if(isset($field['custom_attributes']['placeholder'])){
            $field['custom_attributes']['placeholder'] = $field['custom_attributes']['placeholder'];
        }else{
            $field['custom_attributes']['placeholder'] = isset($field['label'])?$field['label']:$field['name'];
        }
        if(isset($field['id'])){
            $field_id = $field['id'];
        }else{
            $field_id = $field_name;
        }

        $field_detail = array(
            'type'  => $field['type'],
            'name'  => $field_name,
            'id'    => $field_id,
            'class' => 'form-control '.$field['class']
        );

        if($split == TRUE && $sequence ==1  && $field['type'] != 'hidden'){
            $result .= '<div class="row"><div class="col-md-6">';
        }

        if($split == TRUE && round($fieldcount/2)+1 == $sequence && $field['type'] != 'hidden'){
            $result .= '</div><div class="col-md-6">';
        }

        if($field['type'] != 'hidden'){
            $result .= '<div class="form-group">';
            $result .= form_label($label, $field_id, array());
            $result .= '<div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas '.$field['icon'].'"></i></span>
                </div>
            ';
        }

        if($field['type']=='select'){
            $result .= form_dropdown($field_name, $field['options'], $field['default_options'],'class="form-control '.$field['class'].'" id="'.$field_id.'" '.http_build_query($field['custom_attributes'],'',' '));
        }elseif($field['type']=='textarea'){
            $result .= form_textarea(array_merge($field_detail,$field['custom_attributes'],array('rows'=>2)));
        }elseif($field['type']=='checkbox'){
            $result .= form_checkbox(array_merge($field_detail,$field['custom_attributes']));
        }else{
            $result .= form_input(array_merge($field_detail,$field['custom_attributes']));
        }
        

        if($field['type'] != 'hidden'){
            $result .= '</div></div>';
        }

        if($split == TRUE && $fieldcount == $sequence  && $field['type'] != 'hidden'){
            $result .= '</div></div>';
        }

        if($field['type'] != 'hidden'){
            $sequence ++;
        }
       
        
    }

    if(count($extended)>0){
        $fieldset = array_merge($fieldset,$extended);
    }

    if($validate==true){
        $result .= form_validation($fieldset,$form_id,$then,$url);
    }
    return $result;
}

function dropdown_render($array,$default){
    $options = array();
    if(isset($default)){
        $options[$default['key']] = $default['value'];
    }else{
        $options[''] = 'Pilih';
    }
    
    foreach ($array as $row) {
        $options[$row['id']] = $row['name'];
    }
    return $options;
}

function form_validation($fieldset,$form_id,$then, $url = "form_submit"){
    $CI = &get_instance();
    $rules = array();
    $script = '';
    foreach($fieldset as $field){
        $field_name = str_replace(' ', '_', strtolower($field['name']));
        $rules[$field_name] = isset($field['rules'])?$field['rules']:array("required"=>TRUE);
    }
   
    $script .= "<script>";
    $script .= "$('#".$form_id."').validate({
        submitHandler: function () {
            var form = document.getElementById('".$form_id."');
            var formData = new FormData(form);

            $('.preloader').fadeIn('fast');

            $.ajax({
                url: '".base_url().$CI->uri->segment(1)."/data/".$url."',
                enctype: 'multipart/form-data',
                data: formData,
                processData: false,
                contentType: false,
                type: 'POST',
                dataType: 'json',
            })
            .done(function(data) {
                $('.preloader').fadeOut('fast');  
                if(data.status==true){
                    Swal.fire({
                        type: 'success',
                        title: 'Success',
                        text: data.message,
                        showConfirmButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'ok'
                    }).then(function(){
                        ".$then."
                    });
                }else{
                     Swal.fire({
                        type: 'error',
                        title: 'Failed',
                        text: data.message,
                        showConfirmButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'ok'
                    });
                }    
            })
        },
        rules: ".json_encode($rules).",
        messages: {
            email: {
                required: \"Please enter a email address\",
                email: \"Please enter a vaild email address\"
            },
            password: {
                required: \"Please provide a password\",
                minlength: \"Your password must be at least 5 characters long\"
            }
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });";
    $script .= "</script>";
    
    return $script;
}