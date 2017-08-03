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
      'name' => 'Skatteinnberetninger Load',
      'description' => 'Load or add donor data from contributions into Skatteinnberetninger year',
      'run_frequency' => 'Daily',
      'api_entity' => 'TaxDeclarationYear',
      'api_action' => 'Load',
      'parameters' => 'year = [2013, year to be reported on] required',
      'is_active' => 0
    ),
  ),
);