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
                            <h3 class="mb-0">{{ __('Update ACME Package Type') }}</h3>
                            <a class="btn btn-primary" href="{{route('package.index')}}" style="margin-left:1000px;">Back</a>
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
                        <form action="{{route('package.update',$package->owncode)}}" method="POST">
                        @csrf
                        @method('PUT')
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Package Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="name" value="{{ $package->name }}" id="exampleFormControlInput1" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Description</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="description" value="{{ $package->description }}" id="exampleFormControlInput1" required="">
                                    </div>
                                </div>
                            </div>                             
                            <div class=" text-center">
                                <button class="btn btn-primary">Update Package</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- @include('layouts.footers.auth') -->
    </div>
@endsection
