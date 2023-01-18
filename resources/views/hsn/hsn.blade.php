<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="shortcut icon" type="image/jpg" href="{{ asset('images/icon.png') }}"/>

    <!-- Font Awesome UI KIT-->
    <script src="https://kit.fontawesome.com/f75ab26951.js" crossorigin="anonymous"></script>

    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{asset('css/app.css')}}" rel="stylesheet">
    <link href="{{asset('admin/css/sb-admin-2.min.css')}}" rel="stylesheet">
</head>
<body>

    {{-- Message --}}
@if (Session::has('success'))
<div class="alert alert-success alert-dismissible" role="alert">
    {{-- <button type="button" class="close" data-dismiss="alert">
        <i class="fa fa-times"></i>
    </button> --}}
    <strong>Success !</strong> {{ session('success') }}
</div>
@endif
@if (Session::has('allready'))
<div class="alert alert-danger alert-dismissible" role="alert">
    {{-- <button type="button" class="close" data-dismiss="alert">
        <i class="fa fa-times"></i>
    </button> --}}
    <strong>Success !</strong> {{ session('allready') }}
</div>
@endif

@if (Session::has('error'))
<div class="alert alert-danger alert-dismissible" role="alert">
    {{-- <button type="button" class="close" data-dismiss="alert">
        <i class="fa fa-times"></i>
    </button> --}}
    <strong>Error !</strong> {{ session('error') }}
</div>
@endif
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Import File</h1>
            {{-- <a href="{{route('users.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i --}}
                    {{-- class="fas fa-arrow-left fa-sm text-white-50"></i> Back</a> --}}
        </div>

        {{-- Alert Messages --}}
        {{-- @include('common.alert') --}}

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Import Hsn</h6>
            </div>
            {{-- <form method="POST" action="{{route('users.upload')}}" enctype="multipart/form-data"> --}}
                <form method="POST" action="{{url('/import')}}" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-group row">

                        {{-- <div class="col-md-12 mb-3 mt-3">
                            <p>Please Upload CSV in Given Format <a href="{{ asset('files/sample-data-sheet.csv') }}" target="_blank">Sample CSV Format</a></p>
                        </div> --}}
                        {{-- File Input --}}
                        <div class="col-sm-12 mb-3 mt-3 mb-sm-0">
                            <span style="color:red;">*</span>File Input(Datasheet)</label>
                            <input
                                type="file"
                                class="form-control form-control-user @error('file') is-invalid @enderror"
                                id="exampleFile"
                                name="file"
                                value="{{ old('file') }}">

                            @error('file')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>

                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-success btn-user float-right mb-3">Upload HSN & SAN</button>

                </div>
            </form>
        </div>

    </div>
</body>
</html>
