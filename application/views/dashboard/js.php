<!-- START: Page CSS-->
<link rel="stylesheet"  href="<?=$base_url?>assets/pick/dist/vendors/chartjs/Chart.min.css">
<link href="<?=$base_url?>assets/pick/dist/vendors/lineprogressbar/jquery.lineProgressbar.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?=$base_url?>assets/pick/dist/vendors/ionicons/css/ionicons.min.css"> 
<!-- END: Page CSS-->

<!-- START: Page CSS-->   
<link rel="stylesheet" href="<?=$base_url?>assets/pick/dist/vendors/morris/morris.css"> 
<link rel="stylesheet" href="<?=$base_url?>assets/pick/dist/vendors/weather-icons/css/pe-icon-set-weather.min.css"> 
<link rel="stylesheet" href="<?=$base_url?>assets/pick/dist/vendors/chartjs/Chart.min.css"> 
<link rel="stylesheet" href="<?=$base_url?>assets/pick/dist/vendors/starrr/starrr.css"> 
<link rel="stylesheet" href="<?=$base_url?>assets/pick/dist/vendors/fontawesome/css/all.min.css">
<link rel="stylesheet" href="<?=$base_url?>assets/pick/dist/vendors/ionicons/css/ionicons.min.css"> 
<link rel="stylesheet" href="<?=$base_url?>assets/pick/dist/vendors/jquery-jvectormap/jquery-jvectormap-2.0.3.css">
<!-- END: Page CSS-->

 <!-- START: APP JS-->
 <!-- <script src="<?=$base_url?>assets/pick/dist/js/app.js"></script> -->
<!-- END: APP JS-->

<!-- START: Page Vendor JS-->
<script src="<?=$base_url?>assets/pick/dist/vendors/raphael/raphael.min.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/morris/morris.min.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/chartjs/Chart.min.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/starrr/starrr.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/jquery-flot/jquery.canvaswrapper.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/jquery-flot/jquery.colorhelpers.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/jquery-flot/jquery.flot.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/jquery-flot/jquery.flot.saturated.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/jquery-flot/jquery.flot.browser.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/jquery-flot/jquery.flot.drawSeries.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/jquery-flot/jquery.flot.uiConstants.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/jquery-flot/jquery.flot.legend.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/jquery-flot/jquery.flot.pie.js"></script>        
<script src="<?=$base_url?>assets/pick/dist/vendors/chartjs/Chart.min.js"></script>  
<script src="<?=$base_url?>assets/pick/dist/vendors/jquery-jvectormap/jquery-jvectormap-2.0.3.min.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/jquery-jvectormap/jquery-jvectormap-world-mill.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/jquery-jvectormap/jquery-jvectormap-de-merc.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/jquery-jvectormap/jquery-jvectormap-us-aea.js"></script>
<script src="<?=$base_url?>assets/pick/dist/vendors/apexcharts/apexcharts.min.js"></script>
<script  src="<?=$base_url?>assets/pick/dist/vendors/lineprogressbar/jquery.lineProgressbar.js"></script>
<script  src="<?=$base_url?>assets/pick/dist/vendors/lineprogressbar/jquery.barfiller.js"></script>
<!-- END: Page Vendor JS-->

<!-- START: Page JS-->
<!-- <script src="<?=$base_url?>assets/pick/dist/js/home.script.js"></script> -->
<!-- END: Page JS-->

<script>
$(document).ready(function() {

    $('.menu').removeClass('active');
    $('#<?=$this->uri->segment(1)?>').addClass('active');
    $('#<?=$this->uri->segment(1)?>').parent().parent().parent('.has-treeview').addClass('menu-open');
    $('.datetime').datepicker({ dateFormat: 'yy-mm-dd' }).prop('autocomplete',"off"); 

    ////////// Bar Filler //////////////
    if ($('.barfiller').length > 0)
    {
        $(".barfiller").each(function () {
            $(this).barfiller({barColor: $(this).data('color')});
        });
    }

    ////////// Apex Analytic Chart //////////////
    var theme = 'light';
    if ($("#apex_analytic_chart").length > 0)
    {
        options = {
            theme: {
                mode: theme
            },
            chart: {
                height: 350,
                type: 'bar',
            },
            responsive: [
                {
                    breakpoint: 767,
                    options: {
                        chart: {
                            height: 220
                        }
                    }
                }
            ],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            colors: ['#1ee0ac', '#ffc107', '#17a2b8', '#f64e60', '#eb6431', '#ffd04c', '#aaaaaa'],
            series: [{
                    name: 'XL',
                    data: [0, 0, 0, 0, 0, 20, 0, 0, 0]
                }, {
                    name: 'Three',
                    data: [0, 0, 0, 0, 0, 30, 0, 0, 0]
                }, {
                    name: 'Axis',
                    data: [0, 0, 0, 0, 0, 40, 0, 0, 0]
                }, {
                    name: 'Telkomsel',
                    data: [0, 0, 0, 0, 0, 1000, 0, 0, 0]
                }, {
                    name: 'Smartfren',
                    data: [0, 0, 0, 0, 0, 30, 0, 0, 0]
                }, {
                    name: 'Indosat',
                    data: [0, 0, 0, 0, 0, 500, 0, 0, 0]
                }, {
                    name: 'Other',
                    data: [0, 0, 0, 0, 0, 20, 0, 0, 0]
                }],
            xaxis: {
                categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
            },
            yaxis: {
                title: {
                    text: '(thousands)'
                }
            },
            fill: {
                opacity: 1

            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val + " thousands"
                    }
                }
            }
        }

        var chart = new ApexCharts(
                document.querySelector("#apex_analytic_chart"),
                options
                );
        chart.render();
    }
});

</script>