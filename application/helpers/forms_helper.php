<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function form_vendor_data($form_data){
    return array(
        array(
            'name'=>'field_1',
            'label'=>'Jenis Dokumen',
            'type'=>'select',
            'options'=>$form_data['doctypes'],
            'default_options'=>'1'
        ),
        array(
            'name'=>'field_2',
            'label'=>'ID Vendor',
            'type'=>'text',
            'custom_attributes'=>array(
                'maxlength' => '20'
            ),
        ),
        array(
            'name'=>'field_3',
            'label'=>'Nama Vendor',
            'type'=>'text',
            'custom_attributes'=>array(
                'maxlength' => '100'
            ),
        ),
        array(
            'name'=>'field_4',
            'label'=>'Nama Bank',
            'type'=>'text',
            'custom_attributes'=>array(
                'maxlength' => '100'
            ),
        ),
        array(
            'name'=>'field_5',
            'label'=>'Nomor Rekening',
            'type'=>'text',
            'custom_attributes'=>array(
                'maxlength' => '50'
            ),
        ),
        array(
            'name'=>'field_6',
            'label'=>'Nama Pemegang Rekening',
            'type'=>'text',
            'custom_attributes'=>array(
                'maxlength' => '100'
            ),
        ),
        /* array(
            'name'=>'field_7',
            'label'=>'PIC & Nomor Telp Vendor',
            'type'=>'text',
            'custom_attributes'=>array(
                'maxlength' => '50'
            ),
        ) */
    );
}

function form_invoice_data($form_data){
    return array(
        array(
            'name'=>'field_8',
            'label'=>'Nomor Invoice',
            'type'=>'text',
            'custom_attributes'=>array(
                'data-input_type' => 'number'
            ),
        ),
        array(
            'name'=>'field_9',
            'label'=>'Nilai Invoice',
            'type'=>'text',
            'custom_attributes'=>array(
                'data-input_type' => 'currency'
            ),
        ),
        array(
            'name'=>'field_10',
            'label'=>'Nama Pekerjaan',
            'type'=>'text'
        ),
        array(
            'name'=>'field_11',
            'label'=>'Tanggal Invoice',
            'type'=>'text',
            'class'=>'datetime'
        ),
        array(
            'name'=>'field_12',
            'label'=>'Tanggal Jatuh Tempo',
            'type'=>'text',
            'rules'=>array(
                'required'=>false
            ),
            'class'=>'datetime'
        ),
        array(
            'name'=>'field_13',
            'label'=>'Tanggal Diterima Oleh Prosup',
            'type'=>'text',
            'class'=>'datetime'
        )
    );

}

function form_verifikasi_pajak($form_data=array()){
    return array(
        array(
            'name'=>'field_14',
            'label'=>'Nilai Invoice',
            'class'=>'additional_fields',
            'type'=>'text',
            'custom_attributes'=>array(
                'data-input_type' => 'currency'
            )
        ),
        array(
            'name'=>'field_15',
            'label'=>'DPP',
            'class'=>'additional_fields count',
            'type'=>'text',
            'custom_attributes'=>array(
                'data-input_type' => 'currency'
            )
        ),
        array(
            'name'=>'field_16',
            'label'=>'PPN',
            'class'=>'additional_fields count',
            'type'=>'text',
            'rules'=>array(
                'required'=>false
            ),
            'custom_attributes'=>array(
                'data-input_type' => 'currency'
            )
        ),
        array(
            'name'=>'field_17',
            'label'=>'PPH 23',
            'class'=>'additional_fields count',
            'type'=>'text',
            'rules'=>array(
                'required'=>false
            ),
            'custom_attributes'=>array(
                'data-input_type' => 'currency'
            )
        ),
        array(
            'name'=>'field_18',
            'label'=>'PPH Final',
            'class'=>'additional_fields count',
            'type'=>'text',
            'rules'=>array(
                'required'=>false
            ),
            'custom_attributes'=>array(
                'data-input_type' => 'currency'
            )
        ),
        array(
            'name'=>'field_30',
            'label'=>'Jumlah Yang Dibayarkan',
            'class'=>'additional_fields count',
            'type'=>'text',
            'rules'=>array(
                'required'=>false
            ),
            'custom_attributes'=>array(
                'data-input_type' => 'currency',
                'readonly' => true,
            )
        )
    );
}

