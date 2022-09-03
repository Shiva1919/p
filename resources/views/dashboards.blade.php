@extends('layouts.app')

@section('content')
@include('users.partials.header', [
        'title' => __('Hello') . ' '. auth()->user()->name,
        'description' => __('This is your profile page. You can see the progress you\'ve made with your work and manage your projects or assigned tasks'),
        'class' => 'col-lg-12'
    ])   

    <div>
    <img src="{{ asset('') }}assets/img/theme/acme_logo.jpeg" class="navbar-brand-img" alt="..." style="width: 30%;margin-left:500px;">
    </div>

@endsection