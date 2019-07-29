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
    `note` text NULL COMMENT 'Note',
    `meta` text NULL COMMENT 'Meta Data',
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `IDX_STATUS` (`status`),
    KEY `IDX_IS_ENABLED` (`is_enabled`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Gemini Trade Repeater';

DROP TABLE IF EXISTS `gemini_trade_repeater_archive`;
CREATE TABLE `gemini_trade_repeater_archive` (
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Gemini Trade Repeater ID',
    `symbol` VARCHAR(12) NOT NULL COMMENT 'Symbol',
    `buy_client_order_id` VARCHAR(30) NULL COMMENT 'Buy Client Order ID',
    `buy_order_id` VARCHAR(30) NULL COMMENT 'Buy Order Id',
    `buy_amount` VARCHAR(50) NOT NULL COMMENT 'Buy Amount',
    `buy_price` VARCHAR (50) NOT NULL COMMENT 'Buy Price',
    `sell_client_order_id` VARCHAR(30) NULL COMMENT 'Sell Client Order ID',
    `sell_order_id` VARCHAR(30) NULL COMMENT 'Sell Order ID',
    `sell_amount` VARCHAR (50) NOT NULL COMMENT 'Sell Amount',
    `sell_price` VARCHAR (50) NOT NULL COMMENT 'Sell Price',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Gemini Trade Repeater Archive';
