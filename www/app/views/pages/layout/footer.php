
  <!-- jQuery -->
  <script src="assets/plugins/jquery/jquery.min.js"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="assets/plugins/jquery-ui/jquery-ui.min.js"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script type="text/javascript" >
    $.widget.bridge('uibutton', $.ui.button)
  </script>
  <!-- Bootstrap 4 -->
  <script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- pace-progress -->
  <script src="assets/plugins/pace-progress/pace.min.js"></script>

  <!-- bs-custom-file-input -->
  <script src="assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function () {
      bsCustomFileInput.init();
    });
  </script>
  <!-- overlayScrollbars -->
  <script src="assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <!-- Toastr -->
  <script src="assets/plugins/toastr/toastr.min.js"></script>
  <script type="text/javascript">
    $(function(){<?php Controller::message(); ?>});
  </script>

  <!-- Prevent Form Resubmission on Page Refresh -->
  <script type="text/javascript">
    if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.href);
    }
  </script>

  <?php if(isset($data['page_include'])): ?>
    <?php if ($data['page_include'] !== '500' && $data['page_include'] !== '404' && $data['page_include'] !== 'login'): ?>
      <!-- Digital Clock -->
      <script type="text/javascript" src="assets/js/digital-clock.js"></script>
    <?php endif; ?>

    <?php if ($data['page_include'] == 'pos'): ?>
      <!-- POS Retailer -->
      <script type="text/javascript" src="assets/js/pos-retailer.js"></script>
    <?php endif; ?>

    <?php if ($data['page_include'] == 'add_drug'): ?>
      <!-- On Form Submit Preloader JS -->
      <script type="text/javascript">
        $(document).ready(function(){
          $(".add-drug").on("submit", function(){
            $("#pageloader").fadeIn();
          });//submit
        });//document ready
      </script>
    <?php endif; ?>

    <?php if ($data['page_include'] == 'edit_drug'): ?>
      <!-- On Form Submit Preloader JS -->
      <script type="text/javascript">
        $(document).ready(function(){
          $(".edit-drug").on("submit", function(){
            $("#pageloader").fadeIn();
          });//submit
        });//document ready
      </script>
    <?php endif; ?>

    <?php if ($data['page_include'] == 'import_excel_sheet'): ?>
      <!-- On Form Submit Preloader JS -->
      <script type="text/javascript">
        $(document).ready(function(){
          $(".import-drugs").on("submit", function(){
            $("#pageloader").fadeIn();
          });//submit
        });//document ready
      </script>
    <?php endif; ?>

    <?php if ($data['page_include'] == 'notify_expiry'): ?>
      <!-- On Form Submit Preloader JS -->
      <script type="text/javascript">
        $(document).ready(function(){
          $(".move-to-trash").on("submit", function(){
            $("#pageloader").fadeIn();
          });//submit
        });//document ready
      </script>
    <?php endif; ?>

    <?php if ($data['page_include'] == 'edit_stock'): ?>
      <!-- Stock Updator -->
      <script type="text/javascript" src="assets/js/stock-updator.js"></script>
      <!-- On Form Submit Preloader JS -->
      <script type="text/javascript">
        $(document).ready(function(){
          $("#edit-stock").on("submit", function(){
            $("#pageloader").fadeIn();
          });//submit
        });//document ready
      </script>
    <?php endif; ?>

    <?php if ($data['page_include'] == 'add_stock'): ?>
      <script src="assets/js/stock-management.js"></script>
    <?php endif; ?>

    <?php if ($data['page_include'] == 'add_stock' || $data['page_include'] == 'pos'): ?>
      <!-- On Form Submit Preloader JS -->
      <script type="text/javascript">
        $(document).ready(function(){
          $("#add-stock").on("submit", function(){
            $("#pageloader").fadeIn();
          });//submit
        });//document ready
      </script>
      <!-- Select2 -->
      <script src="assets/plugins/select2/js/select2.full.min.js"></script>
      <script>
        $(function () {
          //Initialize Select2 Elements
          $('.select2').select2()
        });
      </script>
    <?php endif; ?>

    <?php if ($data['page_include'] == 'process_payments'): ?>
      <!-- On Form Submit Preloader JS -->
      <script type="text/javascript">
        $(document).ready(function(){
          $("#process-payments").on("submit", function(){
            $("#pageloader").fadeIn();
          });//submit
        });//document ready
      </script>
    <?php endif; ?>
  <?php endif; ?>

  <?php if(isset($data['page_include'])): ?>
    <?php if ($data['page_include'] == 'dashboard'): ?>
      <!-- ChartJS -->
      <script src="assets/plugins/chart.js/Chart.min.js"></script>

     <?php $graphstatistics = new GraphStatistics; ?>

      <!-- page script -->
      <script>
        $(function () {
      /*
       * ChartJS
       */

      //--------------
      //- AREA CHART -
      //--------------
      //'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'
      // Get context with jQuery - using jQuery's .get() method.
      var areaChartCanvas = $('#areaChart').get(0).getContext('2d')

      var areaChartData = {
        labels  : [
        <?php

        $d=strtotime('monday this week');
        for ($i=0; $i<7; $i++) {
          $timestamp = strtotime('+'.$i.' Days', $d);
          echo "'".date("D-d", $timestamp)."'";
          if ($i < 7) {echo ", ";}
        }

        ?>
        ],
        datasets: [
        {
          label               : 'Weekly Sales',
          backgroundColor     : 'rgba(60,141,188,0.9)',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : [<?php $graphstatistics->weekSalesStatistics('monday this week'); ?>]
        },
        ]
      }

      var areaChartOptions = {
        maintainAspectRatio : false,
        responsive : true,
        legend: {
          display: false
        },
        scales: {
          xAxes: [{
            gridLines : {
              display : false,
            }
          }],
          yAxes: [{
            gridLines : {
              display : false,
            }
          }]
        }
      }

      // This will get the first returned node in the jQuery collection.
      var areaChart       = new Chart(areaChartCanvas, {
        type: 'line',
        data: areaChartData,
        options: areaChartOptions
      })
    })
  </script>
