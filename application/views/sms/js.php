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
  

    var datalist = $('#datalist').dataTable({ 
 
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
                "targets": [ 0,8 ], 
                "orderable": false, 
            },
            {
                "targets": [8],
                "className": "no_view_detail"
            },
            {
                "targets": [0,1,2,3,4,5,6,7],
                "className": "view_detail"
            }
        ],
        "drawCallback": function(settings, json) {
            $('[data-toggle="tooltip"]').tooltip();
            $('td.view_detail').on('click',function() {
                var id = $(this).parent().data("id");
                $(location).attr('href','<?=$base_url.$page?>/data/view/'+id);
            });
            $('th').removeClass('view_detail');
            $('.delete').on('click',function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '<?=$base_url.$page?>/data/delete',
                            enctype: 'multipart/form-data',
                            data: {"id":$(this).data('id'),"<?=$csrf_token_name?>":"<?=$csrf_hash?>"},
                            type: 'POST',
                            dataType: 'json',
                        })
                        .done(function(data) {
                            if(data.status==true){
                                Swal.fire(
                                'Deleted!',
                                data.message,
                                'success'
                                ).then(function(){
                                    $('#datalist').DataTable().ajax.reload();
                                });
                            }else{
                                Swal.fire(
                                'Failed!',
                                data.message,
                                'error'
                                );
                            }    
                        });
                        
                    }
                });
            });

            $('.update').on('click',function() {
                $(location).attr('href','<?=$base_url.$page?>/data/update/'+$(this).data('id'));
            });
        },
        createdRow: function (row, data, index) {
            $(row).attr('data-id', data[9]);
            $(row).attr('style','cursor:pointer;');
        }

    });

    $('.menu').removeClass('active');
    $('#<?=$this->uri->segment(1).'-'.$this->uri->segment(3)?>').addClass('active');
    $('#<?=$this->uri->segment(1).'-'.$this->uri->segment(3)?>').parent().parent().parent('.has-treeview').addClass('menu-open');

} );

</script>