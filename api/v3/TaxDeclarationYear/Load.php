<?php

/**
 * TaxDeclarationYear.Load API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRM/API+Architecture+Standards
 */
function _civicrm_api3_tax_declaration_year_load_spec(&$spec) {
  $spec['year']['api_required'] = 1;
  $spec['reload']['api_required'] = 1;
}
/**
 * TaxDeclarationYear.Load API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 */
function civicrm_api3_tax_declaration_year_load($params) {
  validate_params($params);
  $return_values = array();
  remove_year_records($params);
  $dao = get_relevant_contacts($params['year']);
  while ($dao->fetch()) {
    create_contact_oppgave($params, $dao);
  }
  create_skatteinnberetninger($params);  
  return civicrm_api3_create_success($return_values, $params, 'TaxDeclarationYear', 'Load');
}
/**
 * Function to remove existing records for the year (only if reload = 0)
 * 
 * @param array $params
 */
function remove_year_records($params) {
  if ($params['reload'] == 0) {
    $delete_query = 'DELETE FROM civicrm_oppgave WHERE oppgave_year = %1';
    $delete_params = array(1 => array($params['year'], 'Positive'));
    CRM_Core_DAO::executeQuery($delete_query, $delete_params);
  }
}
/**
 * Function to retrieve all required contacts with their total deductible amount
 * 
 * @param int $year
 * @return obj $dao
 */
function get_relevant_contacts($year) {
  $start_date = $year.'-01-01 00:00:00';
  $end_date = $year.'-12-31 23:59:59';
  $query = 'SELECT contact_id, 
    SUM(total_amount - non_deductible_amount) AS deductible_amount 
    FROM civicrm_contribution 
    WHERE (receive_date BETWEEN %1 AND %2) 
    AND contribution_status_id = %3 
    GROUP BY contact_id';
  $params = array(
    1 => array($start_date, 'String'),
    2 => array($end_date, 'String'),
    3 => array(1, 'Positive'));
  $dao = CRM_Core_DAO::executeQuery($query, $params);
  return $dao;
}
/**
 * Function to create contact oppgave if params valid
 * 
 * @param array $params
 * @param obj $dao
 */
function create_contact_oppgave($params, $dao) {
  $create_contact = TRUE;
  if ($params['reload'] == 1) {
    if (contact_exists_in_oppgave($dao->contact_id, $params['year']) == TRUE) {
      $create_contact = FALSE;
    }
  }
  if ($create_contact == TRUE) {
    $create_params = set_oppgave_params($params['year'], $dao);
    if (!empty($create_params)) {
      $query = 'INSERT INTO civicrm_oppgave (oppgave_year, contact_id, donor_type, '
        . 'donor_name, donor_number, deductible_amount, loaded_date) '
        . 'VALUES(%1, %2, %3, %4, %5, %6, %7)';
      CRM_Core_DAO::executeQuery($query, $create_params);
    }
  }  
}
/**
 * Function to check if contact already exists in file for year
 * 
 * @param int $contact_id
 * @param int $oppgave_year
 * @return boolean
 */
function contact_exists_in_oppgave($contact_id, $oppgave_year) {
  $contact_exists = FALSE;
  $query = 'SELECT COUNT(*) AS donor_count FROM civicrm_oppgave WHERE contact_id = %1 '
    . 'AND oppgave_year = %2';
  $params = array(
    1 => array($contact_id, 'Positive'),
    2 => array($oppgave_year, 'Positive'));
  $dao = CRM_Core_DAO::executeQuery($query, $params);
  if ($dao->fetch()) {
    if ($dao->donor_count > 0) {
      $contact_exists = TRUE;
    }
  }
  return $contact_exists;
}
/**
 * Function to create params list for create oppgave
 * 
 * @param int $year
 * @param obj $dao
 * @return array $params
 */
function set_oppgave_params($year, $dao) {
  $params = array();
  $oppgave_config = CRM_Oppgavexml_Config::singleton();
  $min_amount = $oppgave_config->get_min_deductible_amount();
  $max_amount = $oppgave_config->get_max_deductible_amount();
  if ($dao->deductible_amount >= $min_amount) {
    if ($dao->deductible_amount > $max_amount) {
      $dao->deductible_amount = $max_amount;
    }
    $donor_data = get_donor_data($dao->contact_id);
    if (!empty($donor_data)) {
      $params = array(
        1 => array($year, 'Positive'),
        2 => array($dao->contact_id, 'Positive'),
        3 => array($donor_data['type'], 'String'),
        4 => array($donor_data['name'], 'String'),
        5 => array($donor_data['number'], 'String'),
        6 => array($dao->deductible_amount, 'Money'),
        7 => array(date('Ymd'), 'Date'));
    }
  }
  return $params;
}
/**
 * Function to get the donor data
 * @param int $contact_id
 * @return array $donor_data
 */
