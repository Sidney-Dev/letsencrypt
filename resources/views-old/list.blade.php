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
      Certificate lisr
  </div>
  <div class="card-body">

      <form method="post" action="/certificate/store">
          @csrf
          <div class="form-group">
              <label for="domain">Domain name</label>
              <input type="text" class="form-control" name="domain"/>
          </div>

          <button type="submit" class="btn btn-primary">Create</button>
      </form>
  </div>
</div>
@endsection