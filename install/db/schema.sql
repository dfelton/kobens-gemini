
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


CREATE TABLE IF NOT EXISTS `taxes_btcusd_buy_log` (
	`tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
	`amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - BTCUSD Buy Log';


CREATE TABLE IF NOT EXISTS `taxes_ethusd_buy_log` (
        `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
        `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ETHUSD Buy Log';


CREATE TABLE IF NOT EXISTS `taxes_zecusd_buy_log` (
        `tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Transaction ID',
        `amount_remaining` VARCHAR(50) NOT NULL COMMENT 'Amount Remaining',
    PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ZECUSD Buy Log';


CREATE TABLE IF NOT EXISTS `taxes_1inchusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - 1INCHUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_aaveusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - AAVEUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_ampusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - AMPUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_ankrusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ANKRUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_balusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - BALUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_batusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - BATUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_bchusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - BCHUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_bntusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - BNTUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_bondusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - BONDUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_btcusd_sell_log` (
	`sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
	`buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
	`amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
	`cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
	`proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
	`capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - BTCUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_compusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - COMPUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_ctxusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - CTXUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_crvusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - CRVUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_cubeusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - CUBEUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_daiusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - DAIUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_dogeusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - DOGEUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_enjusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ENJUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_ethusd_sell_log` (
        `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
        `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
        `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
        `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
        `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
        `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ETHUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_filusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - FILUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_ftmusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - FTMUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_grtusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - GRTUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_injusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - INJUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_kncusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - KNCUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_linkusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - LINKUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_lptusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - LPTUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_lrcusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - LRCUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_ltcusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - LTCUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_manausd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - MANAUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_maticusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - MATICUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_mirusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - MIRUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_mkrusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - MKRUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_oxtusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - OXTUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_paxgusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - PAXGUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_renusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - RENUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_sandusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - SANDUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_sklusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - SKLUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_snxusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - SNXUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_storjusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - STORJUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_sushiusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - SUSHIUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_umausd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - UMAUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_uniusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - UNIUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_xtzusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - XTZUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_yfiusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - YFIUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_zecusd_sell_log` (
        `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
        `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
        `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
        `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
        `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
        `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ZECUSD Sell Log';


CREATE TABLE IF NOT EXISTS `taxes_zrxusd_sell_log` (
    `sell_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Sell Transaction ID',
    `buy_tid` BIGINT(13) UNSIGNED NOT NULL COMMENT 'Buy Transaction ID',
    `amount` VARCHAR(50) NOT NULL COMMENT 'Amount',
    `cost_basis` VARCHAR(50) NOT NULL COMMENT 'Cost Basis',
    `proceeds` VARCHAR(50) NOT NULL COMMENT 'Proceeds',
    `capital_gain` VARCHAR(50) NOT NULL COMMENT 'Capital Gain',
    PRIMARY KEY (`sell_tid`, `buy_tid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='Taxes - ZRXUSD Sell Log';

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


CREATE TABLE IF NOT EXISTS `trade_history_1inchusd` (
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


CREATE TABLE IF NOT EXISTS `trade_history_aaveusd` (
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


CREATE TABLE IF NOT EXISTS `trade_history_ampusd` (
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


CREATE TABLE IF NOT EXISTS `trade_history_ankrusd` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History ANKRUSD';


CREATE TABLE IF NOT EXISTS `trade_history_balusd` (
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


CREATE TABLE IF NOT EXISTS `trade_history_batbtc` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History BATBTC';


CREATE TABLE IF NOT EXISTS `trade_history_bateth` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History BATETH';


CREATE TABLE IF NOT EXISTS `trade_history_batusd` (
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


CREATE TABLE IF NOT EXISTS `trade_history_bchbtc` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History BCHBTC';


CREATE TABLE IF NOT EXISTS `trade_history_bcheth` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History BCHETH';


CREATE TABLE IF NOT EXISTS `trade_history_bchusd` (
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


CREATE TABLE IF NOT EXISTS `trade_history_bntusd` (
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


CREATE TABLE IF NOT EXISTS `trade_history_bondusd` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History BONDUSD';


CREATE TABLE IF NOT EXISTS `trade_history_btcdai` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History BTCDAI';


CREATE TABLE IF NOT EXISTS `trade_history_btcusd` (
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


CREATE TABLE IF NOT EXISTS `trade_history_compusd` (
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


CREATE TABLE IF NOT EXISTS `trade_history_crvusd` (
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


CREATE TABLE IF NOT EXISTS `trade_history_ctxusd` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History CTXUSD';


CREATE TABLE IF NOT EXISTS `trade_history_cubeusd` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History CUBEUSD';


CREATE TABLE IF NOT EXISTS `trade_history_daiusd` (
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


CREATE TABLE IF NOT EXISTS `trade_history_dogeusd` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History DOGEUSD';


CREATE TABLE IF NOT EXISTS `trade_history_enjusd` (
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


CREATE TABLE IF NOT EXISTS `trade_history_ethbtc` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History ETHBTC';


CREATE TABLE IF NOT EXISTS `trade_history_ethdai` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History ETHDAI';


CREATE TABLE IF NOT EXISTS `trade_history_ethusd` (
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


CREATE TABLE IF NOT EXISTS `trade_history_filusd` (
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


CREATE TABLE IF NOT EXISTS `trade_history_ftmusd` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History FTMUSD';


CREATE TABLE IF NOT EXISTS `trade_history_grtusd` (
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


CREATE TABLE IF NOT EXISTS `trade_history_injusd` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History INJUSD';


CREATE TABLE IF NOT EXISTS `trade_history_kncusd` (
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


CREATE TABLE IF NOT EXISTS `trade_history_linkbtc` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History LINKBTC';


CREATE TABLE IF NOT EXISTS `trade_history_linketh` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History LINKETH';


CREATE TABLE IF NOT EXISTS `trade_history_linkusd` (
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


CREATE TABLE IF NOT EXISTS `trade_history_lptusd` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History LPTUSD';


CREATE TABLE IF NOT EXISTS `trade_history_lrcusd` (
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


CREATE TABLE IF NOT EXISTS `trade_history_ltcbch` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History LTCBCH';


CREATE TABLE IF NOT EXISTS `trade_history_ltcbtc` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History LTCBTC';


CREATE TABLE IF NOT EXISTS `trade_history_ltceth` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History LTCETH';


CREATE TABLE IF NOT EXISTS `trade_history_ltcusd` (
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


CREATE TABLE IF NOT EXISTS `trade_history_manausd` (
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


CREATE TABLE IF NOT EXISTS `trade_history_maticusd` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History MATICUSD';


CREATE TABLE IF NOT EXISTS `trade_history_mirusd` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History MIRUSD';


CREATE TABLE IF NOT EXISTS `trade_history_mkrusd` (
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


CREATE TABLE IF NOT EXISTS `trade_history_oxtbtc` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History OXTBTC';


CREATE TABLE IF NOT EXISTS `trade_history_oxteth` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History OXTETH';


CREATE TABLE IF NOT EXISTS `trade_history_oxtusd` (
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


CREATE TABLE IF NOT EXISTS `trade_history_paxgusd` (
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


CREATE TABLE IF NOT EXISTS `trade_history_renusd` (
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


CREATE TABLE IF NOT EXISTS `trade_history_sandusd` (
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


CREATE TABLE IF NOT EXISTS `trade_history_sklusd` (
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


CREATE TABLE IF NOT EXISTS `trade_history_snxusd` (
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


CREATE TABLE IF NOT EXISTS `trade_history_storjusd` (
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


CREATE TABLE IF NOT EXISTS `trade_history_sushiusd` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History SUSHIUSD';


CREATE TABLE IF NOT EXISTS `trade_history_umausd` (
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


CREATE TABLE IF NOT EXISTS `trade_history_uniusd` (
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


CREATE TABLE IF NOT EXISTS `trade_history_xtzusd` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History XTZUSD';


CREATE TABLE IF NOT EXISTS `trade_history_yfiusd` (
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


CREATE TABLE IF NOT EXISTS `trade_history_zecbch` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History ZECBCH';


CREATE TABLE IF NOT EXISTS `trade_history_zecbtc` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History ZECBTC';


CREATE TABLE IF NOT EXISTS `trade_history_zeceth` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History ZECETH';


CREATE TABLE IF NOT EXISTS `trade_history_zecltc` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Trade History ZECLTC';

CREATE TABLE IF NOT EXISTS `trade_history_zecusd` (
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


CREATE TABLE IF NOT EXISTS `trade_history_zrxusd` (
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


CREATE TABLE IF NOT EXISTS `repeater_stats_daily_profit` (
  `id` int(13) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `symbol` varchar(12) NOT NULL COMMENT 'Symbol',
  `amount` varchar(50) NOT NULL COMMENT 'Amount',
  `amount_notional` varchar(50) NOT NULL COMMENT 'Amount',
  `date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Repeater Stats Daily Profit';

