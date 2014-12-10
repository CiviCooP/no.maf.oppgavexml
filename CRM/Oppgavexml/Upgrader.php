<?php

/**
 * Collection of upgrade steps
 */
class CRM_Oppgavexml_Upgrader extends CRM_Oppgavexml_Upgrader_Base {
  /**
   * Function to create required files (if not exists) when installed
   */
  public function install() {
    $this->executeSqlFile('sql/create_civicrm_skatteinnberetninger.sql');
    $this->executeSqlFile('sql/create_civicrm_oppgave.sql');
  }
}