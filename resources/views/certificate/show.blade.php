@extends('layout')

@section('content')

<div class="jumbotron">
    <h1 class="display-4">Certificate {{ $certificate->id }}</h1>
    <p class="lead"></p>
    <hr class="my-4">
    <p></p>
    <p class="lead">
    </p>
</div>


<div class="card uper">
  <div class="card-header">
  </div>
  <div class="card-body">

       <form method="post" action="{{ route('certificate-add-domain', $certificate->id) }}">
          @csrf

           <div class="form-group">
              <label for="domains">Domain names</label>
              <input type="text" class="form-control" name="domains"/>
          </div>

          <button type="submit" class="btn btn-primary">Add</button>
       </form>
  </div>
</div>


@endsection
