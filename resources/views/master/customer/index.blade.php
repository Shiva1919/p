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
                            <h3 class="mb-0">{{ __('ACME Customers') }}</h3>
                        </div>
                    </div>
                    <div class="card-body">
                    @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif
                    <a class="btn btn-success" href="{{route('customer.create')}}">Add</a><br><br>
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th> Name</th>
                                <th>Entry Code</th>
                                <th>Address</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($customer as $customers)
                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{$customers->name}}</td>
                                <td>{{$customers->entrycode}}</td>
                                <td>{{$customers->address1}}</td>
                                <td>
                                <form action="{{route('customer.destroy',$customers->owncode)}}" method="POST"> 
                                    <a class="btn btn-primary" href="{{ route('branch.index',['id' => $customers->owncode])}}">Branch</a>
                                    <a class="btn btn-success" href="{{ route('contact.index',['id' => $customers->owncode])}}">Contact</a>
                                    <a class="btn btn-primary" href="{{route('customer.edit', $customers->owncode)}}">Edit</a>
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
                                <th> Name</th>
                                <th>Entry Code</th>
                                <th>Address</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