function form_corfin($form_data=array()){
    return array(
        array(
            'name'=>'field_22',
            'class'=>'approvalcheck additional_fields datetime',
            'label'=>'Approval Manager',
            'type'=>'text',
            //'rules'=>array('required'=>false),
            'custom_attributes'=>array('value'=>$form_data['field_22'])
        ),
        array(
            'name'=>'field_23',
            'class'=>'approvalcheck additional_fields datetime',
            'label'=>'Approval VP',
            'type'=>'text',
            //'rules'=>array('required'=>false),
            'custom_attributes'=>array('value'=>$form_data['field_23'])
        ),
        array(
            'name'=>'field_24',
            'class'=>'approvalcheck additional_fields datetime',
            'label'=>'Approval CA',
            'type'=>'text',
            'rules'=>array('required'=>false),
            'custom_attributes'=>array('value'=>$form_data['field_24'])
        ),
        array(
            'name'=>'field_25',
            'class'=>'approvalcheck additional_fields datetime',
            'label'=>'Approval Dirop',
            'type'=>'text',
            'rules'=>array('required'=>false),
            'custom_attributes'=>array('value'=>$form_data['field_25'])
        ),
        array(
            'name'=>'field_26',
            'class'=>'approvalcheck additional_fields datetime',
            'label'=>'Approval Dirkug',
            'type'=>'text',
            //'rules'=>array('required'=>false),
            'custom_attributes'=>array('value'=>$form_data['field_26'])
        ),
        array(
            'name'=>'field_27',
            'class'=>'approvalcheck additional_fields datetime',
            'label'=>'Approval Dirut',
            'type'=>'text',
            //'rules'=>array('required'=>false),
            'custom_attributes'=>array('value'=>$form_data['field_27'])
        ),
        array(
            'name'=>'field_28',
            'class'=>'approvalcheck additional_fields datetime',
            'label'=>'Payment Date Plan',
            'type'=>'text',
            'custom_attributes'=>array('value'=>$form_data['field_28'])
        ),
        array(
            'name'=>'field_29',
            'class'=>'approvalcheck additional_fields datetime',
            'label'=>'Payment Date',
            'type'=>'text',
            'custom_attributes'=>array('value'=>$form_data['field_29'])
        ),
        array(
            'name'=>'field_30',
            'label'=>'Jumlah Yang Dibayarkan',
            'type'=>'text',
            'custom_attributes'=>array('value'=>$form_data['field_30'],'disabled'=>true)
        ),
        array(
            'name'=>'field_31',
            'class'=>'approvalcheck additional_fields',
            'label'=>'Status Payment',
            'type'=>'select',
            'options'=>array('Sirkuler TTD'=>'Sirkuler TTD','Waiting For Payment'=>'Waiting For Payment','Paid'=>'Paid'),
            'default_options'=>$form_data['field_31'],
        ),
    );
}

