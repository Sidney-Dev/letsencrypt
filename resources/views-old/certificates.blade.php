<!-- create.blade.php -->

@extends('layout')

@section('content')
<style>
  .uper {
    margin-top: 40px;
  }
</style>
<div class="card uper">
  <div class="card-header">
      Certificate
  </div>
  <div class="card-body">

      <form method="post" action="/certificates">
          @csrf
          <div class="form-group">
              <label for="domains">Domain names</label>
              <input type="text" class="form-control" name="domains"/>
          </div>

          <button type="submit" class="btn btn-primary">Create</button>
      </form>
  </div>
</div>
@endsection