<?php endif; ?>

<?php  if ($data['page_include'] == 'view_drugs' || $data['page_include'] == 'view_categories' ||
  $data['page_include'] == 'view_expenses' || $data['page_include'] == 'sales' ||
  $data['page_include'] == 'sales_report' || $data['page_include'] == 'expenses_report' ||
  $data['page_include'] == 'notify_expiry' || $data['page_include'] == 'notify_near_expiry' ||
  $data['page_include'] == 'notify_outOfStock' || $data['page_include'] == 'expenses' ||
  $data['page_include'] == 'purchases_records'): ?>
  <!-- DataTables -->
  <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

  <!-- page script -->
  <script>
    $(function () {
      $("#example1").DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
      });
      $('#example2').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
      });
      $('#example3').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
      });
      $('#example4').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
      });
      $('#example5').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
      });
    });
  </script>
<?php endif; ?>

<?php if ($data['page_include'] == 'purchases_records'): ?>
  <script>
    function unseen_notifications(view = ''){
      jQuery.ajax({
        url:"?url=notification/stock_changes_notifications",
        method: "POST",
        data: {view:view},
        dataType: "JSON",
        success:function(data){
          $("#pre-notification-text").hide();
          $("#post-notification-text").hide();
          $("#notification-body").html(data.notify);
          //console.log(data);
        },
        error:function (error){
          console.log(error.responseText);
          $("#pre-notification-text").hide();
          $("#post-notification-text").show();
        }
      });
    }
    //Refreshing notification panel every 1000ms (1 second)
    setInterval(unseen_notifications, 1000);
  </script>

  <script>
    $(document).ready(function(){
      $("#search-trash").on("submit", function(event){
        $("#pageloader").fadeIn(100);
        event.preventDefault();
        var formValues = $(this).serialize();
        jQuery.ajax({
          url: "?url=home/liveSearchTrash",
          method: "POST",
          data: formValues,
          dataType: "JSON",
          success:function(data){
            $("#pageloader").fadeOut("slow");
            $("#trash-search-total").html(data.header);
            $("#list-trash-search-results").html(data.list_data);
            //console.log(data);
          },
          error:function (error){
            console.log(error.responseText);
            $("#pageloader").fadeOut("slow");
            toastr.error('Unable to process the request.');
          }
        });
      });

      $("#search-purchases").on("submit", function(event){
        $("#pageloader").fadeIn(100);
        event.preventDefault();
        var formValues = $(this).serialize();
        jQuery.ajax({
          url: "?url=home/liveSearchPurchases",
          method: "POST",
          data: formValues,
          dataType: "JSON",
          success:function(data){
            $("#pageloader").fadeOut("slow");
            $("#purchases-search-total").html(data.header);
            $("#list-purchases-search-results").html(data.list_data);
            //console.log(data);
          },
          error:function (error){
            console.log(error.responseText);
            $("#pageloader").fadeOut("fast");
            toastr.error('Unable to process the request.');
          }
        });
      });
    });
  </script>
