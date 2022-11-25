
<footer class="content-footer footer bg-footer-theme text-center">
    <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-column">
        <div class="mb-2 mb-md-0 ">
           
           
       
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
<script src="{{asset('admin/assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{asset('admin/assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{asset('admin/assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{asset('admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

<!-- datatable -->
<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

<script src="{{asset('admin/assets/vendor/js/menu.js') }}"></script>
<!-- endbuild -->
<!-- Vendors JS -->
<script src="{{asset('admin/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
<!-- Main JS -->
<script src="{{asset('admin/assets/js/main.js') }}"></script>
<script src="//cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>
<!-- <script src="{{asset('admin/assets/js/ckeditor.js')}}"></script> -->

<!-- SweetAlert -->
<script src="{{asset('admin/assets/js/sweetalert.min.js')}}"></script>
<!-- Page JS -->
<script src="{{asset('admin/assets/js/dashboards-analytics.js') }}"></script>

<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
</body>



<script type="text/javascript">
    function fnExcelReport() {
        let table = document.getElementsByTagName("table"); // you can use document.getElementById('tableId') as well by providing id to the table tag
        TableToExcel.convert(table[0], { // html code may contain multiple tables so here we are refering to 1st table tag
            name: `export.xlsx`, // fileName you could use any name
            sheet: {
                name: 'Sheet 1' // sheetName
            }
        });
    }
    $(document).ready(function() {

        if($(document).hasClass("ckeditor")){
            CKEDITOR.replace('content');
            CKEDITOR.config.allowedContent = true;
        }
   
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
    
        $("#Enddate").change(function () {
           
            var start_date = $("#Startdate").val();
            var end_date = $("#Enddate").val();

            var date1 = new Date(start_date); 
            var date2 = new Date(end_date); 
      
            var Time = date2.getTime() - date1.getTime(); 
            var Days = Time / (1000 * 3600 * 24);
  

            if(Days<0){
                alert("End date should be greater than Start date");
                $("#Enddate").val('');
            } 
        });

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

        $(".select2").select2();

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

        $('#amount').on('keyup',function(){
            $amount_value=$(this).val();
            $unify_service_fee=$("#servicefee").val();
            $service_fee= ($amount_value*$unify_service_fee)/100;
            $freelance_amount=$amount_value-$service_fee;
            $("#unify_service_fee").val($service_fee);
            $("#freelancr_amount").val($freelance_amount);
        });
          
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

        $("#end_date").on("change", function() {
            this.setAttribute(
                "data-date",
                moment(this.value, "YYYY-MM-DD")
                .format( this.getAttribute("data-date-format") )
            )
        }).trigger("change");

        

        $('#user_filter').change(function(){
            $('#filter_form').submit();
        });

        $('#user_status_filter').change(function(){
            $('#status_filter_form').submit();
        });

        $('#project_status_filter').change(function(){
            $('#status_filter_form').submit();
        });

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

        $('#pagination').change(function(){
            $('#pagination').submit();
        });

        $('#project_filter').change(function(){
            $('#project_filter').submit();
        });

        $('#freelancer_filter').change(function(){
            $('#freelancer_filter').submit();
        });

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
   
        $(".example").dataTable({
            aaSorting: [[0, 'asc']],
            bPaginate: false,
            bFilter: false,
            bInfo: false,
            bSortable: true,
            bRetrieve: true,
            aoColumnDefs: [
                { "aTargets": [ 0 ], "bSortable": true },
                { "aTargets": [ 1 ], "bSortable": true },
                { "aTargets": [ 2 ], "bSortable": true },
                { "aTargets": [ 3 ], "bSortable": false }
            ]
        }); 

        var maxGroup = 100;
  
        $(".addMore").click(function(){

            var Qus=$("#Qus_no_replace").val();

            if($('body').find('.fieldGroup').length < maxGroup){
                var fieldHTML = '<div class="fieldGroup">'+$(".fieldGroupCopy").html()+'</div>';
                $('body').find('.fieldGroup:last').after(fieldHTML);
                var new_qus=parseInt(Qus) +1;
                $("#Qus_no_replace").val(new_qus);
                // $(".Qus_no").html('Qus'+ new_qus);
            }else{
                alert('Maximum '+maxGroup+' groups are allowed.');
            }
        });

        $("body").on("click",".remove",function(){
            $(this).parents(".fieldGroup").remove();
        });

        $('.delete-record').on('click', function(event){

            event.preventDefault();

            let form = $(this).closest('form');
            swal({
                title: "You want to delete?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                closeOnClickOutside: false,
            })
            .then((willDelete) => {
            if (willDelete) {
                form.submit();
            }
            });
        });
    });
</script>
</html>
