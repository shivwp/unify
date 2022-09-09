<footer class="content-footer footer bg-footer-theme">
    <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
        <div class="mb-2 mb-md-0">
           
           
       
            <a href="#" target="_blank" class="footer-link fw-bolder"> Copyright  ©  <script>
                document.write(new Date().getFullYear());
            </script> Unify. Designed by Eoxysit All rights reserved.</a>
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


<script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
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

        let table = document.getElementsByTagName("table"); // you can use document.getElementById('tableId') as well by providing id to the table tag
  TableToExcel.convert(table[0], { // html code may contain multiple tables so here we are refering to 1st table tag
    name: `export.xlsx`, // fileName you could use any name
    sheet: {
      name: 'Sheet 1' // sheetName
    }
  });
    }
</script>
<script>

   $('#user_filter').change(function(){
    $('#filter_form').submit();
    });

</script>
<script>
    $('#materialUnchecked').click(function(event) {   
    if(this.checked) {
        // Iterate each checkbox
        $(':checkbox').each(function() {
            this.checked = true;                        
        });
    } else {
        $(':checkbox').each(function() {
            this.checked = false;                       
        });
    }
}); 
</script>
<script>

   $('#pagination').change(function(){
    $('#pagination').submit();
    });

</script>
<script>

   $('#project_filter').change(function(){
    $('#project_filter').submit();
    });

</script>
<script>

   $('#freelancer_filter').change(function(){
    $('#freelancer_filter').submit();
    });

</script>
<script>
     $(document).ready(function(){
        $(".category_re").click(function(e){
         var delete_category_id = $(this).val();
            $('#nameBasic').val(delete_category_id);

            $.ajax({
        type: 'get',
        dataType : 'json',
        url: "{{ url('admin/category-replace') }}",
        data: {
            id:delete_category_id,
           
        },
        success:function(response){
        if (response.status) {
            jQuery('#select2Basic').html(response.online);
            
        }
        }

    });
        });
     });
</script>


</html>