<?php endif; ?>

<?php if ($data['page_include'] == 'sales_report'): ?>
  <script>
    $(document).ready(function(){
      $("#search-sales").on("submit", function(event){
        $("#pageloader").fadeIn(100);
        event.preventDefault();
        var formValues = $(this).serialize();
        jQuery.ajax({
          url: "?url=home/liveSearchSales",
          method: "POST",
          data: formValues,
          dataType: "JSON",
          success:function(data){
            $("#pageloader").fadeOut("slow");
            $("#sales-search-total").html(data.header);
            $("#list-sales-search-results").html(data.list_data);
            //console.log(data);
          },
          error:function (error){
            console.log(error.responseText);
            $("#pageloader").fadeOut("slow");
            toastr.error('Unable to process the request.');
          }
        });
      });
    });
  </script>

  <!-- ChartJS -->
  <script src="assets/plugins/chart.js/Chart.min.js"></script>

  <?php $graphstatistics = new GraphStatistics; ?>

  <!-- page script -->
  <script>
    $(function () {
      'use strict'

      var ticksStyle = {
        fontColor: '#495057',
        fontStyle: 'bold'
      }

      var mode      = 'index'
      var intersect = true

      /* ---- Graph for this and Last Year's Sales Comparison ---- */
      var $salesChart = $('#sales-chart-monthly')
      var salesChart  = new Chart($salesChart, {
        type   : 'bar',
        data   : {
          labels  : ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
          datasets: [
          {
            backgroundColor: '#007bff',
            borderColor    : '#007bff',
            data           : [<?php $graphstatistics->yearlySalesStatistics('january this year'); ?>]
          },
          {
            backgroundColor: '#ced4da',
            borderColor    : '#ced4da',
            data           : [<?php $graphstatistics->yearlySalesStatistics('january last year'); ?>]
          }
          ]
        },
        options: {
          maintainAspectRatio: false,
          tooltips           : {
            mode     : mode,
            intersect: intersect
          },
          hover              : {
            mode     : mode,
            intersect: intersect
          },
          legend             : {
            display: false
          },
          scales             : {
            yAxes: [{
              // display: false,
              gridLines: {
                display      : true,
                lineWidth    : '4px',
                color        : 'rgba(0, 0, 0, .2)',
                zeroLineColor: 'transparent'
              },
              ticks    : $.extend({
                beginAtZero: true,

                // Include a dollar sign in the ticks
                callback: function (value, index, values) {
                  if (value >= 1000000) {
                    value /= 1000000
                    value += 'm'
                  }
                  return 'Tsh ' + value + '/='
                }
              }, ticksStyle)
            }],
            xAxes: [{
              display  : true,
              gridLines: {
                display: false
              },
              ticks    : ticksStyle
            }]
          }
        }
      })

      /* ---- Graph for this and Last Week's Sales Comparison ---- */
      <?php  ?>

      var $weeklySalesChart = $('#sales-chart-weekly')
      var weeklySalesChart  = new Chart($weeklySalesChart, {
        data   : {
          labels  : ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
          datasets: [{
            type                : 'line',
            data                : [<?php $graphstatistics->weekSalesStatistics('monday this week'); ?>],
            backgroundColor     : 'transparent',
            borderColor         : '#007bff',
            pointBorderColor    : '#007bff',
            pointBackgroundColor: '#007bff',
            fill                : false
            // pointHoverBackgroundColor: '#007bff',
            // pointHoverBorderColor    : '#007bff'
          },
          {
            type                : 'line',
            data                : [<?php $graphstatistics->weekSalesStatistics('monday last week'); ?>],
            backgroundColor     : 'tansparent',
            borderColor         : '#ced4da',
            pointBorderColor    : '#ced4da',
            pointBackgroundColor: '#ced4da',
            fill                : false
              // pointHoverBackgroundColor: '#ced4da',
              // pointHoverBorderColor    : '#ced4da'
            }]
          },
          options: {
            maintainAspectRatio: false,
            tooltips           : {
              mode     : mode,
              intersect: intersect
            },
            hover              : {
              mode     : mode,
              intersect: intersect
            },
            legend             : {
              display: false
            },
            scales             : {
              yAxes: [{
              // display: false,
              gridLines: {
                display      : true,
                lineWidth    : '4px',
                color        : 'rgba(0, 0, 0, .2)',
                zeroLineColor: 'transparent'
              },
              ticks    : $.extend({
                beginAtZero : false,

                // Include a dollar sign in the ticks
                callback: function (value, index, values) {
                  if (value >= 1000000) {
                    value /= 1000000
                    value += 'm'
                  }
                  return 'Tsh ' + value + '/='
                }
              }, ticksStyle)
            }],
            xAxes: [{
              display  : true,
              gridLines: {
                display: false
              },
              ticks    : ticksStyle
            }]
          }
        }
      })
    })
  </script>
