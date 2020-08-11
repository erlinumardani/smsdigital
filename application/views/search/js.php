<script>
$(document).ready(function() {

    $('.menu').removeClass('active');
    $('#<?=$this->uri->segment(1)?>').addClass('active');
    $('#<?=$this->uri->segment(1)?>').parent().parent().parent('.has-treeview').addClass('menu-open');
    $('.datetime').datepicker({ dateFormat: 'yy-mm-dd' }).prop('autocomplete',"off"); 

    var datalist = $('#datalist').dataTable({ 
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
            "url": "<?=$base_url.$page?>/data/list",
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

});

</script>