function additional_information($form_data){

    return array(
        array(
            'name'=>'field_14',
            'label'=>'Nilai Invoice',
            'type'=>'text',
            'value'=>$form_data['field_14']
        ),
        array(
            'name'=>'field_15',
            'label'=>'DPP',
            'type'=>'text',
            'value'=>$form_data['field_15']
        ),
        array(
            'name'=>'field_16',
            'label'=>'PPN',
            'type'=>'text',
            'value'=>$form_data['field_16']
        ),
        array(
            'name'=>'field_17',
            'label'=>'PPH 23',
            'type'=>'text',
            'value'=>$form_data['field_17']
        ),
        array(
            'name'=>'field_18',
            'label'=>'PPH Final',
            'type'=>'text',
            'value'=>$form_data['field_18']
        ),
        array(
            'name'=>'field_19',
            'label'=>'Nomor TP',
            'type'=>'text',
            'value'=>$form_data['field_19']
        ),
        array(
            'name'=>'field_20',
            'label'=>'Nomor SPB',
            'type'=>'text',
            'value'=>$form_data['field_20']
        ),
        array(
            'name'=>'field_21',
            'label'=>'Nomor House Bank',
            'type'=>'text',
            'value'=>$form_data['field_21']
        ),
        array(
            'name'=>'field_30',
            'label'=>'Jumlah Yang Dibayarkan',
            'type'=>'text',
            'value'=>$form_data['field_30']
        ),
        array(
            'name'=>'field_22',
            'label'=>'Approval Manager',
            'type'=>'text',
            'value'=>$form_data['field_22']
        ),
        array(
            'name'=>'field_23',
            'label'=>'Approval VP',
            'type'=>'text',
            'value'=>$form_data['field_23']
        ),
        array(
            'name'=>'field_24',
            'label'=>'Approval CA',
            'type'=>'text',
            'value'=>$form_data['field_24']
        ),
        array(
            'name'=>'field_25',
            'label'=>'Approval Dirop',
            'type'=>'text',
            'value'=>$form_data['field_25']
        ),
        array(
            'name'=>'field_26',
            'label'=>'Approval Dirkug',
            'type'=>'text',
            'value'=>$form_data['field_26']
        ),
        array(
            'name'=>'field_27',
            'label'=>'Approval Dirut',
            'type'=>'text',
            'value'=>$form_data['field_27']
        ),
        array(
            'name'=>'field_28',
            'label'=>'Payment Date Plan',
            'type'=>'text',
            'value'=>$form_data['field_28']
        ),
        array(
            'name'=>'field_29',
            'label'=>'Payment Date',
            'type'=>'text',
            'value'=>$form_data['field_29']
        ),
        array(
            'name'=>'field_31',
            'label'=>'Status Payment',
            'type'=>'text',
            'value'=>$form_data['field_31']
        ),
        array(
            'name'=>'field_32',
            'label'=>'Nomor Kabinet',
            'type'=>'text',
            'value'=>$form_data['field_32']
        ),
        array(
            'name'=>'field_33',
            'label'=>'Nomor Rak',
            'type'=>'text',
            'value'=>$form_data['field_33']
        ),
        array(
            'name'=>'field_34',
            'label'=>'Currency',
            'type'=>'text',
            'value'=>$form_data['field_34']
        ),
        array(
            'name'=>'field_35',
            'label'=>'Nomor Faktur Pajak',
            'type'=>'text',
            'value'=>$form_data['field_35']
        ),
        array(
            'name'=>'field_36',
            'label'=>'Nomor PO',
            'type'=>'text',
            'value'=>$form_data['field_36']
        ),
        array(
            'name'=>'field_37',
            'label'=>'NPWP',
            'type'=>'text',
            'value'=>$form_data['field_37']
        ),
        array(
            'name'=>'field_38',
            'label'=>'Nomor PKS',
            'type'=>'text',
            'value'=>$form_data['field_38']
        ),
    );
    
}

function form_vendor_request($form_data=array()){
    return array(
        array(
            'name'=>'field_2',
            'label'=>'ID Vendor',
            'type'=>'text',
            'custom_attributes'=>array(
                'value' => $form_data['vendor_code'],
                "readonly" => true
            ),
        ),
        array(
            'name'=>'field_3',
            'label'=>'Nama Vendor',
            'type'=>'text',
            'custom_attributes'=>array(
                'value' => $form_data['vendor_name'],
                "readonly" => true
            ),
        ),
        array(
            'name'=>'field_10',
            'label'=>'Nama Pekerjaan',
            'type'=>'text',
            'custom_attributes'=>array(
                'maxlength' => '50'
            ),
        ),
        array(
            'name'=>'field_7',
            'label'=>'Nomor Kontrak',
            'type'=>'text',
            'rules'=>array(
                'required'=>false
            ),
            'custom_attributes'=>array(
                'maxlength' => '50'
            ),
        ),
    );
}

