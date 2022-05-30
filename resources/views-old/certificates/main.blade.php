@extends('layout')

@section('content')
    <style>
    .uper {
        margin-top: 40px;
    }
    </style>
    <div class="card uper">
        <div class="card-header">
            Certificates
        </div>
        <div class="card-body">

            <form method="post" action="/certificate/create">
                @csrf
                <div class="form-group">
                    <label for="domain">Domain name</label>
                    <input type="text" class="form-control" name="domain"/>
                </div>

                <button type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>
    <table class="table table-striped">
        <thead>
            <tr>
            <th scope="col">ID</th>
            <th scope="col">Domain</th>
            <th scope="col"></th>
            <th scope="col"></th>
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
            <td>Larry</td>
            <td>the Bird</td>
            <td>@twitter</td>
            </tr>
        </tbody>
        </table>
    </div>


@endsection