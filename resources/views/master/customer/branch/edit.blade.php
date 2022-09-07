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
                            <h3 class="mb-0">{{ __('Update ACME Customer Branch') }}</h3>
                            <a class="btn btn-primary" href="{{route('branch.index', ['id' => $branch->owncode])}}" style="margin-left:1000px;">Back</a>
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
                        <form action="{{route('branch.update', [$branch->owncode, 'id' => $branch->customercode] )}}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="col-xs-6 col-sm-6 col-md-6">
                                <input type="hidden" name="customercode" value="{{$branch->customercode}}" class="form-control"><br/>
                            </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row ">
                                    <label class="col-sm-3 col-form-label">Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="branchname" value="{{ $branch->branchname }}" id="exampleFormControlInput1" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Address1</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="branchaddress1" value="{{ $branch->branchaddress1 }}" id="exampleFormControlInput1" required="">
                                    </div>
                                </div>
                            </div>  
                        </div> 
                        <div class="row"> 
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">State</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="branchstate" value="{{ $branch->branchstate }}" id="state">
                                            @foreach($state_master as $state_masters)
                                            <option value="{{$state_masters->owncode}}">{{$state_masters->statename}}</option>
                                            @endforeach
                                            @foreach($state as $key => $states)
                                                <option value="{{$states->owncode}}">{{$states->statename}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">District</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="branchdistrict" value="{{ $branch->branchdistrict }}" id="district">
                                            @foreach($district_master as $district_masters)
                                            <option value="{{$district_masters->OwnCode}}">{{$district_masters->DistrictName}}</option>
                                            @endforeach 
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
                                        <select class="form-control" name="branchtaluka" value="{{ $branch->branchtaluka }}" id="taluka">
                                            @foreach($taluka_master as $taluka_masters)
                                            <option value="{{$taluka_masters->owncode}}">{{$taluka_masters->talukaname}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">City</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="branchcity" value="{{ $branch->branchcity }}" id="city">
                                            @foreach($city_master as $city_masters)
                                            <option value="{{$city_masters->owncode}}">{{$city_masters->cityname}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
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
