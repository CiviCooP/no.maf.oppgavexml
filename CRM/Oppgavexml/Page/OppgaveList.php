<?php
/**
 * Page Oppgavelist to list all donor oppgaves for a year or a contact
 * 
 * @author Erik Hommel <erik.hommel@civicoop.org>
 * 
 * Copyright (C) 2014 Coöperatieve CiviCooP U.A. <http://www.civicoop.org>
 * Licensed to MAF Norge <http://www.maf.no> under the  AGPL-3.0
 */
require_once 'CRM/Core/Page.php';

class CRM_Oppgavexml_Page_OppgaveList extends CRM_Core_Page {
  
  protected $_display_context = null;
  protected $_context_contact_id = null;
  protected $_context_tax_year = null;
  /**
   * Standard run function created when generating page with Civix
   * 
   * @access public
   */
  function run() {
    $this->set_page_configuration();
    $display_oppgaves = $this->get_oppgaves();
    $this->assign('oppgaves', $display_oppgaves);
    parent::run();
  }
  /**
   * Function to get the data from civicrm_oppgave
   * 
   * @return array $oppgaves
   * @access protected
   */
  protected function get_oppgaves() {
    if ($this->_display_context == 'contact') {
      $params = $this->set_contact_params();
    } else {
      $params = $this->set_year_params();
    }
    $oppgaves = CRM_Oppgavexml_BAO_Oppgave::get_values($params);
    foreach ($oppgaves as $oppgave_id => $oppgave) {
      $oppgaves[$oppgave_id]['actions'] = $this->set_row_actions($oppgave_id);
    }
    return $oppgaves;
  }
  /**
   * Function to set the row action urls and links for each row
   * 
   * @param int $oppgave_id
   * @return array $page_actions
   * @access protected
   */
  protected function set_row_actions($oppgave_id) {
    $page_actions = array();
    $view_url = CRM_Utils_System::url('civicrm/oppgave', 'action=view&oid='.$oppgave_id);
    $page_actions[] = '<a class="action-item" title="View" href="'.$view_url.'">View</a>';
    $edit_url = CRM_Utils_System::url('civicrm/oppgave', 'action=edit&oid='.$oppgave_id);
    $page_actions[] = '<a class="action-item" title="Edit" href="'.$edit_url.'">Edit</a>';
    return $page_actions;
  }
  /**
   * Function to set the params if the context is contact (tab on Contact Summary)
   * 
   * @return array $params
   * @access protected
   */
  protected function set_contact_params() {
    $params = array('year' => $this->_context_contact_id);
    return $params;
  }
  /**
   * Function to set the params if the context is year (Manage from Skatteinnberetninger list)
   * 
   * @return array $params
   * @access protected
   */  
  protected function set_year_params() {
    $params = array('year' => $this->_context_tax_year);
    return $params;
  }
  /**
   * Function to set the page configuration
   * 
   * @access protected
   */
  protected function set_page_configuration() {
    CRM_Utils_System::setTitle(ts('Donoroppgave'));    
    $this->assign('add_url', CRM_Utils_System::url('civicrm/oppgave', 'action=add&cid='.$this->_context_contact_id, true));  
    $snippet = CRM_Utils_Request::retrieve('snippet', 'Positive');
    if (!empty($snippet)) {
      $this->_display_context = 'contact';
      $this->_context_contact_id = CRM_Utils_Request::retrieve('cid', 'Positive');
    } else {
      $this->_display_context = 'year';
      $this->_context_tax_year = CRM_Utils_Request::retrieve('year', 'Positive');
    }
  }
}
