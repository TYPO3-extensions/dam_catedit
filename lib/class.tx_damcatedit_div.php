<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2004-2006 Rene Fritz (r.fritz@colorcube.de)
*  All rights reserved
*
*  This script is part of the Typo3 project. The Typo3 project is 
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
* 
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*  A copy is found in the textfile GPL.txt and important notices to the license 
*  from the author is found in LICENSE.txt distributed with these scripts.
*
* 
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * div functions
 *
 *
 * @author	Rene Fritz <r.fritz@colorcube.de>
 */

class tx_damcatedit_div {

	/**
	 * Makes click menu link (context sensitive menu)
	 * Returns $str (possibly an <|img> tag/icon) wrapped in a link which will activate the context sensitive menu for the record ($table/$uid) or file ($table = file)
	 * The link will load the top frame with the parameter "&item" which is the table,uid and listFr arguments imploded by "|": rawurlencode($table.'|'.$uid.'|'.$listFr)
	 *
	 * @param	string		String to be wrapped in link, typ. image tag.
	 * @param	string		Table name/File path. If the icon is for a database record, enter the tablename from $TCA. If a file then enter the absolute filepath
	 * @param	integer		If icon is for database record this is the UID for the record from $table
	 * @param	boolean		Tells the top frame script that the link is coming from a "list" frame which means a frame from within the backend content frame.
	 * @param	string		Additional GET parameters for the link to alt_clickmenu.php
	 * @param	string		Enable / Disable click menu items. Example: "+new,view" will display ONLY these two items (and any spacers in between), "new,view" will display all BUT these two items.
	 * @param	string		Clickmenu script. Default: $BACK_PATH.alt_clickmenu.php.
	 * @return	string		The link-wrapped input string.
	 */
	function clickMenuWrap($str, $table, $uid='', $listFrame=true, $addParams='', $enDisItems='', $cmdMod='', $clickMenuScript='')	{
		$onClick = tx_damcatedit_div::clickMenuOnClick($table, $uid, $listFrame, $addParams, $enDisItems, $cmdMod='', $clickMenuScript);
		return '<a href="#" onclick="'.htmlspecialchars($onClick).'">'.$str.'</a>';
	}
	
	/**
	 * Makes click menu link (context sensitive menu)
	 * Returns $str (possibly an <|img> tag/icon) wrapped in a link which will activate the context sensitive menu for the record ($table/$uid) or file ($table = file)
	 * The link will load the top frame with the parameter "&item" which is the table,uid and listFr arguments imploded by "|": rawurlencode($table.'|'.$uid.'|'.$listFr)
	 *
	 * @param	string		Table name/File path. If the icon is for a database record, enter the tablename from $TCA. If a file then enter the absolute filepath
	 * @param	integer		If icon is for database record this is the UID for the record from $table
	 * @param	boolean		Tells the top frame script that the link is coming from a "list" frame which means a frame from within the backend content frame.
	 * @param	string		Additional GET parameters for the link to alt_clickmenu.php
	 * @param	string		Enable / Disable click menu items. Example: "+new,view" will display ONLY these two items (and any spacers in between), "new,view" will display all BUT these two items.
	 * @param	string		tce target script to be called by clickmenu.
	 * @param	string		Clickmenu script. Default: $BACK_PATH.alt_clickmenu.php.
	 * @return	string		The link-wrapped input string.
	 */	
	function clickMenuOnClick($table, $uid='', $listFrame=true, $addParams='', $enDisItems='', $cmdMod='', $clickMenuScript='')	{
		$clickMenuScript = $clickMenuScript ? $clickMenuScript : $GLOBALS['BACK_PATH'].t3lib_extmgm::extRelPath('dam_catedit').'mod_clickmenu/index.php';
		
		$cmdMod = $cmdMod ? '&cmdMod'.$cmdMod : '';
		
		$backPath = '&backPath='.rawurlencode($GLOBALS['BACK_PATH']).'|'.t3lib_div::shortMD5($GLOBALS['BACK_PATH'].'|'.$GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey']);
		
		$paramsArr = array('table' => $table,'uid' => $uid,'listFrame' => $listFrame,'enDisItems' => $enDisItems);
		$itemParam = tx_damcatedit_div::compilePipeParams($paramsArr);
		
		$onClick = 'top.loadTopMenu(\''.$clickMenuScript.'?item='.rawurlencode($itemParam).$backPath.$cmdMod.$addParams.'\');'.template::thisBlur().'return false;';
		return $onClick;
	}
	
	
	function compilePipeParams ($paramsArr) {
		$params = array();
		
		foreach($paramsArr as $key => $value) {
			$params[] = $key.':'.$value;
		} 
		return implode ('|', $params);
	}	
	
	function decodePipeParams($paramStr) {
		$paramsArr = array();
		
		$params = explode('|', $paramStr);

		foreach($params as $value) {
			list($key,$value) = $params = explode(':', $value);
			$paramsArr[$key] = $value;
		} 
		return $paramsArr;
	}

			
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dam_catedit/lib/class.tx_damcatedit_div.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dam_catedit/lib/class.tx_damcatedit_div.php']);
}


?>
