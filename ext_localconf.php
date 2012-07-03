<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

/**
  *  Enable hook after saving/altering a DAM category
  */
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'EXT:dam_catedit/class.tx_damcatedit_newedithook.php:&tx_damcatedit_newedithook';

/**
  *  Enable hook after moving a DAM category
  */
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['moveRecordClass'][] = 'EXT:dam_catedit/class.tx_damcatedit_movehook.php:&tx_damcatedit_movehook';

/**
  *  Enable hook after deleting a DAM category
  */
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processCmdmapClass'][] = 'EXT:dam_catedit/class.tx_damcatedit_deletehook.php:&tx_damcatedit_deletehook';

?>
