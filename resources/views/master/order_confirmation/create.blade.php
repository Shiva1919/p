@extends('layouts.app', ['title' => __('User Profile')])

@section('content')
    @include('users.partials.header', [
        'title' => __('Hello') . ' '. auth()->user()->name,
        'description' => __('This is your profile page. You can see the progress you\'ve made with your work and manage your projects or assigned tasks'),
        'class' => 'col-lg-7'
    ])   

    <div class="container-fluid mt--7">
        <div class="row">
            
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <h3 class="mb-0">{{ __('ACME Order Confirmation Form') }}</h3>
                            <a class="btn btn-primary" href="{{route('order_confirmation.index')}}" style="margin-left:1000px;">Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                    @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Warning!</strong> Please check your input <br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="card-header bg-white border-0">
                            <div class="row align-items-center">
                                <h3 class="mb-0">{{ __('Sales Type') }}</h3>
                            </div>
                        </div><br/>
                        <form action="{{route('order_confirmation.store')}}" method="POST" >
                            @csrf
                            <div class="row"> 
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Sales Type</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="salestype" id="saletype" onchange = "ShowHideDiv()">
                                                <option value="Package">Package</option>
                                                <option value="User">User</option>
                                                <option value="Module">Module</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="refocfno" style="display: none">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Reference OCF No</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="eefOcfnocode" value="{{old('eefOcfnocode')}}" id="refno">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-header bg-white border-0">
                                <div class="row align-items-center">
                                    <h3 class="mb-0">{{ __('General') }}</h3>
                                </div>
                            </div><br/>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row ">
                                        <label class="col-sm-3 col-form-label">OCF No</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="ocfno"  value="{{old('ocfno')}}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="useraccount">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">User Count</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="initialusercount"  value="{{old('initialusercount')}}" id="" >
                                        </div>
                                    </div>
                                </div>  
                            </div> 
                            <div class="row" onload = "ShowDiv()">
                                <div class="col-md-6" id="fromdate">
                                    <div class="form-group row" >
                                        <label class="col-sm-3 col-form-label">From Date</label>
                                        <div class="col-sm-9">
                                        <input type="text" class="form-control" id="startdate" name="fromdate"  value="{{old('fromdate')}}">
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-md-6" id="todate">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">To Date</label>
                                        <div class="col-sm-9">
                                        <input type="text" class="form-control" id="enddate" name="todate"  value="{{old('todate')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                            <div class="col-md-6" id="validdays">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Validity Period In Days</label>
                                    <div class="col-sm-9">
                                    <input type="text" class="form-control" id="days" name="validityperiodofinitialusers"  value="{{old('validityperiodofinitialusers')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" id="purchase" style="display: none">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Purchase Date</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="purchasedate" name="purchasedate"  value="{{old('purchasedate')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-header bg-white border-0">
                            <div class="row align-items-center">
                                <h3 class="mb-0">{{ __('Customer Information') }}</h3>
                            </div>
                        </div><br/>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Customer Name</label>
                                    <div class="col-sm-9">
                                    <select name="customercode" class="form-control" id="customer" required>
                                        <option value="">Select Customer</option>
                                            @foreach($master_customer as $master_customers)
                                            <option value="{{$master_customers->owncode}}">{{$master_customers->name}}</option>
                                            @endforeach
                                    </select>
                                    </div>
                                </div>
                            </div>  
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Address</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="address1"  id="address" disabled>
                                    </div>
                                </div>
                            </div>   
                        </div>
                        <div class="row"> 
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">State</label>
                                    <div class="col-sm-9">
                                        
                                        <input type="text" class="form-control" name=""  id="state" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">City</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name=""  id="city" disabled>
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Phone No</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="phoneno" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Mobile No</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="mobileno" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Concern Person</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="concernperson" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Customer Branch</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="branchcode" id="branch" required>
                                        <option value="">Select Branch</option>
                                           
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-header bg-white border-0">
                            <div class="row align-items-center">
                                <h3 class="mb-0">{{ __('Software Information') }}</h3>
                            </div>
                        </div><br/>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Package Given</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="packagetype" id="package" required>
                                        <option value="">Select Package</option>
                                            @foreach($master_package as $master_packages)
                                            <option value="{{$master_packages->owncode}}">{{$master_packages->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Package Subtype</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="subpackage" name="packagesubtype"></select>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-header bg-white border-0">
                            <div class="row align-items-center">
                                <h3 class="mb-0">{{ __('Other Information') }}</h3>
                            </div>
                        </div><br/>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-1 col-form-label">Narration</label>
                                    <div class="col-sm-11">
                                        <input type="text" class="form-control" name="narration"  value="{{old('narration')}}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                            <div class=" text-center">
                                <button class="btn btn-primary">Add</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- @include('layouts.footers.auth') -->
    </div>
 
@endsection
  
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script language="javascript" type="text/javascript"> 

    $(document).ready(function() 
            {
                $("#startdate").datepicker({
                changeMonth: true,
                changeYear: true,
                firstDay: 1,
                dateFormat: 'dd/mm/yy',  
            });
            $("#enddate").datepicker({
                changeMonth: true,
                changeYear: true,
                firstDay: 1,
                dateFormat: 'dd/mm/yy',
            });

            $( "#purchasedate" ).datepicker({
                changeMonth: true,
                changeYear: true,
                firstDay: 1,
                dateFormat: 'dd/mm/yy',
            })

            // $( "#startdate" ).datepicker({ dateFormat: 'dd-mm-yy' });
            $( "#startdate" ).datepicker("setDate", "now");
            // $( "#enddate" ).datepicker({ dateFormat: 'dd-mm-yy'});
            $( "#enddate" ).datepicker("setDate", "1y");
            $( "#purchasedate" ).datepicker("setDate", "now");

            
                var start = $('#startdate').datepicker('getDate');
                console.log(start);
                var end   = $('#enddate').datepicker('getDate');

                if (start<end) {
                    var days   = (end - start)/1000/60/60/24;
                    $('#days').val(days);
                }
                else {
                    alert ("Please Select Greater Date Than From Date!");
                    $('#startdate').val("");
                    $('#enddate').val("");
                    $('#days').val("");
                }
        
            });
</script>


    <script type="text/javascript">
    function ShowHideDiv() 
    {
        var saletype = document.getElementById("saletype");

        if (saletype.value == "User") {
            refocfno.style.display = saletype.value == "User" ? "block" : "none";
            useraccount.style.display = saletype.value == "User" ? "block" : "none";
            fromdate.style.display = saletype.value == "User" ? "block" : "none";
            todate.style.display = saletype.value == "User" ? "block" : "none";
            validdays.style.display = saletype.value == "User" ? "block" : "none";
            purchase.style.display = saletype.value == "User" ? "none" : "none";
        } else if (saletype.value == "Module") {
            refocfno.style.display = saletype.value == "Module" ? "block" : "none";
            useraccount.style.display = saletype.value == "Module" ? "none" : "none";
            fromdate.style.display = saletype.value == "Module" ? "none" : "none";
            todate.style.display = saletype.value == "Module" ? "none" : "none";
            validdays.style.display = saletype.value == "Module" ? "none" : "none";
            purchase.style.display = saletype.value == "Module" ? "block" : "none";
        } else {
            refocfno.style.display = saletype.value == "Package" ? "none" : "none";
            useraccount.style.display = saletype.value == "Package" ? "block" : "none";
            fromdate.style.display = saletype.value == "Package" ? "block" : "none";
            todate.style.display = saletype.value == "Package" ? "block" : "none";
            validdays.style.display = saletype.value == "Package" ? "block" : "none";
            purchase.style.display = saletype.value == "Package" ? "none" : "none";
        }

    }
</script>
    <script>
        $(document).ready(function() 
        {
            jQuery('#customer').on('change',function()
            {
              let customer=jQuery(this).val();
              jQuery.ajax({
                  url:"{{ url('getCustomer') }}",
                  type:'post',
                  data:{ "_token": "{{ csrf_token() }}",customer:customer},
                  dataType : 'json',
                  success:function(result)
                  {
                        $('#address').val(result.customer[0]['address1']); 
                        $('#state').val(result.customer[0]['state']); 
                        $('#city').val(result.customer[0]['city']); 
                        $('#phoneno').val(result.customer[0]['phoneno']); 
                        $('#mobileno').val(result.customer[0]['primarymobileno']); 

                    $.each(result.customer,function(key,value){
                    });
                  }
              });
          });

          jQuery('#customer').on('change',function()
          {
              let customer=jQuery(this).val();
              jQuery.ajax({
                  url:"{{ url('getStates') }}",
                  type:'post',
                  data:{ "_token": "{{ csrf_token() }}",customer:customer},
                  dataType : 'json',
                  success:function(result){
                      console.log(result);
                      $('#state').val(result.state[0]['statename']); 
                    $.each(result.state,function(key,value){
                        // $('#address').val(result.address); 
                       
                    });
                  }
              });
          });


          jQuery('#customer').on('change',function(){
              
              let customer=jQuery(this).val();
                 //   jQuery('#user').html('<option value="">Select User</option>')
              jQuery.ajax({
                  url:"{{ url('getCitys') }}",
                  type:'post',
                  data:{ "_token": "{{ csrf_token() }}",customer:customer},
                  dataType : 'json',
                  success:function(result){
                      console.log(result);
                      $('#city').val(result.city[0]['cityname']); 
                    $.each(result.city,function(key,value){
                        // $('#address').val(result.address); 
                         $("#city").append('<input type="text" class="form-control" name="'+value.cityname+'"' );
                    });
                  }
              });
          });

          jQuery('#customer').on('change',function(){
              
              let customer=jQuery(this).val();
                 //   jQuery('#user').html('<option value="">Select User</option>')
              jQuery.ajax({
                  url:"{{ url('getBranch') }}",
                  type:'post',
                  data:{ "_token": "{{ csrf_token() }}",customer:customer},
                  dataType : 'json',
                  success:function(result){
                      console.log(result);
                      $.each(result.branch,function(key,value){
                        // $('#address').val(result.address); 
                         $("#branch").append('<option value="'+value.owncode+'">'+value.branchname+'</option>' );
                    });
                  }
              });
          });

          $('#package').on('change', function() 
            {
                let customer=jQuery(this).val();
                // alert(customer);
                 //   jQuery('#user').html('<option value="">Select User</option>')
              jQuery.ajax({
                  url:"{{ url('getSubPackage') }}",
                  type:'post',
                  data:{ "_token": "{{ csrf_token() }}",customer:customer},
                  dataType : 'json',
                  success:function(result){
                      console.log(result);
                    //   $('#subpackage').html('<option value="">Select Sub Package</option>'); 
                    $.each(result.subpackage,function(key,value){
                        // $('#address').val(result.address); 
                         $("#subpackage").append('<option value="'+value.owncode+'">'+value.name+'</option>' );
                    });
                  }
              });
            });    
            
        });
</script>


<script src="https://code.jquery.com/jquery-1.8.3.js"></script>
<script src="https://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/redmond/jquery-ui.css" />
<script>
    $(document).ready(function() 
    {
        $("#startdate").datepicker({
            changeMonth: true,
            changeYear: true,
            firstDay: 1,
            dateFormat: 'dd/mm/yy',  
		});
		$("#enddate").datepicker({
            changeMonth: true,
            changeYear: true,
            firstDay: 1,
            dateFormat: 'dd/mm/yy',
		});

        $( "#purchasedate" ).datepicker({
            changeMonth: true,
            changeYear: true,
            firstDay: 1,
            dateFormat: 'dd/mm/yy',
        })

         $( "#startdate" ).datepicker({ dateFormat: 'dd-mm-yy' });
        // $( "#startdate" ).datepicker("setDate", "now");
         $( "#enddate" ).datepicker({ dateFormat: 'dd-mm-yy'});
        // $( "#enddate" ).datepicker("setDate", "1y");
        $( "#purchasedate" ).datepicker({ dateFormat: 'dd-mm-yy' });

        $('#enddate').change(function() {
            var start = $('#startdate').datepicker('getDate');
            console.log(start);
            var end   = $('#enddate').datepicker('getDate');

            if (start<end) {
                var days   = (end - start)/1000/60/60/24;
                $('#days').val(days);
            }
            else {
                alert ("Please Select Greater Date Than From Date!");
                $('#startdate').val("");
                $('#enddate').val("");
                $('#days').val("");
            }
        }); //end change function
    }); //end ready
</script>
