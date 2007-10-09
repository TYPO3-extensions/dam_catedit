<?php

########################################################################
# Extension Manager/Repository config file for ext: "dam_catedit"
#
# Auto generated 29-09-2007 00:38
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Media>Categories',
	'description' => 'Module for editing the DAM categories.',
	'category' => 'module',
	'shy' => 0,
	'version' => '1.0.4',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => 'mod1,mod_cmd,mod_clickmenu',
	'state' => 'alpha',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'Rene Fritz',
	'author_email' => 'r.fritz@colorcube.de',
	'author_company' => 'Colorcube - digital media lab, www.colorcube.de',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'dam' => '',
			'php' => '4.0.0-0.0.0',
			'typo3' => '3.8.0-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:28:{s:9:"ChangeLog";s:4:"79ce";s:25:"class.tx_dam_db_list2.php";s:4:"594b";s:35:"class.tx_damcatedit_positionmap.php";s:4:"e75a";s:12:"ext_icon.gif";s:4:"eb28";s:14:"ext_tables.php";s:4:"7ef5";s:16:"locallang_cm.xml";s:4:"6be2";s:39:"mod_cmd/class.tx_damcatedit_cmd_new.php";s:4:"f0cf";s:43:"mod_cmd/class.tx_damcatedit_cmd_nothing.php";s:4:"703e";s:16:"mod_cmd/conf.php";s:4:"2a10";s:17:"mod_cmd/index.php";s:4:"7a1b";s:21:"mod_cmd/locallang.xml";s:4:"537f";s:25:"mod_cmd/locallang_mod.xml";s:4:"0136";s:22:"mod_cmd/moduleicon.gif";s:4:"adc5";s:37:"lib/class.tx_damcatedit_clickmenu.php";s:4:"ae61";s:30:"lib/class.tx_damcatedit_db.php";s:4:"df7e";s:31:"lib/class.tx_damcatedit_div.php";s:4:"23f2";s:14:"mod1/clear.gif";s:4:"cc11";s:13:"mod1/conf.php";s:4:"39bb";s:14:"mod1/index.php";s:4:"8392";s:18:"mod1/locallang.xml";s:4:"7fea";s:22:"mod1/locallang_mod.xml";s:4:"7790";s:19:"mod1/moduleicon.gif";s:4:"8d3f";s:32:"mod1/tx_dam_catedit_navframe.php";s:4:"7645";s:22:"mod_clickmenu/conf.php";s:4:"44bf";s:23:"mod_clickmenu/index.php";s:4:"02ba";s:31:"mod_clickmenu/locallang_mod.xml";s:4:"1a70";s:28:"mod_clickmenu/moduleicon.gif";s:4:"adc5";s:14:"doc/manual.sxw";s:4:"269e";}',
);

?>