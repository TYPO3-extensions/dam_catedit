<?php

########################################################################
# Extension Manager/Repository config file for ext "dam_catedit".
#
# Auto generated 21-07-2012 00:05
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Media>Categories',
	'description' => 'Module for editing the DAM categories.',
	'category' => 'module',
	'shy' => 0,
	'version' => '1.3.1-dev',
	'dependencies' => 'dam',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => 'mod1,mod_cmd,mod_clickmenu',
	'doNotLoadInFE' => 1,
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'The DAM development team',
	'author_email' => 'typo3-project-dam@lists.netfielders.de',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'dam' => '1.3.0-',
			'php' => '5.2.0-0.0.0',
			'typo3' => '4.5.0-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:29:{s:9:"ChangeLog";s:4:"4b88";s:25:"class.tx_dam_db_list2.php";s:4:"ba8e";s:34:"class.tx_damcatedit_deletehook.php";s:4:"c68b";s:32:"class.tx_damcatedit_movehook.php";s:4:"0b02";s:35:"class.tx_damcatedit_newedithook.php";s:4:"7e03";s:35:"class.tx_damcatedit_positionmap.php";s:4:"77c9";s:12:"ext_icon.gif";s:4:"eb28";s:17:"ext_localconf.php";s:4:"5232";s:14:"ext_tables.php";s:4:"d4bf";s:16:"locallang_cm.xml";s:4:"b39f";s:14:"doc/manual.sxw";s:4:"9b57";s:30:"lib/class.tx_damcatedit_cm.php";s:4:"463f";s:30:"lib/class.tx_damcatedit_db.php";s:4:"9546";s:39:"mod_cmd/class.tx_damcatedit_cmd_new.php";s:4:"dfac";s:43:"mod_cmd/class.tx_damcatedit_cmd_nothing.php";s:4:"d63b";s:16:"mod_cmd/conf.php";s:4:"9c77";s:17:"mod_cmd/index.php";s:4:"b7b9";s:21:"mod_cmd/locallang.xml";s:4:"be81";s:25:"mod_cmd/locallang_mod.xml";s:4:"c6e3";s:22:"mod_cmd/moduleicon.gif";s:4:"adc5";s:14:"mod1/clear.gif";s:4:"cc11";s:13:"mod1/conf.php";s:4:"fbee";s:14:"mod1/index.php";s:4:"123b";s:18:"mod1/locallang.xml";s:4:"6db2";s:22:"mod1/locallang_mod.xml";s:4:"bb2f";s:22:"mod1/mod_template.html";s:4:"6374";s:27:"mod1/mod_template_tree.html";s:4:"ed85";s:19:"mod1/moduleicon.png";s:4:"278f";s:32:"mod1/tx_dam_catedit_navframe.php";s:4:"2405";}',
	'suggests' => array(
	),
);

?>