
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

