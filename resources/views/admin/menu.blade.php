@extends('admin.layout')

@section('title', 'Admin')

@section('styles-links')
@endsection

@section('sidebar')
    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li><a href="#" class="active">Menu</a></li>
@endsection

@section('main-content')
    <div class="main-content">
        <h1>
            Admin Menu
        </h1>
    </div>
@endsection

@section('scripts')
@endsection
