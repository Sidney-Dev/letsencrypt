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

            <form method="post" action="/certificate">
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
            @foreach($certificates as $certificate)
                <tr>
                    <th scope="row">{{ $certificate->id }}</th>
                    <td>{{ $certificate->domain }}</td>
                    <td><a href="{{ route('certificate-detail', $certificate->id) }}">Add domains</a></td>
                    <td>@mdo</td>
                </tr>
            @endforeach
        </tbody>
        </table>
    </div>


@endsection
