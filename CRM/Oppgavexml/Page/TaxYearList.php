<?php
/**
 * Page TaxYearList to list all skatteinnberetninger tax declaration years
 * 
 * @author Erik Hommel <erik.hommel@civicoop.org>
 * 
 * Copyright (C) 2014 Coöperatieve CiviCooP U.A. <http://www.civicoop.org>
 * Licensed to MAF Norge <http://www.maf.no> under the  AGPL-3.0
 */
require_once 'CRM/Core/Page.php';

class CRM_Oppgavexml_Page_TaxYearList extends CRM_Core_Page {
  
  function run() {
    $this->set_page_configuration();
    $skatteinnberetninger = $this->get_skatteinnberetninger();
    $this->assign('skatteinnberetninger', $skatteinnberetninger);
    parent::run();
  }
  /**
   * Function to set actions for row
   * 
   * @param int $tax_year
   * @param int $status_id
   * @return array
   * @access protected
   */
  protected function set_row_actions($tax_year, $status_id) {
    $page_actions = array();
    $status_label = CRM_OppgaveXml_OptionGroup::get_status_option_label($status_id);
    switch ($status_label) {
      case 'New':
        $this->set_new_row_actions($tax_year, $page_actions);
        break;
      case 'Loaded':
        $this->set_loaded_modified_row_actions($tax_year, $page_actions);
        break;
      case 'Modified':
        $this->set_loaded_modified_row_actions($tax_year, $page_actions);
        break;
      case 'Exported':
        $this->set_exported_row_actions($tax_year, $page_actions);
        break;
    }
    return $page_actions;
  }
  /**
   * Function to set the actions for a tax year with status Exported
   * 
   * @access protected
   */
  protected function set_exported_row_actions($tax_year, &$page_actions) {
    $manage_url = CRM_Utils_System::url('civicrm/oppgavelist', 'year='.$tax_year);
    $page_actions[] = '<a class="action-item" title="Manage" href="'.$manage_url.'">Manage</a>';
    $reload_url = CRM_Utils_System::url('civicrm/loadtaxyear', 'option=reload&year='.$tax_year, true);
    $page_actions[] = '<a class="action-item" title="Reload" href="'.$reload_url.'">Reload</a>';
  }
  /**
   * Function to set the actions for a tax year with status Loaded or Modified
   * 
   * @access protected
   */
  protected function set_loaded_modified_row_actions($tax_year, &$page_actions) {
    $manage_url = CRM_Utils_System::url('civicrm/oppgavelist', 'year='.$tax_year);
    $page_actions[] = '<a class="action-item" title="Manage" href="'.$manage_url.'">Manage</a>';
    $reload_url = CRM_Utils_System::url('civicrm/loadtaxyear', 'option=reload&year='.$tax_year, true);
    $page_actions[] = '<a class="action-item" title="Reload" href="'.$reload_url.'">Reload</a>';
    $export_rul = CRM_Utils_System::url('civicrm/exporttaxyear', 'year='.$tax_year, true);
    $page_actions[] = '<a class="action-item" title="Export" href="'.$export_rul.'">Export</a>';
  }
  /**
   * Function to set the actions for a tax year with status New
   * 
   * @access protected
   */
  protected function set_new_row_actions($tax_year, &$page_actions) {
    $load_url = CRM_Utils_System::url('civicrm/loadtaxyear');
    $page_actions[] = '<a class="action-item" title="Load" href="'.$load_url.'">Load</a>';
    $delete_url = CRM_Utils_System::url('civicrm/skatteinnberetninger', 'action=delete&year='.$tax_year, true);
    $page_actions[] = '<a class="action-item" title="Delete" href="'.$delete_url.'">Delete</a>';
  }
  /**
   * Function to set the page configuration initially
   * 
   * @access protected
   */
  protected function set_page_configuration() {
    CRM_Utils_System::setTitle(ts('Manage Tax Declaration Years'));    
    $this->assign('add_url', CRM_Utils_System::url('civicrm/skatteinnberetninger', 'action=add', true));
  }
  /**
   * Function to get skatteinnberetninger
   * 
   * @return array $skatteinnberetninger
   * @access protected
   */
  protected function get_skatteinnberetninger() {
    $skatteinnberetninger = CRM_Oppgavexml_BAO_Skatteinnberetninger::get_values(array());
    foreach ($skatteinnberetninger as $key => $values) {
      $skatteinnberetninger[$key]['status'] = CRM_Oppgavexml_OptionGroup::get_status_option_label($values['status_id']);
      $skatteinnberetninger[$key]['actions'] = $this->set_row_actions($key, $values['status_id']);
    }
    return $skatteinnberetninger;
  }
}
