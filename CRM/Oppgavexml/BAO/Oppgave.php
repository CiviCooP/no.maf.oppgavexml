<?php
/**
 * BAO Oppgave for dealing with tax declaration contact records
 * 
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * 
 * Copyright (C) 2014 Coöperatieve CiviCooP U.A. <http://www.civicoop.org>
 * Licensed to MAF Norge <http://www.maf.no> and CiviCRM under the AGPL-3.0
 */
class CRM_Oppgavexml_BAO_Oppgave extends CRM_Oppgavexml_DAO_Oppgave {
  /**
   * Function to get values
   * 
   * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
   * @return array $result found rows with data
   * @access public
   * @static
   */
  public static function get_values($params) {
    $result = array();
    $oppgave = new CRM_Oppgavexml_BAO_Oppgave();
    if (!empty($params)) {
      $fields = self::fields();
      foreach ($params as $key => $value) {
        if (isset($fields[$key])) {
          $oppgave->$key = $value;
        }
      }
    }
    $oppgave->find();
    while ($oppgave->fetch()) {
      $row = array();
      self::storeValues($oppgave, $row);
      $result[$row['id']] = $row;
    }
    return $result;
  }
  /**
   * Function to add or update oppgave
   * 
   * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
   * @param array $params 
   * @return array $result
   * @access public
   * @static
   */
  public static function add($params) {
    $result = array();
    if (empty($params)) {
      throw new Exception('Params can not be empty when adding or updating a tax declaration contact record');
    }
    $oppgave = new CRM_Oppgavexml_BAO_Oppgave();
    $fields = self::fields();
    foreach ($params as $key => $value) {
      if (isset($fields[$key])) {
        $oppgave->$key = $value;
      }
    }
    $oppgave->save();
    self::storeValues($oppgave, $result);
    return $result;
  }
}