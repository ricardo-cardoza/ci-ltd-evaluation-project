<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Config;
use File;
use Validator;

class AdminController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      $this->middleware('auth');
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function index()
  {
      return view('admin/home');
  }

  /**
   * Show the page to upload SQLite file upload.
   * 
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function upload() {
    return view('admin/upload');
  }

  /**
   * Imports the SQLite file upload into the cloud database.
   * 
   * @return \Illuminate\Http\RedirectResponse
   */
  public function import(Request $request) {

    // Clean up all previously uploaded files
    $previous_file_uploads = File::allFiles(storage_path('app/file-uploads'));

    if($previous_file_uploads) {
      foreach($previous_file_uploads as $file_upload) {
        File::delete($file_upload->getPathname());
      }
    }

    // Validate file upload request
    $this->validate($request, [
      'file-upload' => [ 'required', 'file', 'mimetypes:application/x-sqlite3' ]
    ]);

    // Once validation passes store SQLite upload
    $file_upload_store_path = $request->file('file-upload')->store('file-uploads', 'local');
    $file_upload_store_path = config('filesystems.disks.local.root')."/{$file_upload_store_path}";

    // Update SQLite database connection configuration to point to newly uploaded file
    Config::set('database.connections.sqlite.database', $file_upload_store_path);
    DB::reconnect('sqlite');

    // Validate and Import 'Tool' table data
    $rows = DB::connection('sqlite')->table('Tool')->get();

    $original_validation_messages = [
      'date' => __('validation.date'),
      'integer' => __('validation.integer'),
      'numeric' => __('validation.numeric'),
      'required' => __('validation.required')
    ];

    if($rows->isNotEmpty()) {
      $validator_rules = config('web-app.validation_rules.tool_db_row');

      // Validate data in table
      foreach($rows as $row_number => $row) {
        $insert_data = get_object_vars($row);
        $validation_messages_for_db_row = call_user_func_array('array_merge', array_map(function($message_key, $message_value) use ($row_number) {
              return [ $message_key => "On 'Tool' table, row {$row_number}: {$message_value}" ];
            }, array_keys($original_validation_messages), $original_validation_messages));   

        $validator = Validator::make($insert_data, $validator_rules, $validation_messages_for_db_row);

        if($validator->fails()) {
          return redirect()->route('admin.upload')->with('sqlite_upload_errors', $validator->errors()->all());
        }

        unset($validator);
      }

      // Import data in table
      foreach($rows as $row_number => $row) {
        try {
          $insert_data = get_object_vars($row);
          DB::connection('mysql')->table('Tool')->insert($insert_data);
        }catch(\Exception $e) {
          return redirect()->route('admin.upload')->with('cloud_database_error', $e->getMessage());
        }
      }
    }

    // Validate and Import 'CalibrationResult' table data
    $rows = DB::connection('sqlite')->table('CalibrationResult')->get();
    if($rows->isNotEmpty()) {
      $validator_rules = config('web-app.validation_rules.calibration_result_db_row');

      // Validate data in table
      foreach($rows as $row_number => $row) {
        $insert_data = get_object_vars($row);
        $validation_messages_for_db_row = call_user_func_array('array_merge', array_map(function($message_key, $message_value) use ($row_number) {
              return [ $message_key => "On 'CalibrationResult' table, row {$row_number}: {$message_value}" ];
            }, array_keys($original_validation_messages), $original_validation_messages));   

        $validator = Validator::make($insert_data, $validator_rules, $validation_messages_for_db_row);

        if($validator->fails()) {
          return redirect()->route('admin.upload')->with('sqlite_upload_errors', $validator->errors()->all());
        }

        unset($validator);
      }

      // Import data in table
      foreach($rows as $row) {
        try {
          $insert_data = get_object_vars($row);
          DB::connection('mysql')->table('CalibrationResult')->insert($insert_data);
        }catch(\Exception $e) {
          return redirect()->route('admin.upload')->with('cloud_database_error', $e->getMessage());
        }
      }
    }

    // No issuess
    return redirect()->route('admin.upload')->with('success', "Successfully imported SQLite database file into the Cloud database.");

  }


  /**
   * Show the page to send SQL query requests for the cloud database.
   * 
   * @return \Illuminate\Contracts\Support\Renderable
   */ 
  public function search() {

    return view('admin/search', [
      'queried_db' => false,
      'no_results' => false,
      'db_query_results' => null
    ]);
  } 

  /**
   * Show the page to make SQL queries to the cloud database and display the results of those queries.
   * 
   * @return \Illuminate\Contracts\Support\Renderable
   */ 
  public function query(Request $request) {
    // Validate db query request
    $this->validate($request, [
      'search-query' => [ 
        'required', 
        'regex:/^select/i',  // Only select statements allowed
        //'not_regex:/^(delete|update)/i' // any other kind of steatement except those that update db allowed
      ]
    ], $custom_validation_messages = [
      'required' => "Enter an SQL query.",
      'regex' => "Only 'SELECT' statements allowed here."
    ]);

    $cloud_db_sql_query = $request->get('search-query');

    // Make the request to the cloud database
    try {
      $results = DB::connection('mysql')->select(DB::connection('mysql')->raw($cloud_db_sql_query));
    }catch(\Exception $e) {
      return redirect()->route('admin.search-1')->with('cloud_database_error', $e->getMessage());
    }

    // Assume no results
    $no_results = true;
    $db_query_results = [
      'columns' => [],
      'tableData' => [],
      'options' => []
    ];     

    // If there are non-empty results, prepare the results to be transformed into 
    // JSON objects within the view and then passed down to a Vue table component
    if(!empty($results)) {
      $no_results = false;
      $transformed_results = array_map(function($row_object) {
        return (array) $row_object;
      }, $results);

      // free up memory
      unset($results);

      // for each row, cast any properties that are large integers into strings
      // so that they won't be rounder or truncated by JavaScript  in the view.
      $casted_results = array_map(function(&$row) {
        $transform_columns = [
          'ToolId',
          'CalibrationResultId',
          'Voltmeter',
          'Ammeter',
          'Supply',
          'Load',
          'TickBox',
          'Scanner',
          'Jig',
          'Tick_EepromRes',
          'Tick_FlashRes',
          'CalStatus',
          'Tick_ProductId',
          'AAx',
          'AAy',
          'AAz',
          'AOff'
        ];

        foreach($transform_columns as $column) {
          if(isset($row[$column]) && !is_null($row[$column])) {
            $row[$column] = (string) $row[$column];
          }
        }

        return $row;
      }, $transformed_results);

      // free up memory
      unset($transformed_results);

      // Get column names for table in view which will display results
      $table_columns = array_keys($casted_results[0]);

      $db_query_results = [
        'columns' => $table_columns,
        'tableData' => $casted_results,
        'options' => [
          'headings' => array_combine($table_columns, $table_columns),
          'sortable' => $table_columns
        ]
      ];

    }

    // return results or lackthereof to the view
    return view('admin/search', [
      'queried_db' => true,
      'no_results' => $no_results,
      'db_query_results' => $db_query_results,
      'sql_query' => $cloud_db_sql_query
    ]);
  } 
}
