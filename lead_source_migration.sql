-- Lead source master and orders mapping

CREATE TABLE IF NOT EXISTS `lead_source` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `lead_source` VARCHAR(150) NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_lead_source_name` (`lead_source`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `orders`
  ADD COLUMN `lead_source_id` INT NULL AFTER `competition_involved`,
  ADD INDEX `idx_orders_lead_source_id` (`lead_source_id`);

ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_lead_source`
  FOREIGN KEY (`lead_source_id`) REFERENCES `lead_source`(`id`)
  ON UPDATE CASCADE
  ON DELETE SET NULL;
