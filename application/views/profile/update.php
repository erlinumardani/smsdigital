<!-- Main content -->
<section class="content">
    <div class="row">
    <div class="col-md-12">

            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-clipboard"></i> <?=$form_title?></h3>
              </div>
              <div class="card-body">

              <form method="post" action="#" id="initiate_form">

                <?=$form?>

                <button type="submit" class="btn btn-warning" onclick="window.history.go(-1); return false;"><i class="fas fa-backward"></i> Back</button>
                <button type="submit" class="btn btn-primary" onclick="change_password_modal(); return false;"><i class="fas fa-lock"></i> Change Password</button>
                <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane"></i> Update</button>
                
              </form>
              
              

              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

          </div>
    <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<div class="modal fade" id="modal_change_password">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="post" action="#" id="change_password">
        <div class="modal-header bg-primary">
          <h4 class="modal-title" style="color:white;"><i class="fas fa-lock"></i> Change Password</h4>
          <button type="button" class="close" style="color:white;" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <?=$form2?>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal_vendor_reminder">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="modal-title"><i class="fas fa-info-circle"></i> Reminder</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Selamat datang di aplikasi Diana, mohon luangkan waktu anda untuk membaca petunjuk penggunaan aplikasi ini. 
      </div>
      <div class="modal-footer justify-content-between">
        <div class="icheck-primary d-inline">
          <input type="checkbox" id="checkbox_reminder" />
          <label for="checkbox_reminder">
            <font size="2">Don't show again</font>
          </label>
        </div>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" onClick="window.location.href = '<?=$base_url?>guidance/data/view'">Lihat Dokumen Petunjuk</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.content -->
<script>

  var role_id = '<?=$this->session->userdata("role_id")?>';

  if($.cookie("guidance_reminder") == "checked"){
    
  }else{
    if(role_id == '11'){
      $('#modal_vendor_reminder').modal('toggle');
    }
  }
  
  $('#checkbox_reminder').on('change',function(){

    if($(this).prop("checked")){
      $.cookie("guidance_reminder", "checked");
    }else{
      $.cookie("guidance_reminder", "unchecked");
    }
    
  });
  
  function change_password_modal(params) {
    $('#modal_change_password').modal('toggle');
  }

  $('#change_password').validate({
      submitHandler: function () {
          $.ajax({
            url: '<?=$base_url.$page?>/data/change_password',
            enctype: 'multipart/form-data',
            data: {"id":$('#user_id').val(),"password":$('#password').val(),"old_password":$('#old_password').val(),"<?=$csrf_token_name?>":"<?=$csrf_hash?>"},
            type: 'POST',
            dataType: 'json',
          })
          .done(function(data) {
            if(data.status==true){
                Swal.fire(
                'Password Changed!',
                data.message,
                'success'
                ).then(function(){
                    $('#modal_change_password').modal('toggle');
                });
            }else{
                Swal.fire(
                'Failed!',
                data.message,
                'error'
                );
            }    
          });
        },
        rules: {
          "password":{
            "required":true
          },
          "confirm_password":{
            "required":true,
            "equalTo": "#password"
          },
          "old_password":{
            "required":true
          }
        },
        messages: {
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 5 characters long"
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
    });

</script>
  