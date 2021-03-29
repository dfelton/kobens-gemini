
-- TODO: Really need an install command that supports our other modules too

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


DROP TABLE IF EXISTS `trade_history_pageLimitError`;
CREATE TABLE `trade_history_pageLimitError` (
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Transaction ID',
    `symbol` VARCHAR(12) NOT NULL COMMENT 'Symbol',
    `timestampms` BIGINT(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Gemini Page Limit Errors for Transaction History';


DROP TABLE IF EXISTS `taxes_btcusd_buy_log`;
CREATE TABLE IF NOT EXISTS `taxes_btcusd_buy_log` (
	`tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
	`amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - BTCUSD Buy Log';


DROP TABLE IF EXISTS `taxes_ethusd_buy_log`;
CREATE TABLE IF NOT EXISTS `taxes_ethusd_buy_log` (
        `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
        `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ETHUSD Buy Log';


DROP TABLE IF EXISTS `taxes_zecusd_buy_log`;
CREATE TABLE IF NOT EXISTS `taxes_zecusd_buy_log` (
        `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
        `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ZECUSD Buy Log';


-- DROP TABLE IF EXISTS `taxes_btcusd_sell_log`;
CREATE TABLE IF NOT EXISTS `taxes_btcusd_sell_log` (
	`sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
	`buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
	`amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
	`cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
	`proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
	`capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - BTCUSD Sell Log';


DROP TABLE IF EXISTS `taxes_ethusd_sell_log`;
CREATE TABLE IF NOT EXISTS `taxes_ethusd_sell_log` (
        `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
        `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
        `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
        `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
        `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
        `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ETHUSD Sell Log';


DROP TABLE IF EXISTS `taxes_zecusd_sell_log`;
CREATE TABLE IF NOT EXISTS `taxes_zecusd_sell_log` (
        `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
        `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
        `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
        `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
        `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
        `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ZECUSD Sell Log';


-- DROP TABLE IF EXISTS `taxes_form8949`;
-- CREATE TABLE IF NOT EXISTS `taxes_form8949` (
--	`id` BIGINT(13) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
--	`tid` BIGINT(10) UNSIGNED NOT NULL COMMENT 'Transaction ID',
--	`description` VARCHAR(50) NOT NULL COMMENT 'Description of Property',
--	`date_acquired` TIMESTAMP NOT NULL COMMENT 'Date Acquired',
--	`date_sold` TIMESTAMP NOT NULL COMMENT 'Date Sold',
--	`proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
--	`cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
--	`gain_or_loss` VARCHAR(50) NOT NULL COMMENT 'Gain or (loss)',
--    `created_at` TIMESTAMP(3) NOT NULL DEFAULT CURRENT_TIMESTAMP(3) COMMENT 'Created At',
--    PRIMARY KEY (`id`)
-- ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - Form 8949';


DROP TABLE IF EXISTS `trade_history_1inchusd`;
CREATE TABLE `trade_history_1inchusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History 1INCHUSD';


DROP TABLE IF EXISTS `trade_history_aaveusd`;
CREATE TABLE `trade_history_aaveusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History AAVEUSD';

DROP TABLE IF EXISTS `trade_history_ampusd`;
CREATE TABLE `trade_history_ampusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History AMPUSD';

DROP TABLE IF EXISTS `trade_history_batusd`;
CREATE TABLE `trade_history_batusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History BATUSD';

DROP TABLE IF EXISTS `trade_history_balusd`;
CREATE TABLE `trade_history_balusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History BALUSD';

DROP TABLE IF EXISTS `trade_history_bchusd`;
CREATE TABLE `trade_history_bchusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History BCHUSD';

DROP TABLE IF EXISTS `trade_history_bntusd`;
CREATE TABLE `trade_history_bntusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History BNTUSD';


DROP TABLE IF EXISTS `trade_history_btcusd`;
CREATE TABLE `trade_history_btcusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History BTCUSD';

DROP TABLE IF EXISTS `trade_history_compusd`;
CREATE TABLE `trade_history_compusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History COMPUSD';

DROP TABLE IF EXISTS `trade_history_crvusd`;
CREATE TABLE `trade_history_crvusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History CRVUSD';

DROP TABLE IF EXISTS `trade_history_daiusd`;
CREATE TABLE `trade_history_daiusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History DAIUSD';

DROP TABLE IF EXISTS `trade_history_enjusd`;
CREATE TABLE `trade_history_enjusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History ENJUSD';

DROP TABLE IF EXISTS `trade_history_ethusd`;
CREATE TABLE `trade_history_ethusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History ETHUSD';

DROP TABLE IF EXISTS `trade_history_filusd`;
CREATE TABLE `trade_history_filusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History FILUSD';

DROP TABLE IF EXISTS `trade_history_grtusd`;
CREATE TABLE `trade_history_grtusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History GRTUSD';

DROP TABLE IF EXISTS `trade_history_kncusd`;
CREATE TABLE `trade_history_kncusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History KNCUSD';

DROP TABLE IF EXISTS `trade_history_linkusd`;
CREATE TABLE `trade_history_linkusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History LINKUSD';

DROP TABLE IF EXISTS `trade_history_lrcusd`;
CREATE TABLE `trade_history_lrcusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History LRCUSD';

DROP TABLE IF EXISTS `trade_history_ltcusd`;
CREATE TABLE `trade_history_ltcusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History LTCUSD';

DROP TABLE IF EXISTS `trade_history_mkrusd`;
CREATE TABLE `trade_history_mkrusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History MKRUSD';

DROP TABLE IF EXISTS `trade_history_manausd`;
CREATE TABLE `trade_history_manausd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History MANAUSD';

DROP TABLE IF EXISTS `trade_history_oxtusd`;
CREATE TABLE `trade_history_oxtusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History OXTUSD';

DROP TABLE IF EXISTS `trade_history_paxgusd`;
CREATE TABLE `trade_history_paxgusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History PAXGUSD';

DROP TABLE IF EXISTS `trade_history_renusd`;
CREATE TABLE `trade_history_renusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History RENUSD';

DROP TABLE IF EXISTS `trade_history_sandusd`;
CREATE TABLE `trade_history_sandusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History SANDUSD';

DROP TABLE IF EXISTS `trade_history_sklusd`;
CREATE TABLE `trade_history_sklusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History SKLUSD';

DROP TABLE IF EXISTS `trade_history_snxusd`;
CREATE TABLE `trade_history_snxusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History SNXUSD';

DROP TABLE IF EXISTS `trade_history_storjusd`;
CREATE TABLE `trade_history_storjusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History STORJUSD';

DROP TABLE IF EXISTS `trade_history_umausd`;
CREATE TABLE `trade_history_umausd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History UMAUSD';

DROP TABLE IF EXISTS `trade_history_uniusd`;
CREATE TABLE `trade_history_uniusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History UNIUSD';

DROP TABLE IF EXISTS `trade_history_yfiusd`;
CREATE TABLE `trade_history_yfiusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History YFIUSD';

DROP TABLE IF EXISTS `trade_history_zecusd`;
CREATE TABLE `trade_history_zecusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History ZECUSD';

DROP TABLE IF EXISTS `trade_history_zrxusd`;
CREATE TABLE `trade_history_zrxusd` (
  `tid` bigint(13) unsigned NOT NULL COMMENT 'Transaction ID',
  `price` varchar(50) NOT NULL COMMENT 'Price',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `timestampms` bigint(13) unsigned NOT NULL COMMENT 'Timestamp Milliseconds',
  `type` varchar(4) NOT NULL COMMENT 'Type',
  `aggressor` tinyint(1) NOT NULL COMMENT 'Aggressor',
  `fee_currency` varchar(10) NOT NULL COMMENT 'Fee Currency',
  `fee_amount` varchar(50) NOT NULL COMMENT 'Fee Amount',
  `order_id` bigint(13) unsigned NOT NULL COMMENT 'Order Id',
  `client_order_id` varchar(100) DEFAULT NULL,
  `trade_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Trade Date',
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History ZRXUSD';


CREATE TABLE `repeater_stats_daily_profit` (
  `id` int(13) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `symbol` varchar(12) NOT NULL COMMENT 'Symbol',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `amount_notional` varchar(50) NOT NULL COMMENT 'Amount',
  `date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Repeater Stats Daily Profit';

