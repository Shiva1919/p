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
                            <h3 class="mb-0">{{ __('ACME Package') }}</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif
                    <a class="btn btn-success" href="{{route('package.create')}}">Add</a><br/><br/>
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($package as $packages)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$packages->name}}</td>
                                <td>{{$packages->description}}</td>
                                <td>
                                <form action="{{route('package.destroy',$packages->owncode)}}" method="POST"> 
                                    <a class="btn btn-primary" href="{{route('subpackage.index',['id' => $packages->owncode])}}">SubPackage</a>
                                    <a class="btn btn-success" href="{{route('module.index',['id' => $packages->owncode])}}">Module</a>
                                    <a class="btn btn-primary" href="{{route('package.edit',$packages->owncode)}}">Edit</a>
                                    @csrf
                                    @method('DELETE')
                    
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                            <th>Sr.No</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- @include('layouts.footers.auth') -->
    </div>
   
@endsection
