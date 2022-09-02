<footer class="content-footer footer bg-footer-theme">
    <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
        <div class="mb-2 mb-md-0">
            ©
            <script>
                document.write(new Date().getFullYear());
            </script>
            , made with by
            <a href="#" target="_blank" class="footer-link fw-bolder">Eoxys It</a>
        </div>
        <div>
            <a href="#" class="footer-link me-4" target="_blank">License</a>
            <a href="#" target="_blank" class="footer-link me-4">More Themes</a>

            <a href="#" target="_blank" class="footer-link me-4">Documentation</a>

            <a href="#" target="_blank" class="footer-link me-4">Support</a>
        </div>
    </div>
</footer>
<!-- / Footer -->

<div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->
</div>
<!-- / Layout page -->
</div>

<!-- Overlay -->
<div class="layout-overlay layout-menu-toggle"></div>
</div>
<!-- / Layout wrapper -->



<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="{{ URL::asset('admin/assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ URL::asset('admin/assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ URL::asset('admin/assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ URL::asset('admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

<script src="{{ URL::asset('admin/assets/vendor/js/menu.js') }}"></script>
<!-- endbuild -->
<script src="assets/vendor/libs/select2/select2.js"></script>
<!-- Vendors JS -->
<script src="{{ URL::asset('admin/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
<!-- Main JS -->
<script src="{{ URL::asset('admin/assets/js/main.js') }}"></script>

<!-- Page JS -->
<script src="{{ URL::asset('admin/assets/js/dashboards-analytics.js') }}"></script>

<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
</body>
<script>
      
    $( document ).ready(function() {
        $('#end_date').change(function(){
        var start_date=$('#start_date').val();
      
        var end_date=$('#end_date').val();
        
       var date1 = new Date(start_date); 
      
	var date2 = new Date(end_date); 
  
    var Time = date2.getTime() - date1.getTime(); 
    var Days = Time / (1000 * 3600 * 24);
    if(Days<0){
        $("#project_duration").val("End date should be greater than Start date")+'Day';
     
        }else{
            $("#project_duration").val(Days)+'Day';
        } 
       
});
    

});
    
</script>
<script>
      
    $( document ).ready(function() {
        $('#start_date').change(function(){
        var start_date=$('#start_date').val();
      
        var end_date=$('#end_date').val();
        
       var date1 = new Date(start_date); 
	var date2 = new Date(end_date); 
  
    var Time = date2.getTime() - date1.getTime(); 
    var Days = Time / (1000 * 3600 * 24);
  
    if(Days<0){
        $("#project_duration").val("End date should be greater than Start date")+'Day';
     
        }else{
            $("#project_duration").val(Days)+'Day';
        }     
});
    

});
    
</script>
<script>
    $('#paymethod').on('change', function() {
  var paymethod=(this.value );
  if(paymethod=='hourly'){
    $(".per_hour_budget").show();
    $(".total_budget").hide();
  }
  if(paymethod=='fixed'){
    $(".total_budget").show();
    $(".per_hour_budget").hide();
  }
  if(paymethod==''){
    $(".total_budget").hide();
    $(".per_hour_budget").hide();
  }
});
</script>
<script>
    $(".select2").select2();
</script>
<script>
    $(function() {
        $("#Project").change(function(){
            var element = $(this);
            $project_id=$(this).val();
            $('#project_id').val($project_id);
            var payment_base = $('option:selected', this).attr('data');
           if(payment_base=='fixed'){
            $("#amount_label").html('Amount(fixed)')
            $(".amount").attr("placeholder", "Enter fixed amount");
           }
           if(payment_base=='hourly'){
            $("#amount_label").html('Amount(per hour)')
            $(".amount").attr("placeholder", "Enter per hour amount");
           }
     
        });
    });
</script>
<script type="text/javascript">
    $('#amount').on('keyup',function(){
  
    $amount_value=$(this).val();
    $unify_service_fee=$("#servicefee").val();
  $service_fee= ($amount_value*$unify_service_fee)/100;
  $freelance_amount=$amount_value-$service_fee;
  $("#unify_service_fee").val($service_fee);
  $("#freelancr_amount").val($freelance_amount);
    })
</script>




<script type="text/javascript">
    $(document).ready(function(){
          
        $("#formsubmit").click(function(e){
            
          e.preventDefault();
          checkoutvalue = 1;
         
           
      
          
          var project_description = $('#project_description').val().length;
          
           
  
   
          
          if (project_description<100) {
              $('#project_description_error').text('Description minimum words limit is 100');
              checkoutvalue = 0;
          }else{
              $('.project_description_error').text('');
          }
  
          if (project_description>1000) {
             $( '#project_description_error' ).text('Description maximum words limit is 1000');
              checkoutvalue = 0;
          }else{
              $('.project_description_error').text('');
          }
  
         
  
         
  
         
          if (checkoutvalue == 1) {
            $('#formId').submit();
             
          }
        });
      });


      
</script>

<script>
    $("#end_date").on("change", function() {
    this.setAttribute(
        "data-date",
        moment(this.value, "YYYY-MM-DD")
        .format( this.getAttribute("data-date-format") )
    )
}).trigger("change")
</script>
<script>
    function fnExcelReport() {
        var table = document.getElementById('exportToTable'); // id of table

        var tableHTML = table.outerHTML;
         alert(tableHTML);
        var fileName = 'Projects.xls';

        var msie = window.navigator.userAgent.indexOf("MSIE ");

        // If Internet Explorer
        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
            dummyFrame.document.open('txt/html', 'replace');
            dummyFrame.document.write(tableHTML);
            dummyFrame.document.close();
            dummyFrame.focus();
            return dummyFrame.document.execCommand('SaveAs', true, fileName);
        }
        //other browsers
        else {
            var a = document.createElement('a');
            tableHTML = tableHTML.replace(/  /g, '').replace(/ /g, '%20'); // replaces spaces
            a.href = 'data:application/vnd.ms-excel,' + tableHTML;
            a.setAttribute('download', fileName);
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    }
</script>


</html>
