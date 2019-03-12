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
}
