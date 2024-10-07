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

        <div class="table-container">
            <table class="table">
                <thead class="table-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">First</th>
                        <th scope="col">Last</th>
                        <th scope="col">Handle</th>
                      </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                      </tr>
                      <tr>
                        <th scope="row">2</th>
                        <td>Jacob</td>
                        <td>Thornton</td>
                        <td>@fat</td>
                      </tr>
                      <tr>
                        <th scope="row">3</th>
                        <td colspan="2">Larry the Bird</td>
                        <td>@twitter</td>
                      </tr>
                </tbody>
              </table>
        </div>

    </div>
@endsection

@section('scripts')
@endsection
