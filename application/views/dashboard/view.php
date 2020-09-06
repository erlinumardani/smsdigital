<!-- Main content -->
<section class="content">
<div class="container-fluid site-width">

    <!-- START: Card Data-->
    <div id="summary" class="row">
        <div class="col-12 col-sm-6 col-xl-3 mt-3">
            <div class="card">
                <div class="card-body text-success border-bottom border-success border-w-5">
                    <h2 id="total_sms_received" class="text-center"></h2>
                    <h6 class="text-center">RECEIVED</h6>       
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3 mt-3">
            <div class="card">
                <div class="card-body text-info border-bottom border-info border-w-5">
                    <h2 id="total_sms_sent" class="text-center"></h2>
                    <h6 class="text-center">SENT</h6>       
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3 mt-3">
            <div class="card">
                <div class="card-body text-warning border-bottom border-warning border-w-5">
                    <h2 class="text-center" id="total_sms_sending"></h2>
                    <h6 class="text-center">SENDING</h6>       
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-3 mt-3">
            <div class="card">
                <div class="card-body text-danger border-bottom border-danger border-w-5">
                    <h2 class="text-center" id ="total_sms_failed"></h2>
                    <h6 class="text-center">FAILED</h6>       
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-6 col-xl-4 mt-3">
            <div class="card">
                <div class="card-body p-0">
                    <div class='p-4 align-self-center'>
                        <h4><i class="icon-envelope"></i> <b id="total_sms"><?=$total_sms?></b> / <?=$limit?></h4>
                        <h6 class="card-liner-subtitle">This Month Summary</h6>  
                    </div>
                    <div  class="barfiller" data-color="#1e3d73">
                        <div class="tipWrap">
                            <span class="tip rounded primary">
                                <span class="tip-arrow"></span>
                            </span>
                        </div>
                        <span class="fill" data-percentage="<?=$limit_persent?>"></span>
                    </div>                              
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4 mt-3">
            <div class="card">
                <div class="card-body p-0">
                    <div class='p-4 align-self-center'>
                        <h2><i class="icon-envelope-open"></i> <?=$sms_otomatis?></h2>
                        <h6 class="card-liner-subtitle">SMS Otomatis</h6>  
                    </div>
                    <div  class="barfilleroff" data-color="#17a2b8">
                        <div class="tipWrap">
                            <span class="tip rounded info">
                                <span class="tip-arrow"></span>
                            </span>
                        </div>
                        <span class="fill" data-percentage="92"></span>
                    </div>                              
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4 mt-3">
            <div class="card">
                <div class="card-body p-0">
                    <div class='p-4 align-self-center'>
                        <h2><i class="icon-user"></i> <?=$contacts?></h2>
                        <h6 class="card-liner-subtitle">Contacts</h6>  
                    </div>
                    <div  class="barfilleroff" data-color="#1ee0ac">
                        <div class="tipWrap">
                            <span class="tip rounded success">
                                <span class="tip-arrow"></span>
                            </span>
                        </div>
                        <span class="fill" data-percentage="67"></span>
                    </div>                              
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-12  mt-3">
            <div class="card">                           
                <div class="card-content">
                    <div class="card-body">
                        <div id="apex_analytic_chart" class="height-500"></div>
                    </div>
                </div>
            </div>
        </div>     
        <div class="col-12 col-md-12 col-lg-12 mt-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">                               
                    <h6 class="card-title">Precentage by Provider</h6>
                </div>
                <div class="card-content">
                    <div class="card-body p-0">
                        <ul class="list-group list-unstyled">
                            <li class="p-4 border-bottom">
                                <div class="w-100">
                                    <a href="#"><img src="<?=$base_url?>assets/images/xl.png" alt="" class="img-fluid ml-0 mb-2  rounded-circle" width="50"></a>                                                
                                    <div class="barfiller h-7 rounded" data-color="#1ee0ac">
                                        <div class="tipWrap">
                                            <span class="tip rounded">
                                                <span class="tip-arrow"></span>
                                            </span>
                                        </div>
                                        <span class="fill" data-percentage="<?=$y_xl?>"></span>
                                    </div>                                 
                                </div> 
                            </li>
                            <li class="p-4 border-bottom">
                                <div class="w-100">
                                    <a href="#"><img src="<?=$base_url?>assets/images/three.png" alt="" class="img-fluid ml-0 mb-2  rounded-circle" width="50"></a>                                                
                                    <div class="barfiller h-7" data-color="#7782bd">
                                        <div class="tipWrap">
                                            <span class="tip rounded">
                                                <span class="tip-arrow"></span>
                                            </span>
                                        </div>
                                        <span class="fill" data-percentage="<?=$y_three?>"></span>
                                    </div>                                 
                                </div> 
                            </li>
                            <li class="p-4 border-bottom">
                                <div class="w-100">
                                    <a href="#"><img src="<?=$base_url?>assets/images/axis.png" alt="" class="img-fluid ml-0 mb-2  rounded-circle" width="50"></a>                                                
                                    <div class="barfiller h-7" data-color="#753c94">
                                        <div class="tipWrap">
                                            <span class="tip rounded">
                                                <span class="tip-arrow"></span>
                                            </span>
                                        </div>
                                        <span class="fill" data-percentage="<?=$y_axis?>"></span>
                                    </div>                                 
                                </div> 
                            </li>
                            <li class="p-4 border-bottom">
                                <div class="w-100">
                                    <a href="#"><img src="<?=$base_url?>assets/images/telkomsel.png" alt="" class="img-fluid ml-0 mb-2  rounded-circle" width="50"></a>                                                
                                    <div class="barfiller h-7" data-color="#f64e60">
                                        <div class="tipWrap">
                                            <span class="tip rounded">
                                                <span class="tip-arrow"></span>
                                            </span>
                                        </div>
                                        <span class="fill" data-percentage="<?=$y_telkomsel?>"></span>
                                    </div>                                 
                                </div> 
                            </li>
                            <li class="p-4 border-bottom">
                                <div class="w-100">
                                    <a href="#"><img src="<?=$base_url?>assets/images/smartfren.png" alt="" class="img-fluid ml-0 mb-2  rounded-circle" width="50"></a>                                                
                                    <div class="barfiller h-7" data-color="#eb6431">
                                        <div class="tipWrap">
                                            <span class="tip rounded">
                                                <span class="tip-arrow"></span>
                                            </span>
                                        </div>
                                        <span class="fill" data-percentage="<?=$y_smartfren?>"></span>
                                    </div>                                 
                                </div> 
                            </li>
                            <li class="p-4 border-bottom">
                                <div class="w-100">
                                    <a href="#"><img src="<?=$base_url?>assets/images/indosat.png" alt="" class="img-fluid ml-0 mb-2  rounded-circle" width="50"></a>                                                
                                    <div class="barfiller h-7" data-color="#ffd04c">
                                        <div class="tipWrap">
                                            <span class="tip rounded">
                                                <span class="tip-arrow"></span>
                                            </span>
                                        </div>
                                        <span class="fill" data-percentage="<?=$y_indosat?>"></span>
                                    </div>                                 
                                </div> 
                            </li>
                            <li class="p-4 border-bottom">
                                <div class="w-100">
                                    <a href="#">Other</a>                                                
                                    <div class="barfiller h-7" data-color="#aaa">
                                        <div class="tipWrap">
                                            <span class="tip rounded">
                                                <span class="tip-arrow"></span>
                                            </span>
                                        </div>
                                        <span class="fill" data-percentage="<?=$y_other?>"></span>
                                    </div>                                 
                                </div> 
                            </li>

                        </ul> 
                    </div>
                </div>
            </div>
        </div>                 
    </div>
    <!-- END: Card DATA-->                 
</div>
</section>
<!-- /.content -->

  