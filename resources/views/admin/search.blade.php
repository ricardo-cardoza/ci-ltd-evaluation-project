@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">Query the Cloud Database</div>
        <div class="card-body">

          {{-- Show any actual errors with querying the cloud database. --}}
          @if(session('cloud_database_error'))
            <div class="alert alert-danger" role="alert">
              <h4 class="alert-heading">There was an error with your SQL query to the cloud database!</h4>
              
              <hr>
              <p>{{ session('cloud_database_error') }}</p>
            </div>
          @endif        

          <form action="{{ route('admin.query')}}" method="POST">
            @csrf

            <div class="form-group row">
              <label for="search-query" class="col-sm-2 col-form-label">Query Cloud DB</label>
              <div class="col-sm-10">
                <input type="text" class="form-control {{ $errors->has('search-query') ? ' is-invalid' : '' }}" id="search-query" name="search-query" placeholder="Enter an SQL Query">
                <small id="search-query-help" class="form-text text-muted">Only 'SELECT' statements for now. I suppose you could try other types of statements but we don't guarantee results.</small>

                @if ($errors->has('search-query'))
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('search-query') }}</strong>
                  </span>
                @endif                  
              </div>              
            </div>

            <div class="form-group row mb-0">
              <div class="col-md-12">
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('admin.search-1') }}" class="btn btn-light" title="Clear Results">Clear Results</a>
              </div>
            </div>

            <hr>            
          </form>
          
          <br><br>

          {{-- Success message if cloud database query returns some results. --}}
          @if($queried_db && !$no_results && $sql_query)
            <div class="alert alert-success" role="alert">
              <h5 class="alert-heading">Your SQL query:<br><br><pre>

              {{ $sql_query }}

              </pre> returned some results.</h5>
              <br>
              <p><strong><em>If there are many columns try scrolling sideways with your mouse.</em></strong></p>
            </div>
          @endif

          {{-- Message if cloud database query returns empty / no results. --}}
          @if($no_results && $sql_query)
            <div class="alert alert-warning" role="alert">
              <h5 class="alert-heading">There are no matching results for your SQL query:<br><br><pre>
              
              {{ $sql_query }}

            </pre></h5>
            </div>
          @endif

          <br>            

          {{-- Non-empty cloud db results will be loaded into Vue table component if it has been queried.  --}}
          <div id="app">
            <v-client-table :data="tableData" :columns="columns" :options="options" ref="queryResultsTable" v-if="queriedDB && !noResults"></v-client-table>
          </div>

          <p><a href="{{ route('admin.upload') }}" title="Upload an SQLite DB File">Have another SQLite DB File to upload?</a></p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('header-javascript')
<script>
  {{-- If cloud database has been queried then load results.  --}}
  @if($queried_db)
    window.web_app_server_data = "{!! addslashes(json_encode([
      'queriedDB' => true,
      'noResults' => $no_results,
      'columns' => $db_query_results['columns'],
      'tableData' => $db_query_results['tableData'],
      'options' => $db_query_results['options']
    ])) !!}";
  @else
    window.web_app_server_data = "{!! addslashes(json_encode([
      'queriedDB' => false,
      'noResults' => false,
    ])) !!}";
  @endif
  
</script>
@endsection

@section('footer-javascript')
<script src="{{ asset('js/view-scripts/search.js') }}"></script>
@endsection        

