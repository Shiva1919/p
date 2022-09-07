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
                            <h3 class="mb-0">{{ __('ACME Package Type') }}</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Your Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="exampleFormControlInput1" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Description</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="exampleFormControlInput1" required="">
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
