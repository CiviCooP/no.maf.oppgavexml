<?php
/**
 * Oppgave.Get API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_oppgave_get($params) {
  $return_values = CRM_Oppgavexml_BAO_Oppgave::get_values($params);
  return civicrm_api3_create_success($return_values, $params, 'Oppgave', 'Get');
}

