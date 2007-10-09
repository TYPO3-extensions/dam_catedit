<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2003-2005 Ren� Fritz (r.fritz@colorcube.de)
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
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * Command module 'new cat'
 *
 * @author	Ren� Fritz <r.fritz@colorcube.de>
 * @package TYPO3
 * @subpackage tx_damcatedit
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *

 *
 */


require_once(PATH_t3lib.'class.t3lib_extobjbase.php');



/**
 * "no command" module
 *
 * @author	Ren� Fritz <r.fritz@colorcube.de>
 * @package TYPO3
 * @subpackage tx_damcatedit
 */
class tx_damcatedit_cmd_new extends t3lib_extobjbase {


	/**
	 * Do some init things and set some things in HTML header
	 * 
	 * @return	void		
	 */
	function head() {
		global $LANG, $SOBE, $BACK_PATH, $TYPO3_CONF_VARS;

		$SOBE->pageTitle = $LANG->getLL('tx_damcatedit_cmd_new.title');
	}


	/**
	 * Main function
	 *
	 * @return	void
	 */
	function main()	{
		global $LANG, $SOBE, $TCA, $BACK_PATH;

		$content ='';

		$param = t3lib_div::_GP('edit');
		$table = key($param);
		$uid = (string)key($param[$table]);
		$cmd = $param[$table][$uid];
		
//		$content='This is the GET/POST vars sent to the script:<BR>'.
//			'GET:'.t3lib_div::view_array($GLOBALS['HTTP_GET_VARS']).'<BR>'.
//			'POST:'.t3lib_div::view_array($GLOBALS['HTTP_POST_VARS']).'<BR>'.
//			'';
		
		if(is_array($TCA[$table]) AND $cmd=='new') {



require_once(PATH_txdam.'lib/class.tx_dam_db.php');
list($this->defaultPid,$this->defaultFolder,$this->folderList) = tx_dam_db::initDAMFolders();

			
			$getArray['edit'][$table][$this->defaultPid]='new';
			$getArray['defVals'] = t3lib_div::_GP('defVals');
			$getArray['defVals'][$table]['pid']=$this->defaultPid;

#debug($this->defaultPid);			
#debug($getArray);			
			$getArray = t3lib_div::compileSelectedGetVarsFromArray('edit,defVals,overrideVals,columnsOnly,disHelp,noView,editRegularContentFromId',$getArray);
			$getUrl = t3lib_div::implodeArrayForUrl('',$getArray);
		
#debug($getUrl);				
			header('Location: '.$BACK_PATH.'alt_doc.php?id='.$this->defaultPid.$getUrl);
		} else {
			$content.= 'wrong comand!';
		}
	
#TODO do it always this way (with if)		
		if ($this->pObj->returnUrl) {
			$content.= '<br /><br />'.$this->pObj->btn_back('',$this->pObj->returnUrl);
		}


			// CSH:
#		$content.= t3lib_BEfunc::cshItem('xMOD_csh_corebe', 'file_rename', $GLOBALS['BACK_PATH'],'<br/>');


		return $content;

	}


}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dam_catedit/mod_cmd/class.tx_damcatedit_cmd_new.php'])    {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dam_catedit/mod_cmd/class.tx_damcatedit_cmd_new.php']);
}


?>