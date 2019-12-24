--
-- TODO: Implement a install/upgrade approach better than this
--



DROP TABLE IF EXISTS `trade_repeater`;
CREATE TABLE `trade_repeater` (
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Trade Repeater ID',
    `is_enabled` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Is Enabled',
    `is_error` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Is Error',
    -- BUY_READY | BUY_SENT | BUY_PLACED | BUY_FILLED | SELL_SENT | SELL_PLACED | SELL_FILLED
    `status` VARCHAR(25) NOT NULL COMMENT 'Status',
    `symbol` VARCHAR(12) NOT NULL COMMENT 'Symbol',
    `buy_client_order_id` VARCHAR(100) NULL COMMENT 'Buy Client Order ID',
    `buy_order_id` VARCHAR(30) NULL COMMENT 'Buy Order Id',
    `buy_amount` VARCHAR(50) NOT NULL COMMENT 'Buy Amount',
    `buy_price` VARCHAR (50) NOT NULL COMMENT 'Buy Price',
    `sell_client_order_id` VARCHAR(100) NULL COMMENT 'Sell Client Order ID',
    `sell_order_id` VARCHAR(30) NULL COMMENT 'Sell Order ID',
    `sell_amount` VARCHAR (50) NOT NULL COMMENT 'Sell Amount',
    `sell_price` VARCHAR (50) NOT NULL COMMENT 'Sell Price',
    `note` TEXT NULL COMMENT 'Note',
    `meta` TEXT NULL COMMENT 'Meta Data',
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `IDX_STATUS` (`status`),
    KEY `IDX_IS_ENABLED` (`is_enabled`),
    KEY `IDX_IS_ERROR` (`is_error`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Trade Repeater';


DROP TABLE IF EXISTS `trade_repeater_archive`;
CREATE TABLE `trade_repeater_archive` (
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Trade Repeater Archive ID',
    `symbol` VARCHAR(12) NOT NULL COMMENT 'Symbol',
    `buy_client_order_id` VARCHAR(100) NULL COMMENT 'Buy Client Order ID',
    `buy_order_id` VARCHAR(30) NULL COMMENT 'Buy Order Id',
    `buy_amount` VARCHAR(50) NOT NULL COMMENT 'Buy Amount',
    `buy_price` VARCHAR (50) NOT NULL COMMENT 'Buy Price',
    `sell_client_order_id` VARCHAR(100) NULL COMMENT 'Sell Client Order ID',
    `sell_order_id` VARCHAR(30) NULL COMMENT 'Sell Order ID',
    `sell_amount` VARCHAR (50) NOT NULL COMMENT 'Sell Amount',
    `sell_price` VARCHAR (50) NOT NULL COMMENT 'Sell Price',
    `meta` TEXT NULL COMMENT 'Meta Data',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Trade Repeater Archive';


DROP TABLE IF EXISTS `trade_repeater_profit_summary`;
CREATE TABLE `trade_repeater_profit_summary` (
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Summary Id',
    `symbol` VARCHAR(12) NOT NULL COMMENT 'Symbol',
    `total_sell_fills` INT(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Total Sell Fills',
    `profit_base` VARCHAR(50) NOT NULL COMMENT 'Profit (Base Currency)',
    `profit_quote` VARCHAR(50) NOT NULL COMMENT 'Profit (Quote Currency)',
    `summary_date` TIMESTAMP NOT NULL COMMENT 'Summary Date',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Trade Repeater Profit Summary';


DROP TABLE IF EXISTS `trade_history_btcusd`;
CREATE TABLE `trade_history_btcusd` (
    `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `price` VARCHAR(50) NOT NULL COMMENT 'Price',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `timestampms` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Timestamp Milliseconds',
    `type` VARCHAR(4) NOT NULL COMMENT 'Type',
    `aggressor` TINYINT(1) NOT NULL COMMENT 'Aggressor',
    `fee_currency` VARCHAR(10) NOT NULL COMMENT 'Fee Currency',
    `fee_amount` VARCHAR(50) NOT NULL COMMENT 'Fee Amount',
    `order_id` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Order Id',
    `client_order_id` VARCHAR(100) DEFAULT NULL,
    `trade_date` TIMESTAMP(3) NOT NULL COMMENT 'Trade Date',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History BTCUSD';


DROP TABLE IF EXISTS `trade_history_pageLimitError`;
CREATE TABLE `trade_history_pageLimitError` (
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Transaction ID',
    `symbol` VARCHAR(12) NOT NULL COMMENT 'Symbol',
    `timestampms` BIGINT(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Gemini Page Limit Errors for Transaction History';


DROP TABLE IF EXISTS `taxes_btcusd_buy_log`;
CREATE TABLE `taxes_btcusd_buy_log` (
	`tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
	`amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - BTCUSD Buy Log';


DROP TABLE IF EXISTS `taxes_btcusd_sell_log`;
CREATE TABLE `taxes_btcusd_sell_log` (
	`sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
	`buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
	`amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
	`cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
	`proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
	`capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - BTCUSD Sell Log';


DROP TABLE IF EXISTS `taxes_form8949`;
CREATE TABLE `taxes_form8949` (
	`id` BIGINT(13) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
	`tid` BIGINT(10) UNSIGNED NOT NULL COMMENT 'Transaction ID',
	`description` VARCHAR(50) NOT NULL COMMENT 'Description of Property',
	`date_acquired` TIMESTAMP NOT NULL COMMENT 'Date Acquired',
	`date_sold` TIMESTAMP NOT NULL COMMENT 'Date Sold',
	`proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
	`cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
	`gain_or_loss` VARCHAR(50) NOT NULL COMMENT 'Gain or (loss)',
    `created_at` TIMESTAMP(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) COMMENT 'Created At',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - Form 8949';


--
-- TODO: This (schema) originates from kobens-core and doesn't belong in the kobens-gemini
--
DROP TABLE IF EXISTS `throttler`;
CREATE TABLE `throttler` (
     `id` VARCHAR(255) NOT NULL COMMENT 'Key',
     `max` INT(10) UNSIGNED NOT NULL COMMENT 'Limit',
     `count` INT(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Count',
     `time` INT(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Time',
     PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Throttler';

INSERT INTO `throttler` (`id`,`max`)
VALUES
  ('api.sandbox.gemini.com::public',  2),
  ('api.sandbox.gemini.com::private', 10),
  ('api.gemini.com::public',  2),
  ('api.gemini.com::private', 10)
;


