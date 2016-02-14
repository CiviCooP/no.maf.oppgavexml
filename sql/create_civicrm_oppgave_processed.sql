CREATE TABLE IF NOT EXISTS `civicrm_oppgave_processed` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `oppgave_year` CHAR(4) NULL,
  `contact_id` INT NULL,
  `checked_date` DATE NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `year_contact INDEX` (`oppgave_year` ASC, `contact_id` ASC))
  ENGINE = InnoDB;
