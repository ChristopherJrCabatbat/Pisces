@extends('admin.layout')

@section('title', 'Admin')

@section('styles-links')
@endsection

@section('sidebar')
    <li><a href="#" class="active">Dashboard</a></li>
    <li><a href="{{ route('admin.menu') }}">Menu</a></li>
@endsection

@section('main-content')
    <div class="main-content">
        <h1>
            Admin Dashboard
        </h1>
    </div>
@endsection

@section('scripts')
@endsection
