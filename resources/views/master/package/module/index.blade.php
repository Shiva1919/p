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
                            <h3 class="mb-0">{{ __('ACME Modules') }}</h3>
                            <a class="btn btn-primary" href="{{route('package.index')}}" style="margin-left:1000px;">Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif
                    <div class="row">    
                    <a class="btn btn-success" href="{{route('module.create', ['id' => $modules->ProductType] )}}">Add</a>
                    
                    </div><br><br>
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($module as $modules)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$modules->ProductName}}</td>
                                <!-- <td>{{$modules->ProductDescription}}</td> -->
                                <td>
                                <form action="{{ route('module.destroy',$modules->ProductId) }}" method="POST"> 
                                    <a class="btn btn-primary" href="{{route('module.edit', [$modules->ProductId, 'id' => $modules->ProductType])}}">Edit</a>
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
                                <!-- <th>Description</th> -->
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table><br>
                    
                    </div>
                </div>
            </div>
        </div>
        
        <!-- @include('layouts.footers.auth') -->
    </div>
    <script>
        $(document).ready(function () {
             $('#example').DataTable();
        });
    </script>
@endsection
