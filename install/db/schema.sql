-- Statuses:
--   NEW
--   BUY_PLACED
--   BUY_FILLED
--   SELL_PLACED
--   COMPLETE
--   CANCELLED

--CREATE TABLE `trade_repeater` (
--  `trade_repeater_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Trade Repeater Id',
--  `exchange` varchar(25) NOT NULL COMMENT 'Exchange',
--  `symbol` varchar(12) NOT NULL COMMENT 'Symbol',
--  `status` varchar(24) NOT NULL DEFAULT 'NEW' COMMENT 'Status',
--  `auto_buy` tinyint(1)  NOT NULL DEFAULT 0 COMMENT 'Auto Buy',
--  `auto_sell` tinyint(1)  NOT NULL DEFAULT 0 COMMENT 'Auto Sell',
--  `order_id` int(10) unsigned COMMENT 'Order Id',
--  `buy_quote_price` varchar(50) NOT NULL COMMENT 'Buy Quote Price',
--  `buy_base_amount` varchar(50) NOT NULL COMMENT 'Buy Base Amount',
--  `sell_quote_price` varchar(50) NOT NULL COMMENT 'Sell Quote Price',
--  `sell_base_amount` varchar(50) NOT NULL COMMENT 'Sell Base Amount',
--  `completions` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'Completions', 
--  PRIMARY KEY (`trade_repeater_id`),
--  KEY `IDX_ORDER_ID` (`order_id`),
--  KEY `IDX_STATUS` (`status`),
--  KEY `IDX_AUTO_BUY` (`auto_buy`),
--  KEY `IDX_AUTO_SELL` (`auto_sell`)
--) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Trade Repeater';

DROP TABLE IF EXISTS `gemini_trade_repeater`;
CREATE TABLE `gemini_trade_repeater` (
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Gemini Trade Repeater ID',
    -- BUY_READY | BUY_SENT | BUY_PLACED | BUY_FILLED | SELL_SENT | SELL_PLACED | SELL_FILLED
    `is_enabled` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Is Enabled',
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
    `timestampms` INT(10) UNSIGNED NOT NULL COMMENT 'Timestamp Milliseconds',
    `side` VARCHAR(4) NOT NULL COMMENT 'Side',
    `fee_currency` VARCHAR(10) NOT NULL COMMENT 'Fee Currency',
    `fee_amount` VARCHAR(50) NOT NULL COMMENT 'Fee Amount',
    `order_id` INT (10) UNSIGNED NOT NULL COMMENT 'Order Id',
    `client_order_id` VARCHAR (100) NOT NULL COMMENT 'Client Order Id',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`transaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Gemini Trade History';