<?php endif; ?>
<?php endif; ?>

  <?php if ($data['page_include'] == 'expenses_report'): ?>
    <script>
      $(document).ready(function(){
        $("#search-expenses").on("submit", function(event){
          $("#pageloader").fadeIn(100);
          event.preventDefault();
          var formValues = $(this).serialize();
          jQuery.ajax({
            url: "?url=home/liveSearchExpenses",
            method: "POST",
            data: formValues,
            dataType: "JSON",
            success:function(data){
              $("#pageloader").fadeOut("slow");
              $("#expenses-search-total").html(data.header);
              $("#list-expenses-search-results").html(data.list_data);
              //console.log(data);
            },
            error:function (error){
              console.log(error.responseText);
              $("#pageloader").fadeOut("slow");
              toastr.error('Unable to process the request.');
            }
          });
        });
      });
    </script>
  <?php endif; ?>

  <?php if ($data['page_include'] == 'profit_loss_report'): ?>
    <script>
      $(document).ready(function(){
        $("#search-netprofit").on("submit", function(event){
          $("#pageloader").fadeIn(100);
          event.preventDefault();
          var formValues = $(this).serialize();
          jQuery.ajax({
            url: "?url=home/liveSearchProfitLoss",
            method: "POST",
            data: formValues,
            dataType: "JSON",
            success:function(data){
              $("#pageloader").fadeOut("slow");
              $("#netprofit-search-total").html(data.header);
              $("#list-netprofit-search-results").html(data.list_data);
              //console.log(data);
            },
            error:function (error){
              console.log(error.responseText);
              $("#pageloader").fadeOut("slow");
              toastr.error('Unable to process the request.');
            }
          });
        });
      });
    </script>
  <?php endif; ?>

  <?php if(Session::get('isAdmin') == true): ?>
    <!-- Notification-Count Badge for Side Bar -->
    <script>
      function load_notifications(count = ''){
        jQuery.ajax({
          url: "?url=notification/stock_changes_notifications",
          method: "POST",
          data: {count:count},
          dataType: "JSON",
          success:function(data){
            $("#notify-purchase").html(data.purchaseCount);
            $("#notify-trash").html(data.trashCount);
          },
          error:function (error){
            console.log(error.responseText);
          }
        });
  		}
      //Refreshing notification badges every 1000ms (1 second)
      setInterval(load_notifications, 1000);
    </script>
  <?php endif; ?>

  <!-- AdminLTE App -->
  <script src="assets/js/adminlte.min.js"></script>

</body>
</html>
