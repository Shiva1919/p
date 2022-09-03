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
                            <h3 class="mb-0">{{ __('ACME Package SubType') }}</h3>
                            <a class="btn btn-primary" href="{{route('package.index')}}" style="margin-left:1000px;">Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success">{{session('status')}}</div>
                    @endif
                    <div class="row">
                    <a class="btn btn-success" href="{{route('subpackage.create',['id' => $subpackages->packagetype])}}">Add</a>
                    
                    </div><br><br>
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
                            @foreach($subpackage as $subpackages)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$subpackages->name}}</td>
                                <td>{{$subpackages->description}}</td>
                                <td> 
                                <form action="{{ route('subpackage.destroy',$subpackages->owncode) }}" method="POST"> 
                                    <a class="btn btn-primary" href="{{route('subpackage.edit', [$subpackages->owncode, 'id' => $subpackages->packagetype])}}">Edit</a>
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
    <script>
        $(document).ready(function () {
             $('#example').DataTable();
        });
    </script>
@endsection
