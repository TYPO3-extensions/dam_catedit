<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2003-2006 Rene Fritz (r.fritz@colorcube.de)
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
 * DAM edit nav frame.
 * Part of the DAM (digital asset management) extension.
 *
 * @author	Rene Fritz <r.fritz@colorcube.de>
 * @package TYPO3
 * @subpackage tx_dam
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   73: class tx_dam_navframe
 *   89:     function init()
 *  111:     function jumpTo(params,linkObj,highLightID)
 *  127:     function refresh_nav()
 *  136:     function _refresh_nav()
 *  179:     function main()
 *  207:     function printContent()
 *
 * TOTAL FUNCTIONS: 6
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */



unset($MCONF);
include ('conf.php');
include ($BACK_PATH.'init.php');
include ($BACK_PATH.'template.php');



if (!defined ('PATH_txdam')) {
	define('PATH_txdam', t3lib_extMgm::extPath('dam'));
}
require_once(PATH_txdam.'lib/class.tx_dam.php');
require_once(PATH_txdam.'lib/class.tx_dam_browsetrees.php');





/**
 * Main script class for the tree edit navigation frame
 *
 * @author	@author	Rene Fritz <r.fritz@colorcube.de>
 * @package TYPO3
 * @subpackage tx_dam
 */
class tx_damcatedit_navframe {

	var $categoryTree;

	var $doc;
	var $content;

		// Internal, static: _GP
	var $currentSubScript;

