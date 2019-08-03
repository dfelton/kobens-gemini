
DROP TABLE IF EXISTS `gemini_trade_repeater`;
CREATE TABLE `gemini_trade_repeater` (
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Gemini Trade Repeater ID',
    `is_enabled` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Is Enabled',
    -- BUY_READY | BUY_SENT | BUY_PLACED | BUY_FILLED | SELL_SENT | SELL_PLACED | SELL_FILLED
    `status` VARCHAR(25) NOT NULL COMMENT 'Status',
    `symbol` VARCHAR(12) NOT NULL COMMENT 'Symbol',
    `buy_client_order_id` VARCHAR(30) NULL COMMENT 'Buy Client Order ID',
    `buy_order_id` VARCHAR(30) NULL COMMENT 'Buy Order Id',
    `buy_amount` VARCHAR(50) NOT NULL COMMENT 'Buy Amount',
    `buy_price` VARCHAR (50) NOT NULL COMMENT 'Buy Price',
    `sell_client_order_id` VARCHAR(30) NULL COMMENT 'Sell Client Order ID',
    `sell_order_id` VARCHAR(30) NULL COMMENT 'Sell Order ID',
    `sell_amount` VARCHAR (50) NOT NULL COMMENT 'Sell Amount',
    `sell_price` VARCHAR (50) NOT NULL COMMENT 'Sell Price',
    `note` TEXT NULL COMMENT 'Note',
    `meta` TEXT NULL COMMENT 'Meta Data',
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `IDX_STATUS` (`status`),
    KEY `IDX_IS_ENABLED` (`is_enabled`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Gemini Trade Repeater';


DROP TABLE IF EXISTS `gemini_trade_repeater_archive`;
CREATE TABLE `gemini_trade_repeater_archive` (
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Gemini Trade Repeater Archive ID',
    `symbol` VARCHAR(12) NOT NULL COMMENT 'Symbol',
    `buy_client_order_id` VARCHAR(30) NULL COMMENT 'Buy Client Order ID',
    `buy_order_id` VARCHAR(30) NULL COMMENT 'Buy Order Id',
    `buy_amount` VARCHAR(50) NOT NULL COMMENT 'Buy Amount',
    `buy_price` VARCHAR (50) NOT NULL COMMENT 'Buy Price',
    `sell_client_order_id` VARCHAR(30) NULL COMMENT 'Sell Client Order ID',
    `sell_order_id` VARCHAR(30) NULL COMMENT 'Sell Order ID',
    `sell_amount` VARCHAR (50) NOT NULL COMMENT 'Sell Amount',
    `sell_price` VARCHAR (50) NOT NULL COMMENT 'Sell Price',
    `meta` TEXT NULL COMMENT 'Meta Data',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Gemini Trade Repeater Archive';


DROP TABLE IF EXISTS `gemini_trade_repeater_profit_summary`;
CREATE TABLE `gemini_trade_repeater_profit_summary` (
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Summary Id',
    `symbol` VARCHAR(12) NOT NULL COMMENT 'Symbol',
    `total_sell_fills` INT(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Total Sell Fills',
    `profit_base` VARCHAR(50) NOT NULL COMMENT 'Profit (Base Currency)',
    `profit_quote` VARCHAR(50) NOT NULL COMMENT 'Profit (Quote Currency)',
    `summary_date` TIMESTAMP NOT NULL COMMENT 'Summary Date',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Gemini Trade Repeater Profit Summary';


DROP TABLE IF EXISTS `gemini_trade_history`;
CREATE TABLE `gemini_trade_history` (
    `transaction_id` INT(10) UNSIGNED NOT NULL COMMENT 'Transaction ID',
    `symbol` VARCHAR(12) NOT NULL COMMENT 'Symbol',
    `price` VARCHAR(50) NOT NULL COMMENT 'Price',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `timestampms` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Timestamp Milliseconds',
    `side` VARCHAR(4) NOT NULL COMMENT 'Side',
    `fee_currency` VARCHAR(10) NOT NULL COMMENT 'Fee Currency',
    `fee_amount` VARCHAR(50) NOT NULL COMMENT 'Fee Amount',
    `order_id` INT(10) UNSIGNED NOT NULL COMMENT 'Order Id',
    `client_order_id` VARCHAR(100) DEFAULT NULL,
    `created_at` TIMESTAMP(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) COMMENT 'Created At',
    PRIMARY KEY (`transaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Gemini Trade History';


DROP TABLE IF EXISTS `gemini_trade_history_pageLimitError`;
CREATE TABLE `gemini_trade_history_pageLimitError` (
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Transaction ID',
    `symbol` VARCHAR(12) NOT NULL COMMENT 'Symbol',
    `timestampms` BIGINT(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Gemini Page Limit Errors for Transaction History';


DROP TABLE IF EXISTS `gemini_taxes_cost_basis`;
CREATE TABLE `gemini_taxes_cost_basis` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
	`transaction_id` INT(10) UNSIGNED NOT NULL COMMENT 'Transaction ID',
	`cost_basis_per_subunit` VARCHAR(50) NOT NULL COMMENT 'Cost Basis Per Subunit',
	`is_fully_sold` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Is Fully Sold',
    `created_at` TIMESTAMP(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) COMMENT 'Created At',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Gemini Taxes - Cost Basis';

DROP TABLE IF EXISTS `gemini_taxes_form8949`;
CREATE TABLE `gemini_taxes_form8949` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
	`transaction_id` INT(10) UNSIGNED NOT NULL COMMENT 'Transaction ID',
	`description` VARCHAR(50) NOT NULL COMMENT 'Description of Property',
	`date_acquired` TIMESTAMP NOT NULL COMMENT 'Date Acquired',
	`date_sold` TIMESTAMP NOT NULL COMMENT 'Date Sold',
	`proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
	`cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis'
	`gain_or_loss` VARCHAR(50) NOT NULL COMMENT 'Gain or (loss)',
    `created_at` TIMESTAMP(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) COMMENT 'Created At',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Gemini Taxes - Form 8949';