function get_donor_data($contact_id) {
  $params = array('id' => $contact_id);
  try {
    $contact_data = civicrm_api3('Contact', 'Getsingle', $params);
    $donor_data = pull_donor_data($contact_data);
    
    if (empty($donor_data['number'])) {
      $donor_data = array();
    }
  } catch (CiviCRM_API3_Exception $ex) {
    $donor_data = array();
  }
  return $donor_data;
}
/**
 * Function to pull donor data from api contact getsingle result array
 * 
 * @param array $contact_data
 * @return array $donor_data
 */
function pull_donor_data($contact_data) {
  $donor_data = array();
  $donor_data['name'] = $contact_data['display_name'];
  $custom_data = get_custom_data($contact_data['id']);
  switch ($contact_data['contact_type']) {
    case 'Individual':
      $donor_data['type'] = 'Person';
      $donor_data['number'] = $custom_data['personsnummer'];
      break;
    case 'Household':
      $donor_data['type'] = 'Husholdning';
      $donor_data['number'] = $custom_data['personsnummer'];
    break;
    case 'Organization':
      $donor_data['type'] = 'Organisasjon';
      $donor_data['number'] = $custom_data['organisasjonsnummer'];
    break;
  }
  return $donor_data;
}
/**
 * Function to get custom data for person/organisasjonsnummer
 * 
 * @param int $contact_id
 * @return array $custom_data
 */
function get_custom_data($contact_id) {
  $oppgave_config = CRM_Oppgavexml_Config::singleton();
  $table_name = $oppgave_config->get_contact_custom_group_table();
  $column_person = $oppgave_config->get_personsnummer_custom_field_column();
  $column_org = $oppgave_config->get_organisasjonsnummer_custom_field_column();
  $query = ' SELECT * FROM '.$table_name.' WHERE entity_id = %1';
  $params = array(1 => array($contact_id, 'Positive'));
  $dao = CRM_Core_DAO::executeQuery($query, $params);
  if ($dao->fetch()) {
    $custom_data['personsnummer'] = $dao->$column_person;
    $custom_data['organisasjonsnummer'] = $dao->$column_org;
  } else {
    $custom_data['personsnummer'] = '';
    $custom_data['organisasjonsnummer'] = '';    
  }
  return $custom_data;
}
/**
 * Function to update the status of the tax declaration year
 * 
 * @param array $params
 */
function create_skatteinnberetninger($params) {
  if ($params['reload'] == 0) {
    $query = 'REPLACE INTO civicrm_skatteinnberetninger (year, status_id) VALUES(%1, %2)';
  } else {
    $query = 'UPDATE civicrm_skatteinnberetninger SET status_id = %2 WHERE year = %1';
  }
  $query_params = array(
    1 => array($params['year'], 'Positive'),
    2 => array(2, 'Positive'));
  CRM_Core_DAO::executeQuery($query, $query_params);
}
/**
 * Function to validate incoming params
 * 
 * @param array $params
 * @throws API_Exception when no param year found
 * @throws API_Exception when no param reload found
 * @throws API_Exception when param reload not 0 or 1
 * @throws API_Exception when param year is empty
 * @throws API_Exception when param year is not 4 digits long
 * @throws API_Exception when param year is not numeric
 */
function validate_params($params) {
  if (!array_key_exists('year', $params)) {
    throw new API_Exception('Year is a mandatory param but is not found in passed params');
  }
  if (!array_key_exists('reload', $params)) {
    throw new API_Exception('Reload is a mandatory param but is not found in passed params');
  }
  if ($params['reload'] != 1 && $params['reload'] != 0) {
    throw new API_Exception('Reload param can only be 0 or 1');
  }
  if (empty($params['year'])) {
    throw new API_Exception('Param year can not be empty');
  }
  if (strlen($params['year']) != 4) {
    throw new API_Exception('Year has to have 4 digits');
  }
  if (!is_numeric($params['year'])) {
    throw new API_Exception('Year has to be numeric');
  }
}
