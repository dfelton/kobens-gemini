
-- TODO: Really need an install command that supports our other modules too

CREATE TABLE IF NOT EXISTS `trade_repeater` (
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

CREATE TABLE IF NOT EXISTS `trade_repeater_archive` (
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

CREATE TABLE IF NOT EXISTS `trade_repeater_profit_summary` (
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Summary Id',
    `symbol` VARCHAR(12) NOT NULL COMMENT 'Symbol',
    `total_sell_fills` INT(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Total Sell Fills',
    `profit_base` VARCHAR(50) NOT NULL COMMENT 'Profit (Base Currency)',
    `profit_quote` VARCHAR(50) NOT NULL COMMENT 'Profit (Quote Currency)',
    `summary_date` TIMESTAMP NOT NULL COMMENT 'Summary Date',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Trade Repeater Profit Summary';

CREATE TABLE IF NOT EXISTS `trade_history_pageLimitError` (
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Transaction ID',
    `symbol` VARCHAR(12) NOT NULL COMMENT 'Symbol',
    `timestampms` BIGINT(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Gemini Page Limit Errors for Transaction History';

CREATE TABLE IF NOT EXISTS `repeater_stats_daily_profit` (
  `id` int(13) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `symbol` varchar(12) NOT NULL COMMENT 'Symbol',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `amount_notional` varchar(50) NOT NULL COMMENT 'Amount',
  `date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Repeater Stats Daily Profit';

