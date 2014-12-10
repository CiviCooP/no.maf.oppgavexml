<?php

/**
 * TaxDeclarationYear.Export API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRM/API+Architecture+Standards
 */
function _civicrm_api3_tax_declaration_year_export_spec(&$spec) {
  $spec['year']['api.required'] = 1;
}

/**
 * TaxDeclarationYear.Export API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_tax_declaration_year_export($params) {
  validate_params($params);
  $xml = new CRM_Oppgavexml_ExportYear();
  $xml->export_year($params['year']);
  $file_name = $xml->get_file_name();
  $return_values = array('File succesfully exported to '.$file_name);
  return civicrm_api3_create_success($return_values, $params, 'TaxDeclarationYear', 'Export');
}
/**
 * Function to validate incoming params
 * 
 * @param array $params
 * @throws API_Exception when no param year found
 * @throws API_Exception when param year is empty
 * @throws API_Exception when param year is not 4 digits long
 * @throws API_Exception when param year is not numeric
 */
function validate_params($params) {
  if (!array_key_exists('year', $params)) {
    throw new API_Exception('Year is a mandatory param but is not found in passed params');
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