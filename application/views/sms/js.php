<link rel="stylesheet" href="<?=$base_url?>assets/pick/dist/vendors/datatable/css/dataTables.bootstrap4.min.css" />
<link rel="stylesheet" href="<?=$base_url?>assets/pick/dist/vendors/datatable/buttons/css/buttons.bootstrap4.min.css"/>

<script src="<?=$base_url?>assets/pick/dist/vendors/datatable/js/jquery.dataTables.min.js"></script> 
<script src="<?=$base_url?>assets/pick/dist/vendors/datatable/js/dataTables.bootstrap4.min.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/datatable/jszip/jszip.min.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/datatable/pdfmake/pdfmake.min.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/datatable/pdfmake/vfs_fonts.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/datatable/buttons/js/dataTables.buttons.min.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/datatable/buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/datatable/buttons/js/buttons.colVis.min.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/datatable/buttons/js/buttons.flash.min.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/datatable/buttons/js/buttons.html5.min.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/datatable/buttons/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {

    $('#client_group').prop('disabled',true);
    $('#client_group').parent().parent().hide();

    $('#contact_type').on('change',function() {
      if(this.value == "Client Group"){
        $('#client_group').prop('disabled',false);
        $('#client_group').parent().parent().show();

        $('#phonebook').prop('disabled',true);
        $('#phonebook').parent().parent().hide();
      }else{
        $('#client_group').prop('disabled',true);
        $('#client_group').parent().parent().hide();

        $('#phonebook').prop('disabled',false);
        $('#phonebook').parent().parent().show();
      }
    });
  
    var history_data = $('#history_data').dataTable({ 
        dom:
            "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        orderCellsTop: true,
        fixedHeader: true,
        buttons:[
            {
                extend:    'copyHtml5',
                text:      '<i class="fas fa-clipboard"></i>',
                className: 'btn btn-primary',
                titleAttr: 'Copy to Clipboard'
            },
            {
                extend:    'excelHtml5',
                text:      '<i class="fas fa-file-excel"></i>',
                className: 'btn btn-primary',
                titleAttr: 'Export to Excel'
            },
            {
                extend:    'csvHtml5',
                text:      '<i class="fas fa-file-csv"></i>',
                className: 'btn btn-primary',
                titleAttr: 'Export to CSV'
            }
        ],
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "processing": true, 
        "serverSide": true, 
        "scrollX": true,
        "order": [], 
        "ajax": {
            "url": "<?=$base_url.$page?>/data/history_data",
            "data":{"<?=$csrf_token_name?>":"<?=$csrf_hash?>"},
            "type": "POST"
        },
        "columnDefs": [
            { 
                "targets": [ 0 ], 
                "orderable": false, 
            }
        ]

    });

    $('#get_history').on('click',function() {
        history_data.fnDestroy();
        history_data = $('#history_data').dataTable({ 
            dom:
                "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            orderCellsTop: true,
            fixedHeader: true,
            buttons:[
                {
                    extend:    'copyHtml5',
                    text:      '<i class="fas fa-clipboard"></i>',
                    className: 'btn btn-primary',
                    titleAttr: 'Copy to Clipboard'
                },
                {
                    extend:    'excelHtml5',
                    text:      '<i class="fas fa-file-excel"></i>',
                    className: 'btn btn-primary',
                    titleAttr: 'Export to Excel'
                },
                {
                    extend:    'csvHtml5',
                    text:      '<i class="fas fa-file-csv"></i>',
                    className: 'btn btn-primary',
                    titleAttr: 'Export to CSV'
                }
            ],
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "processing": true, 
            "serverSide": true, 
            "scrollX": true,
            "order": [], 
            "ajax": {
                "url": "<?=$base_url.$page?>/data/history_data",
                "data":{"<?=$csrf_token_name?>":"<?=$csrf_hash?>","startdate":$("#startdate").val(),"enddate":$("#enddate").val()},
                "type": "POST"
            },
            "columnDefs": [
                { 
                    "targets": [ 0 ], 
                    "orderable": false, 
                }
            ]

        });
    });

    var otomatis_data = $('#otomatis_data').dataTable({ 
        dom:
            "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        orderCellsTop: true,
        fixedHeader: true,
        buttons:[
            {
                extend:    'copyHtml5',
                text:      '<i class="fas fa-clipboard"></i>',
                className: 'btn btn-primary',
                titleAttr: 'Copy to Clipboard'
            },
            {
                extend:    'excelHtml5',
                text:      '<i class="fas fa-file-excel"></i>',
                className: 'btn btn-primary',
                titleAttr: 'Export to Excel'
            },
            {
                extend:    'csvHtml5',
                text:      '<i class="fas fa-file-csv"></i>',
                className: 'btn btn-primary',
                titleAttr: 'Export to CSV'
            }
        ],
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "processing": true, 
        "serverSide": true, 
        "scrollX": true,
        "order": [], 
        "ajax": {
            "url": "<?=$base_url.$page?>/data/otomatis_data",
            "data":{"<?=$csrf_token_name?>":"<?=$csrf_hash?>"},
            "type": "POST"
        },
        "columnDefs": [
            { 
                "targets": [ 0 ], 
                "orderable": false, 
            }
        ]

    });

    $('#get_otomatis').on('click',function() {
        otomatis_data.fnDestroy();
        otomatis_data = $('#otomatis_data').dataTable({ 
            dom:
                "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            orderCellsTop: true,
            fixedHeader: true,
            buttons:[
                {
                    extend:    'copyHtml5',
                    text:      '<i class="fas fa-clipboard"></i>',
                    className: 'btn btn-primary',
                    titleAttr: 'Copy to Clipboard'
                },
                {
                    extend:    'excelHtml5',
                    text:      '<i class="fas fa-file-excel"></i>',
                    className: 'btn btn-primary',
                    titleAttr: 'Export to Excel'
                },
                {
                    extend:    'csvHtml5',
                    text:      '<i class="fas fa-file-csv"></i>',
                    className: 'btn btn-primary',
                    titleAttr: 'Export to CSV'
                }
            ],
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "processing": true, 
            "serverSide": true, 
            "scrollX": true,
            "order": [], 
            "ajax": {
                "url": "<?=$base_url.$page?>/data/otomatis_data",
                "data":{"<?=$csrf_token_name?>":"<?=$csrf_hash?>","startdate":$("#startdate").val(),"enddate":$("#enddate").val()},
                "type": "POST"
            },
            "columnDefs": [
                { 
                    "targets": [ 0 ], 
                    "orderable": false, 
                }
            ]

        });
    });

    $('#get_spreadsheet').on('click',function() {

        window.open('<?=$base_url.$page?>/data/export_spreadsheet/'+$('#startdate').val()+'/'+$('#enddate').val(), '_blank');

        /* $.ajax({
            url: '{base_url}page_inc_open/printspb',
            type: 'POST',
            dataType: 'json',
            data: {impact: $("#impact").val(), urgency: $("#urgency").val() },
        })
        .done(function(data) {
            if(data.status==true){
                $('#priority').val(data.priority_id).trigger('change');
            }else{
                $('#priority').val('').trigger('change');
            }
        })
        .fail(function() {
            $('#priority').val('').trigger('change');
        }); */

    });

    $('#get_csv').on('click',function() {

        window.open('<?=$base_url.$page?>/data/export_spreadsheet/'+$('#startdate').val()+'/'+$('#enddate').val()+'/csv', '_blank');

    });


    $('.menu').removeClass('active');
    $('#<?=$this->uri->segment(1).'-'.$this->uri->segment(3)?>').addClass('active');
    $('#<?=$this->uri->segment(1).'-'.$this->uri->segment(3)?>').parent().parent().parent('.has-treeview').addClass('menu-open');

} );

</script>