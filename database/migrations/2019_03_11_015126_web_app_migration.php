<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WebAppMigration extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('Tool', function (Blueprint $table) {
      $table->bigInteger('ToolId');
      $table->string('Serial')->nullable()->default(null);
      $table->string('Model')->nullable()->default(null);
      $table->string('Manufacturer')->nullable()->default(null);

      // indexes and foreign keys
      $table->primary('ToolId');      
    });

    Schema::create('CalibrationResult', function (Blueprint $table) {
      $table->bigInteger('CalibrationResultId');
      $table->dateTime('RunTime')->nullable()->default(null);
      $table->string('OperatorId')->nullable()->default(null);
      $table->bigInteger('Voltmeter')->nullable()->default(null);
      $table->bigInteger('Ammeter')->nullable()->default(null);
      $table->bigInteger('Supply')->nullable()->default(null);
      $table->bigInteger('Load')->nullable()->default(null);
      $table->bigInteger('TickBox')->nullable()->default(null);
      $table->bigInteger('Scanner')->nullable()->default(null);
      $table->bigInteger('Jig')->nullable()->default(null);
      $table->string('Tick_Firmware')->nullable()->default(null);
      $table->string('Tick_Guid')->nullable()->default(null);
      $table->string('Tick_HwId')->nullable()->default(null);
      $table->integer('Tick_BatchId')->nullable()->default(null);
      $table->integer('Tick_Count')->nullable()->default(null);
      $table->string('Tick_Serial')->nullable()->default(null);
      $table->integer('Tick_AccelRes')->nullable()->default(null);
      $table->decimal('Tick_VoltCalScale', 20, 10)->nullable()->default(null);
      $table->decimal('Tick_VoltCalOffset', 20, 10)->nullable()->default(null);
      $table->decimal('Tick_CurrCalScale', 20, 10)->nullable()->default(null);
      $table->decimal('Tick_CurrCalOffset', 20, 10)->nullable()->default(null);
      $table->integer('Tick_EepromRes')->nullable()->default(null);
      $table->integer('Tick_FlashRes')->nullable()->default(null);
      $table->integer('CalStatus')->nullable()->default(null);
      $table->integer('Tick_ProductId')->nullable()->default(null);
      $table->dateTime('EndTime')->nullable()->default(null);
      $table->integer('AAx')->nullable()->default(null);
      $table->integer('AAy')->nullable()->default(null);
      $table->integer('AAz')->nullable()->default(null);
      $table->integer('AOff')->nullable()->default(null);
      $table->string('FlashId')->nullable()->default(null);
      $table->string('Prev_Tick_Firmware')->nullable()->default(null);

      // indexes and foreign keys
      $table->primary('CalibrationResultId');
      $table->foreign('TickBox', 'cr_tickbox_fk')->references('ToolId')->on('Tool');
      $table->foreign('Scanner', 'cr_scanner_fk')->references('ToolId')->on('Tool');
      $table->foreign('Jig', 'cr_jig_fk')->references('ToolId')->on('Tool');
      $table->foreign('Supply', 'cr_supply_fk')->references('ToolId')->on('Tool');
      $table->foreign('Load', 'cr_load_fk')->references('ToolId')->on('Tool');
      $table->foreign('Voltmeter', 'cr_voltmeter_fk')->references('ToolId')->on('Tool');
      $table->foreign('Ammeter', 'cr_ammeter_fk')->references('ToolId')->on('Tool');
    });    
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('CalibrationResult');
    Schema::dropIfExists('Tool');
  }
}
