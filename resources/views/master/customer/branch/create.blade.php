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
                            <h3 class="mb-0">{{ __('Create ACME Customer Branch') }}</h3>
                            <a class="btn btn-primary" href="{{route('branch.index', ['id' => $request->id])}}" style="margin-left:1000px;">Back</a>
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
                        <form action="{{route('branch.store', $request)}}" method="POST">
                        @csrf
                        <div class="col-xs-3 col-sm-3 col-md-3">
                            <input type="hidden" name="customercode" value="{{$request->id}}" class="form-control" ><br/>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row ">
                                    <label class="col-sm-3 col-form-label">Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="branchname" value="{{old('branchname')}}" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Address1</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="branchaddress1" value="{{old('branchaddress1')}}" required="">
                                    </div>
                                </div>
                            </div>  
                        </div> 
                        <div class="row"> 
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">State</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="branchstate" id="state">
                                            <option value="">Select State</option>
                                                @foreach($master_state as $master_states)
                                                <option value="{{$master_states->owncode}}">{{$master_states->statename}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">District</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="branchdistrict" id="district">
                                        </select>
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Taluka</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="branchtaluka" id="taluka">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">City</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="branchcity" id="city">
                                        </select>
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
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() 
        {
            $('#state').on('change', function() 
            {
                var StateID = this.value;
                $("#district").html('');
                $.ajax({
                    url:"{{url('getDistrict')}}",
                    type: "POST",
                    data: {
                        StateID: StateID,
                        _token: '{{csrf_token()}}' 
                    },
                    dataType : 'json',
                    success: function(result){
                        $('#district').html('<option value="">Select District</option>'); 
                        $.each(result.District,function(key,value){
                        $("#district").append('<option value="'+value.OwnCode+'">'+value.DistrictName+'</option>');
                        });
                        $('#taluka').html('<option value="">Select District First</option>'); 
                    }
                });
            });    
            $('#district').on('change', function() {
                var districtid = this.value;
                $.ajax({
                    url:"{{url('getTaluka')}}",
                    type: "POST",
                    data: {
                        districtid: districtid,
                        _token: '{{csrf_token()}}' 
                    },
                    dataType : 'json',
                    success: function(result){
                        $('#taluka').html('<option value="">Select Taluka</option>'); 
                        $.each(result.Taluka,function(key,value){
                        $("#taluka").append('<option value="'+value.owncode+'">'+value.talukaname+'</option>');
                        });
                        $('#city').html('<option value="">Select Taluka First</option>'); 
                    }
                });
            });
            $('#taluka').on('change', function() {
                var talukaid = this.value;
                $.ajax({
                    url:"{{url('getCity')}}",
                    type: "POST",
                    data: {
                        talukaid: talukaid,
                        _token: '{{csrf_token()}}' 
                    },
                    dataType : 'json',
                    success: function(result){
                        $('#city').html('<option value="">Select City</option>'); 
                        $.each(result.City,function(key,value){
                        $("#city").append('<option value="'+value.owncode+'">'+value.cityname+'</option>');
                        });
                    }
                });
            });
        });
</script>
@endsection
