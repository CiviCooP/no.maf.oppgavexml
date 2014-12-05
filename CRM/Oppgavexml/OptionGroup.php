<?php
/**
 * Class to create option groups for tax declaration year status
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 */
class CRM_OppgaveXml_OptionGroup {
  /**
   * Function to create option group and values for tax declaration file status
   * if not exists already
   * 
   * @throws Exception if option group can not be created
   * @access public
   * @static
   */
  public static function create_status_option_group() {
    $option_group_name = 'tax_declaration_year_status';
    if (self::status_option_group_exists($option_group_name) == FALSE) {
      $params = array(
        'name' => $option_group_name,
        'title' => 'Tax Declaration Year Status',
        'is_active' => 1,
        'is_reserved' => 1
      );
      try {
        $option_group = civicrm_api3('OptionGroup', 'Create', $params);
      } catch (CiviCRM_API3_Exception $ex) {
        throw new Exception('Could not create option group tax_declaration_year_status, '
          . 'error from API OptionGroup Create: '.$ex->getMessage());      
      }
      self::create_status_option_values($option_group['id']);
    }
  }
  /**
   * Function to check if there is an option group with a specific name
   * 
   * @param string $option_group_name
   * @return boolean
   * @access public
   * @static
   */
  public static function status_option_group_exists($option_group_name) {
    $params = array('name' => $option_group_name);
    $count_option_group = civicrm_api3('OptionGroup', 'Getcount', $params);
    if ($count_option_group > 0) {
      return TRUE;
    } else {
      return FALSE;
    }
  }
  /**
   * Function to create option values for tax declaration year status
   * 
   * @param int $option_group_id
   * @throws Exception if option value can not be created
   * @access protected
   * @static
   */
  protected static function _oppgavexml_create_status_option_values($option_group_id) {
    $status_labels = array('New', 'Loaded', 'Exported', 'Reloaded');
    $value = 1;
    foreach ($status_labels as $status_label) {
      $params = array(
        'name' => $status_label,
        'label' => $status_label,
        'value' => $value,
        'is_active' => 1,
        'weight' => $value,
        'option_group_id' => $option_group_id);
      try {
        civicrm_api3('OptionValue', 'Create', $params);
        $value++;
      } catch (CiviCRM_API3_Exception $ex) {
        throw new Exception('Could not create option value '.$status_label.' in option group '
          .$option_group_id.', error from API OptionValue Create: '.$ex->getMessage());
      }
    }
  }
}
