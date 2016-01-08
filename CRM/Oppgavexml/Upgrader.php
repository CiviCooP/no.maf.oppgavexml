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
    $this->executeSqlFile('sql/create_civicrm_oppgave_processed.sql');
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
  /**
   * Upgrade 1002 - add column last referanse to skatteinnberetninger
   */
  public function upgrade_1002() {
    $this->ctx->log->info('Applying update 1002 (add last_referanse column to civicrm_skatteinnberetninger)');
    if (CRM_Core_DAO::checkTableExists('civicrm_skatteinnberetninger')) {
      if (!CRM_Core_DAO::checkFieldExists('civicrm_skatteinnberetninger', 'last_referanse')) {
        CRM_Core_DAO::executeQuery("ALTER TABLE civicrm_skatteinnberetninger ADD last_referanse INT(11)");
      }
    }
    return TRUE;
  }
  /**
   * Upgrade 1003 - add table civicrm_oppgave_processed
   */
  public function upgrade_1003() {
    $this->ctx->log->info('Applying update 1003 (add civicrm_oppgave_processed table)');
    $this->executeSqlFile('sql/create_civicrm_oppgave_processed.sql');
    return TRUE;
  }
}