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
                            <h3 class="mb-0">{{ __('Update Order Confirmation Form') }}</h3>
                            <a class="btn btn-primary" href="{{route('order_confirmation.index')}}" style="margin-left:900px;">Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                    @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Warning!</strong> Please check input field <br><br>
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
                        <form action="{{route('order_confirmation.update', $order_confirmation->owncode)}}" method="POST" >
                            @csrf
                            @method('PUT')
                            <div class="row"> 
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Sales Type</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="salestype" value="{{$order_confirmation->salestype}}" id="saletype" onload = "ShowHideDiv()" >
                                                <option value="{{$order_confirmation->salestype}}">{{$order_confirmation->salestype}}</option>
                                                <option value="Package">Package</option>
                                                <option value="User">User</option>
                                                <option value="Module">Module</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="refocfno" style="display: none">
                                    <div class="form-group row ">
                                        <label class="col-sm-3 col-form-label">Reference OCF No</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="eefOcfnocode" value="{{$order_confirmation->eefOcfnocode}}" id="exampleFormControlInput1">
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
                                            <input type="text" class="form-control" name="ocfno" value="{{$order_confirmation->ocfno}}" id="exampleFormControlInput1" >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="useraccount">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">User Count</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="initialusercount" value="{{$order_confirmation->initialusercount}}"  id="exampleFormControlInput1" >
                                        </div>
                                    </div>
                                </div>  
                            </div> 
                            <div class="row">
                                <div class="col-md-6" id="fromdate">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">From Date</label>
                                        <div class="col-sm-9">
                                        <input type="text" id="startdate" class="form-control" name="fromdate" value="{{$order_confirmation->fromdate}}">
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-md-6" id="todate">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">To Date</label>
                                        <div class="col-sm-9">
                                        <input type="text" id="enddate" class="form-control" name="todate" value="{{$order_confirmation->todate}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6" id="validdays">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Validity Period In Days</label>
                                        <div class="col-sm-9">
                                        <input type="text" id="days" class="form-control" name="validityperiodofinitialusers" value="{{$order_confirmation->validityperiodofinitialusers}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="purchase" style="display: none">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Purchase Date</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="purchasedate" name="purchasedate"  value="{{$order_confirmation->purchasedate}}">
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
                                        <select name="customercode" class="form-control" id="customer">
                                                @foreach($master_customer as $master_customers)
                                                <option value="{{$master_customers->owncode}}">{{$master_customers->name}}</option>
                                                @endforeach
                                                @foreach($customer as $customers)
                                                <option value="{{$customers->owncode}}">{{$customers->name}}</option>
                                                @endforeach
                                        </select>
                                        </div>
                                    </div>
                                </div>  
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Address</label>
                                        <div class="col-sm-9">
                                          
                                            <input type="text" class="form-control" name="address1" value="{{$master_customers->address1}}" id="address" >
                                          
                                        </div>
                                    </div>
                                </div>   
                            </div>
                            <div class="row"> 
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">State</label>
                                        <div class="col-sm-9">
                                        @foreach($master_state as $master_states)
                                            <input type="text" class="form-control" name="state" value="{{$master_states->statename}}" disabled>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">City</label>
                                        <div class="col-sm-9">
                                            @foreach($master_city as $master_citys)
                                            <input type="text" class="form-control"  value="{{$master_citys->cityname}}" >
                                            @endforeach
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Phone No</label>
                                        <div class="col-sm-9">
                                        @foreach($master_customer as $master_customers)
                                            <input type="text" class="form-control" value="{{$master_customers->phoneno}}" disabled>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Mobile No</label>
                                        <div class="col-sm-9">
                                        @foreach($master_customer as $master_customers)
                                            <input type="text" class="form-control" value="{{$master_customers->primarymobileno}}" disabled>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Concern Person</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="concernperson" value="{{$order_confirmation->concernperson}}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Customer Branch</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="branchcode" >
                                                @foreach($master_branch as $master_branchs)
                                                <option value="{{$master_branchs->owncode}}">{{$master_branchs->branchname}}</option>
                                                @endforeach
                                                @foreach($branch as $branchs)
                                                <option value="{{$branchs->owncode}}">{{$branchs->branchname}}</option>
                                                @endforeach
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
                                            <select class="form-control" name="packagetype" id="package">
                                                @foreach($master_package as $master_packages)
                                                <option value="{{$master_packages->owncode}}">{{$master_packages->name}}</option>
                                                @endforeach
                                                @foreach($package as $packages)
                                                <option value="{{$packages->owncode}}">{{$packages->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Package Subtype</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" id="subpackage" name="packagesubtype" >
                                                @foreach($subpackage as $subpackages)
                                                <option value="{{$subpackages->owncode}}">{{$subpackages->name}}</option>
                                                @endforeach
                                            </select>                                        
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
                                            <input type="text" class="form-control" name="narration" value="{{$order_confirmation->narration }}" id="exampleFormControlInput1" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div><br/>
                            <div class=" text-center">
                                <button class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- @include('layouts.footers.auth') -->
    </div>
   
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() 
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

           
             var v = $("#customer").val();
            // alert(v);
                 jQuery.ajax({
                  url:"{{ url('getBranch') }}",
                  type:'post',
                  data:{ "_token": "{{ csrf_token() }}",v:v},
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
                  success:function(result){

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
                       
                    });
                  }
              });
          });


          jQuery('#customer').on('change',function(){
              
              let customer=jQuery(this).val();
                
              jQuery.ajax({
                  url:"{{ url('getCitys') }}",
                  type:'post',
                  data:{ "_token": "{{ csrf_token() }}",customer:customer},
                  dataType : 'json',
                  success:function(result){
                     
                      $('#city').val(result.city[0]['cityname']); 
                    $.each(result.city,function(key,value){
                       
                         $("#city").append('<input type="text" class="form-control" name="'+value.cityname+'"' );
                    });
                  }
              });
          });

          $('#package').on('change', function() 
            {
                let customer=jQuery(this).val();
                jQuery.ajax({
                  url:"{{ url('getSubPackage') }}",
                  type:'post',
                  data:{ "_token": "{{ csrf_token() }}",customer:customer},
                  dataType : 'json',
                  success:function(result){
                    $.each(result.subpackage,function(key,value){
                        //  $('#address').val(result.name); 
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
$(document).ready(function() {

$( "#startdate,#enddate" ).datepicker({
changeMonth: true,
changeYear: true,
firstDay: 1,
dateFormat: 'dd/mm/yy',
})

$( "#startdate" ).datepicker({ dateFormat: 'dd-mm-yy' });
$( "#enddate" ).datepicker({ dateFormat: 'dd-mm-yy' });

$('#enddate').change(function() {
var start = $('#startdate').datepicker('getDate');
var end   = $('#enddate').datepicker('getDate');

if (start<end) {
var days   = (end - start)/1000/60/60/24;
$('#days').val(days);
}
else {
alert ("You cant come back before you have been!");
$('#startdate').val("");
$('#enddate').val("");
$('#days').val("");
}
}); //end change function
}); //end ready
</script>


@endsection
