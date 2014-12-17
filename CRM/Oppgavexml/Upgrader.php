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
  /**
   * Upgrade 1001 - modify column deductible amount to integer
   */
  public function upgrade_1001() {
    $this->ctx->log->info('Applying update 1001 (modify deductible_amount in civicrm_oppgave to INT(11))');
    if (CRM_Core_DAO::checkTableExists('civicrm_oppgave')) {
      if (CRM_Core_DAO::checkFieldExists('civicrm_oppgave', 'deductible_amount')) {
        CRM_Core_DAO::executeQuery("ALTER TABLE civicrm_oppgave MODIFY deductible_amount INT(11)");
      }
    }
    return TRUE;
  }
}