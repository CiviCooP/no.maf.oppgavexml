<?php
// This file declares a managed database record of type "Job".
// The record will be automatically inserted, updated, or deleted from the
// database as appropriate. For more details, see "hook_civicrm_managed" at:
// http://wiki.civicrm.org/confluence/display/CRMDOC42/Hook+Reference
return array (
  0 => 
  array (
    'name' => 'Cron:TaxDeclarationYear.Load',
    'entity' => 'Job',
    'params' => 
    array (
      'version' => 3,
      'name' => 'Load Skatteinnberetninger',
      'description' => 'Initial load of donor into skatteinnberetninger',
      'run_frequency' => 'Daily',
      'api_entity' => 'TaxDeclarationYear',
      'api_action' => 'Load',
      'parameters' => 'year = [2013, year to be reported on] required / reload = [0 first load for year or 1 add contacts to existing year] required',
      'is_active' => 0
    ),
  ),
);