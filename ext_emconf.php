<?php

########################################################################
# Extension Manager/Repository config file for ext: "dam_catedit"
#
# Auto generated 29-09-2007 00:36
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Media>Categories',
	'description' => 'Preliminary module for editing the DAM categories.',
	'category' => 'module',
	'shy' => 0,
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => 'mod1,mod_cmd,mod_clickmenu',
	'state' => 'alpha',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author' => 'René Fritz',
	'author_email' => 'r.fritz@colorcube.de',
	'author_company' => 'Colorcube - digital media lab',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'version' => '0.1.0',
	'constraints' => array(
		'depends' => array(
			'typo3' => '3.5.0-0.0.0',
			'php' => '3.0.0-0.0.0',
			'dam' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:28:{s:35:"class.tx_damcatedit_positionmap.php";s:4:"b303";s:12:"ext_icon.gif";s:4:"9250";s:14:"ext_tables.php";s:4:"950f";s:16:"locallang_cm.php";s:4:"eb60";s:39:"mod_cmd/class.tx_damcatedit_cmd_new.php";s:4:"00e6";s:43:"mod_cmd/class.tx_damcatedit_cmd_nothing.php";s:4:"7436";s:16:"mod_cmd/conf.php";s:4:"e9c3";s:17:"mod_cmd/index.php";s:4:"f570";s:21:"mod_cmd/locallang.php";s:4:"977b";s:25:"mod_cmd/locallang_mod.php";s:4:"32d8";s:22:"mod_cmd/moduleicon.gif";s:4:"adc5";s:37:"lib/class.tx_damcatedit_clickmenu.php";s:4:"2854";s:30:"lib/class.tx_damcatedit_db.php";s:4:"a392";s:31:"lib/class.tx_damcatedit_div.php";s:4:"8b95";s:14:"mod1/clear.gif";s:4:"cc11";s:13:"mod1/conf.php";s:4:"14d6";s:14:"mod1/index.php";s:4:"e911";s:18:"mod1/locallang.php";s:4:"5ecc";s:22:"mod1/locallang_mod.php";s:4:"50e5";s:19:"mod1/moduleicon.gif";s:4:"8d3f";s:32:"mod1/tx_dam_catedit_navframe.php";s:4:"beb9";s:22:"mod_clickmenu/conf.php";s:4:"912d";s:23:"mod_clickmenu/index.php";s:4:"ceb2";s:31:"mod_clickmenu/locallang_mod.php";s:4:"694f";s:28:"mod_clickmenu/moduleicon.gif";s:4:"adc5";s:14:"doc/manual.sxw";s:4:"2e8e";s:19:"doc/wizard_form.dat";s:4:"8171";s:20:"doc/wizard_form.html";s:4:"f9b5";}',
);

?>