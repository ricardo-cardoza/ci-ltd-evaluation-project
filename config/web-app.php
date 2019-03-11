<?php

return [
  'validation_rules' => [
    'calibration_result_db_row' => [
      'CalibrationResultId' => ['required', 'integer'],     
      'RunTime' => ['date', 'nullable'],
      'Voltmeter' => ['integer', 'nullable'],
      'Ammeter' => ['integer', 'nullable'],
      'Supply' => ['integer', 'nullable'],
      'Load' => ['integer', 'nullable'],
      'TickBox' => ['integer', 'nullable'],
      'Scanner' => ['integer', 'nullable'],
      'Jig' => ['integer', 'nullable'],
      'Tick_BatchId' => ['integer', 'nullable'],
      'Tick_Count' => ['integer', 'nullable'],
      'Tick_AccelRes' => ['integer', 'nullable'],
      'Tick_VoltCalScale' => ['numeric', 'nullable'],
      'Tick_VoltCalOffset' => ['numeric', 'nullable'],
      'Tick_CurrCalScale' => ['numeric', 'nullable'],
      'Tick_CurrCalOffset' => ['numeric', 'nullable'],
      'Tick_EepromRes' => ['integer', 'nullable'],
      'Tick_FlashRes' => ['integer', 'nullable'],
      'CalStatus' => ['integer', 'nullable'],
      'Tick_ProductId' => ['integer', 'nullable'],
      'EndTime' => ['date', 'nullable'],
      'AAx' => ['integer', 'nullable'],
      'AAy' => ['integer', 'nullable'],
      'AAz' => ['integer', 'nullable'],
      'AOff' => ['integer', 'nullable'],
    ]
  ],
  'tool_db_row' => [
    'ToolId' => ['required', 'integer'],
  ],
];