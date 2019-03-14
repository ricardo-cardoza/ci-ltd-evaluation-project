@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">Remove All Data from the Cloud Database</div>
        <div class="card-body">

          @if(session('cloud_database_error'))
            <div class="alert alert-danger" role="alert">
              <h4 class="alert-heading">There was an error saving the data from the SQLite database file to the cloud database</h4>
              
              <hr>
              <p>{{ session('cloud_database_error') }}</p>
            </div>
          @endif

          @if(session('success'))
            <div class="alert alert-success" role="alert">
              <h4 class="alert-heading">{{ session('success') }}</h4>
            </div>
          @endif

          @if(session('info'))
            <div class="alert alert-info" role="alert">
              <h4 class="alert-heading">{{ session('info') }}</h4>
            </div>
          @endif                     

          <form action="{{ route('admin.destroy')}}" method="POST">
            @csrf

            Are you sure you want to remove all the imported data in the cloud database and start over again?

            <div class="form-check">
              <input class="form-check-input" type="radio" name="clear_db_confirmation" id="clear_db_confirmation_yes" value="yes" >
              <label class="form-check-label" for="clear_db_confirmation_yes">
                Yes
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="clear_db_confirmation" id="clear_db_confirmation_no" value="no" checked>
              <label class="form-check-label" for="clear_db_confirmation_no">
                No
              </label>
            </div>

            <br>
            <div class="form-group row mb-0">
              <div class="col-md-12">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </div>            
          </form>

          <br>
          <p><a href="{{ route('admin.upload') }}" title="Upload an SQLite DB File">Start over again? Have an SQLite DB File to upload?</a></p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection