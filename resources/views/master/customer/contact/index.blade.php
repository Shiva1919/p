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
                            <h3 class="mb-0">{{ __('ACME Customer Contact') }}</h3>
                            <a class="btn btn-primary" href="{{route('customer.index')}}" style="margin-left:1000px;">Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                    @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif
                    <a class="btn btn-success" href="{{route('contact.create',['id' => $contacts->customercode])}}">Add</a><br><br>
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Name</th>
                                <th>Mobile No</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contact as $contacts)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$contacts->contactpersonname}}</td>
                                <td>{{$contacts->phoneno}}</td>
                                <td>
                                <form action="{{route('contact.destroy',$contacts->owncode)}}" method="POST"> 
                                    <a class="btn btn-primary" href="{{route('contact.edit', [$contacts->owncode, 'id' => $contacts->customercode])}}">Edit</a>
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
                                <th>Mobile No</th>
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
