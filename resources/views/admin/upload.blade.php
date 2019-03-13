@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">Upload SQLite Database</div>
        <div class="card-body">
          @if(session('sqlite_upload_errors'))
            <div class="alert alert-danger" role="alert">
              <h4 class="alert-heading">There appears to be validation errors in your SQLite database file.</h4> 
              @php
                $sqlite_upload_errors = session('sqlite_upload_errors');
              @endphp
              <hr>
              <ul>
              @foreach($sqlite_upload_errors as $error_message)
                <li>{{ $error_message }}</li>
              @endforeach
              </ul>
            </div>
          @endif

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

          <form action="{{ route('admin.import')}}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
              <div class="custom-file">
                <input type="file" id="file-upload" name="file-upload"  class="custom-file-input {{ $errors->has('file-upload') ? ' is-invalid' : '' }}" >
                <label for="file-upload" class="custom-file-label">Choose and SQLite database to upload</label>
                
                @if ($errors->has('file-upload'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('file-upload') }}</strong>
                  </span>
                @endif                
              </div>
            </div>

            <div class="form-group row mb-0">
              <div class="col-md-12">
                <button type="submit" class="btn btn-primary">Upload</button>
              </div>
            </div>

          </form>

          <br>
          <p><a href="{{ route('admin.search-1') }}" title="Search Cloud DB">Already uploaded an SQLite DB File? Search the Cloud DB.</a></p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('footer-javascript')
<script defer>
  jQuery('#file-upload').on('change',function(){

    //var fileUploadName = $(this).val();
    var fileUploadName = this.files[0].name;
    //replace the label
    jQuery(this).next('.custom-file-label').html(fileUploadName);
  });  
</script>
@endsection