function form_vendor_request_complete($form_data){
    return array(
        array(
            'name'=>'field_1',
            'label'=>'Jenis Dokumen',
            'type'=>'select',
            'rules'=>array(
                'required'=>false
            ),
            'options'=>$form_data['doctypes'],
            'default_options'=>'1'
        ),
        array(
            'name'=>'field_2',
            'label'=>'ID Vendor',
            'type'=>'text',
            'custom_attributes'=>array(
                'value' => $form_data['vendor_code'],
                "readonly" => true
            ),
        ),
        array(
            'name'=>'field_3',
            'label'=>'Nama Vendor',
            'type'=>'text',
            'custom_attributes'=>array(
                'value' => $form_data['vendor_name'],
                "readonly" => true
            ),
        ),
        array(
            'name'=>'field_4',
            'label'=>'Nama Bank',
            'type'=>'text',
            'custom_attributes'=>array(
                'maxlength' => '100'
            ),
        ),
        array(
            'name'=>'field_5',
            'label'=>'Nomor Rekening',
            'type'=>'text',
            'custom_attributes'=>array(
                'maxlength' => '50'
            ),
        ),
        array(
            'name'=>'field_6',
            'label'=>'Nama Pemegang Rekening',
            'type'=>'text',
            'custom_attributes'=>array(
                'maxlength' => '100'
            ),
        ),
        array(
            'name'=>'field_7',
            'label'=>'Nomor Kontrak',
            'type'=>'text',
            'rules'=>array(
                'required'=>false
            ),
            'custom_attributes'=>array(
                'maxlength' => '50',
                "readonly" => true
            ),
        ),
    );
}

function form_invoice_data_by_vendor($form_data){
    return array(
        array(
            'name'=>'field_8',
            'label'=>'Nomor Invoice',
            'type'=>'text',
            'custom_attributes'=>array(
                'data-input_type' => 'number'
            ),
        ),
        array(
            'name'=>'field_9',
            'label'=>'Nilai Invoice',
            'type'=>'text',
            'custom_attributes'=>array(
                'data-input_type' => 'currency'
            ),
        ),
        array(
            'name'=>'field_34',
            'label'=>'Currency',
            'type'=>'select',
            'options'=>$form_data['currency'],
            'default_options'=>''
        ),
        array(
            'name'=>'field_10',
            'label'=>'Nama Pekerjaan',
            'type'=>'text',
            'custom_attributes'=>array(
                "readonly" => true
            ),
        ),
        array(
            'name'=>'field_35',
            'label'=>'Nomor Faktur Pajak',
            'type'=>'text',
            'rules'=>array(
                'required'=>false
            ),
        ),
        array(
            'name'=>'field_36',
            'label'=>'Nomor PO',
            'type'=>'text',
            'rules'=>array(
                'required'=>false
            ),
        ),
        array(
            'name'=>'field_37',
            'label'=>'NPWP',
            'type'=>'text',
            'rules'=>array(
                'required'=>false
            ),
        ),
        array(
            'name'=>'field_38',
            'label'=>'Nomor PKS',
            'type'=>'text',
            'rules'=>array(
                'required'=>false
            ),
        ),
        array(
            'name'=>'field_11',
            'label'=>'Tanggal Invoice',
            'type'=>'text',
            'class'=>'datetime'
        ),
        array(
            'name'=>'field_12',
            'label'=>'Tanggal Jatuh Tempo',
            'type'=>'text',
            'rules'=>array(
                'required'=>false
            ),
            'class'=>'datetime'
        ),
        array(
            'name'=>'field_13',
            'label'=>'Tanggal Diterima Oleh Prosup',
            'type'=>'text',
            'rules'=>array(
                'required'=>false
            ),
            'class'=>'datetime'
        )
    );

}