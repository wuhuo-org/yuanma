CREATE DATABASE linux011;
USE linux011;

CREATE TABLE `bu_chong` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `han_shu` int(11) DEFAULT NULL,
  `dai_ma` int(11) DEFAULT NULL,
  `zhu_shi` int(11) DEFAULT NULL,
  `xu_hao` int(11) DEFAULT NULL,
  `nei_rong` text CHARACTER SET utf8,
  `zan_cheng` int(11) DEFAULT NULL,
  `fan_dui` int(11) DEFAULT NULL,
  `zuo_zhe` char(50) CHARACTER SET utf8 DEFAULT NULL,
  `shi_jian` datetime DEFAULT NULL,
  `chl_1` bit(64) DEFAULT NULL,
  `shj_ch` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `bu_chong_hd` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `han_shu` int(11) DEFAULT NULL,
  `dai_ma` int(11) DEFAULT NULL,
  `wen_ti` int(11) DEFAULT NULL,
  `hui_da` int(11) DEFAULT NULL,
  `xu_hao` int(11) DEFAULT NULL,
  `nei_rong` text CHARACTER SET utf8,
  `zan_cheng` int(11) DEFAULT NULL,
  `fan_dui` int(11) DEFAULT NULL,
  `zuo_zhe` char(50) CHARACTER SET utf8 DEFAULT NULL,
  `shi_jian` datetime DEFAULT NULL,
  `chl_1` bit(64) DEFAULT NULL,
  `shj_ch` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `dai_ma` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `han_shu` int(11) DEFAULT NULL,
  `xu_hao` int(11) DEFAULT NULL,
  `zhu_shi` int(11) DEFAULT NULL,
  `wen_ti` int(11) DEFAULT NULL,
  `nei_rong` text,
  `shi_jian` datetime DEFAULT NULL,
  `gl_1` bit(64) DEFAULT NULL,
  `shj_ch` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `fan_dui` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `han_shu` int(11) DEFAULT NULL,
  `dai_ma` int(11) DEFAULT NULL,
  `zhu_shi` int(11) DEFAULT NULL,
  `xu_hao` int(11) DEFAULT NULL,
  `nei_rong` text CHARACTER SET utf8,
  `zan_cheng` int(11) DEFAULT NULL,
  `fan_dui` int(11) DEFAULT NULL,
  `zuo_zhe` char(50) CHARACTER SET utf8 DEFAULT NULL,
  `shi_jian` datetime DEFAULT NULL,
  `chl_1` bit(64) DEFAULT NULL,
  `shj_ch` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `fan_dui_hd` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `han_shu` int(11) DEFAULT NULL,
  `dai_ma` int(11) DEFAULT NULL,
  `wen_ti` int(11) DEFAULT NULL,
  `hui_da` int(11) DEFAULT NULL,
  `xu_hao` int(11) DEFAULT NULL,
  `nei_rong` text CHARACTER SET utf8,
  `zan_cheng` int(11) DEFAULT NULL,
  `fan_dui` int(11) DEFAULT NULL,
  `zuo_zhe` char(50) CHARACTER SET utf8 DEFAULT NULL,
  `shi_jian` datetime DEFAULT NULL,
  `chl_1` bit(64) DEFAULT NULL,
  `shj_ch` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `han_shu` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `ming_zi` char(40) DEFAULT NULL,
  `gong_neng` text CHARACTER SET utf8,
  `dai_ma` int(11) DEFAULT NULL,
  `mo_kuai` char(5) DEFAULT NULL,
  `zuo_yong` char(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `hui_da` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `han_shu` int(11) DEFAULT NULL,
  `dai_ma` int(11) DEFAULT NULL,
  `wen_ti` int(11) DEFAULT NULL,
  `xu_hao` int(11) DEFAULT NULL,
  `nei_rong` text CHARACTER SET utf8,
  `zan_cheng` int(11) DEFAULT NULL,
  `bu_chong` int(11) DEFAULT NULL,
  `fan_dui` int(11) DEFAULT NULL,
  `zuo_zhe` char(50) CHARACTER SET utf8 DEFAULT NULL,
  `shi_jian` datetime DEFAULT NULL,
  `chl_1` bit(64) DEFAULT NULL,
  `shj_ch` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `wen_ti` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `han_shu` int(11) DEFAULT NULL,
  `dai_ma` int(11) DEFAULT NULL,
  `xu_hao` int(11) DEFAULT NULL,
  `nei_rong` text CHARACTER SET utf8,
  `zhi_chi` int(11) DEFAULT NULL,
  `hui_da` int(11) DEFAULT NULL,
  `zuo_zhe` char(50) CHARACTER SET utf8 DEFAULT NULL,
  `shi_jian` datetime DEFAULT NULL,
  `chl_1` bit(64) DEFAULT NULL,
  `shj_ch` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `zhang_hao` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `zhang_hao` char(20) DEFAULT NULL,
  `mi_ma` char(20) DEFAULT NULL,
  `you_xiang` char(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `zhu_shi` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `han_shu` int(11) DEFAULT NULL,
  `dai_ma` int(11) DEFAULT NULL,
  `xu_hao` int(11) DEFAULT NULL,
  `nei_rong` text CHARACTER SET utf8,
  `zan_cheng` int(11) DEFAULT NULL,
  `bu_chong` int(11) DEFAULT NULL,
  `fan_dui` int(11) DEFAULT NULL,
  `zuo_zhe` char(50) CHARACTER SET utf8 DEFAULT NULL,
  `shi_jian` datetime DEFAULT NULL,
  `chl_1` bit(64) DEFAULT NULL,
  `shj_ch` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

GRANT ALL ON linux011.* TO 'linux011' IDENTIFIED BY 'ikm-098';
FLUSH PRIVILEGES;