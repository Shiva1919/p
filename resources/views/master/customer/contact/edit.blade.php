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
                            <h3 class="mb-0">{{ __('Update ACME Customer Contacts') }}</h3>
                            <a class="btn btn-primary" href="{{route('branch.index', ['id' => $contact->owncode])}}" style="margin-left:1000px;">Back</a>
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
                        <form action="{{route('contact.update',[$contact->owncode, 'id' => $contact->customercode])}}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="col-xs-6 col-sm-6 col-md-6">
                                <input type="hidden" name="customercode" value="{{$contact->customercode}}" class="form-control"><br/>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row ">
                                        <label class="col-sm-3 col-form-label">Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="contactpersonname" value="{{$contact->contactpersonname}}" id="exampleFormControlInput1" required="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Mobile No</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="mobileno" value="{{$contact->mobileno}}" id="exampleFormControlInput1" required="">
                                        </div>
                                    </div>
                                </div>  
                            </div> 
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Phone No</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="phoneno" value="{{$contact->phoneno}}" id="exampleFormControlInput1" required="">
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Email ID</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="emailid" value="{{$contact->emailid}}" id="exampleFormControlInput1" required="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row"> 
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Branch</label>
                                        <div class="col-sm-9">
                                            <select class="form-control" name="branch" value="{{$contact->branch}}">
                                                @foreach($branch_master as $branch_masters)
                                                <option value="{{$branch_masters->owncode}}" >{{$branch_masters->branchname}}</option>
                                                @endforeach
                                                @foreach($branch as $key => $branchs)
                                                    <option value="{{ $branchs->owncode }}" >{{$branchs->branchname}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <div class=" text-center">
                                    <button class="btn btn-primary">Update Contact</button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- @include('layouts.footers.auth') -->
    </div>
@endsection