		// Constructor:
	function init()	{
		global $BE_USER,$LANG,$BACK_PATH,$TYPO3_CONF_VARS;

		$this->doc = t3lib_div::makeInstance('template');
		$this->doc->backPath = $BACK_PATH;


		$this->currentSubScript = t3lib_div::_GP('currentSubScript');

			// Setting highlight mode:
		$this->doHighlight = !$BE_USER->getTSConfigVal('options.pageTree.disableTitleHighlight');


		$this->doc->JScode='';

			// Setting JavaScript for menu.
		$this->doc->JScode=$this->doc->wrapScriptTags(
			($this->currentSubScript?'top.currentSubScript=unescape("'.rawurlencode($this->currentSubScript).'");':'').'

			function jumpTo(params,linkObj,highLightID)	{
				var theUrl = top.TS.PATH_typo3+top.currentSubScript+"?"+params;

				if (top.condensedMode)	{
					top.content.document.location=theUrl;
				} else {
					parent.list_frame.document.location=theUrl;
				}
				'.($this->doHighlight?'hilight_row("row"+top.fsMod.recentIds["txdamM1"],highLightID);':'').'
				'.(!$GLOBALS['CLIENT']['FORMSTYLE'] ? '' : 'if (linkObj) {linkObj.blur();}').'
				return false;
			}


				// Call this function, refresh_nav(), from another script in the backend if you want to refresh the navigation frame (eg. after having changed a page title or moved pages etc.)
				// See t3lib_BEfunc::getSetUpdateSignal()
			function refresh_nav()	{
				window.setTimeout("_refresh_nav();",0);
			}


			function _refresh_nav()	{
				document.location="'.htmlspecialchars(t3lib_div::getIndpEnv('SCRIPT_NAME').'?unique='.time()).'";
			}

				// Highlighting rows in the page tree:
			function hilight_row(frameSetModule,highLightID) {	//

					// Remove old:
				theObj = document.getElementById(top.fsMod.navFrameHighlightedID[frameSetModule]);
				if (theObj)	{
					theObj.style.backgroundColor="";
				}

					// Set new:
				top.fsMod.navFrameHighlightedID[frameSetModule] = highLightID;
				theObj = document.getElementById(highLightID);
				if (theObj)	{
					theObj.style.backgroundColor="'.t3lib_div::modifyHTMLColorAll($this->doc->bgColor,-20).'";
				}
			}
		');

		$CMparts=$this->doc->getContextMenuCode();
		$this->doc->bodyTagAdditions = $CMparts[1];
		$this->doc->JScode.=$CMparts[0];
		$this->doc->postCode.= $CMparts[2];

			// from tx_dam_SCbase
		$this->doc->buttonColor = '#e3dfdb'; #t3lib_div::modifyHTMLcolor($this->doc->bgColor4,0,0,0);
		$this->doc->buttonColorHover = t3lib_div::modifyHTMLcolor($this->doc->buttonColor,-20,-20,-20);


			// should be float but gives bad results
		$this->doc->inDocStyles .= '

					/* Trees */
			TABLE.typo3-browsetree A { text-decoration: none;  }
			TABLE.typo3-browsetree TR TD { white-space: nowrap; vertical-align: middle; }
			TABLE.typo3-browsetree TR TD IMG { vertical-align: middle; }
			TABLE.typo3-browsetree TR TD IMG.c-recIcon { margin-right: 1px;}
			TABLE.typo3-browsetree { margin-bottom: 10px; width: 95%; }

			TABLE.typo3-browsetree TR TD.typo3-browsetree-control {
				padding: 0px 0px 1px 0px;
			}
			TABLE.typo3-browsetree TR TD.typo3-browsetree-control a {
				padding: 0px 3px 0px 3px;
				background-color: '.$this->doc->buttonColor.';
			}
			TABLE.typo3-browsetree TR TD.typo3-browsetree-control > a:hover {
				background-color:'.$this->doc->buttonColorHover.';
			}
			';




	}




	/**
	 * Main function, rendering the browsable page tree
	 *
	 * @return	void
	 */
	function main()	{
		global $LANG,$BACK_PATH,$TYPO3_CONF_VARS;

		$this->content = '';
		$this->content.= $this->doc->startPage('Navigation');


			// the trees
		$this->browseTrees = t3lib_div::makeInstance('tx_dam_browseTrees');
			// show only categories:
		$selClass = array('txdamCat' => $TYPO3_CONF_VARS['EXTCONF']['dam']['selectionClasses']['txdamCat']);
		$this->browseTrees->initSelectionClasses($selClass, 'tx_dam_catedit_navframe.php');
		$this->browseTrees->treeObjArr['txdamCat']->ext_IconMode = false; // context menu on icons
		$this->browseTrees->treeObjArr['txdamCat']->modeSelIcons = false;
		$this->browseTrees->treeObjArr['txdamCat']->linkRootCat = true;

		$this->content.= $this->browseTrees->getTrees();

		$this->content.= '
			<p class="c-refresh">
				<a href="'.htmlspecialchars(t3lib_div::linkThisScript(array('unique' => uniqid('tx_dam_catedit_navframe')))).'">'.
				'<img'.t3lib_iconWorks::skinImg('',$BACK_PATH.'gfx/refresh_n.gif','width="14" height="14"').' title="'.$LANG->sL('LLL:EXT:lang/locallang_core.xml:labels.refresh',1).'" alt="" />'.
				$LANG->sL('LLL:EXT:lang/locallang_core.xml:labels.refresh',1).'</a>
			</p>
			<br />';

			// Adding highlight - JavaScript
		if ($this->doHighlight)	$this->content .=$this->doc->wrapScriptTags('
			hilight_row("",top.fsMod.navFrameHighlightedID["web"]);
		');
	}


	/**
	 * Outputting the accumulated content to screen
	 *
	 * @return	void
	 */
	function printContent()	{
		$this->content.= $this->doc->endPage();
		echo $this->content;
	}

}

// Include extension?
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dam_catedit/mod1/tx_dam_catedit_navframe.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dam_catedit/mod1/tx_dam_catedit_navframe.php']);
}




// Make instance:

$SOBE = t3lib_div::makeInstance('tx_damcatedit_navframe');
$SOBE->init();
$SOBE->main();
$SOBE->printContent();


?>