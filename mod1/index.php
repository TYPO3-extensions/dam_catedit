<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2004-2006 Rene Fritz (r.fritz@colorcube.de)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
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
 * Module 'Categories' for the 'dam_catedit' extension.
 *
 * @author	Rene Fritz <r.fritz@colorcube.de>
 */



	// DEFAULT initialization of a module [BEGIN]
unset($MCONF);
require ('conf.php');
require ($BACK_PATH.'init.php');
require ($BACK_PATH.'template.php');
$LANG->includeLLFile('EXT:dam_catedit/mod1/locallang.xml');

$BE_USER->modAccess($MCONF,1);	// This checks permissions and exits if the users has no permission for entry.
	// DEFAULT initialization of a module [END]



require_once (PATH_t3lib.'class.t3lib_scbase.php');

require_once(PATH_txdam.'lib/class.tx_dam_scbase.php');

$LANG->includeLLFile('EXT:lang/locallang_mod_web_list.xml');

#require_once(t3lib_extmgm::extPath('dam_catedit').'class.tx_dam_db_list.php');
require_once(t3lib_extmgm::extPath('dam_catedit').'class.tx_dam_db_list2.php');
require_once(PATH_txdam.'lib/class.tx_dam_sysfolder.php');
require_once(t3lib_extmgm::extPath('dam_catedit').'lib/class.tx_damcatedit_db.php');



class tx_damcatedit_module1 extends tx_dam_SCbase {

	/**
	 * Main function of the module. Write the content to $this->content
	 */
	function main()	{
		global $BE_USER,$LANG,$BACK_PATH,$TCA,$TYPO3_CONF_VARS;


		$this->defaultPid = tx_dam_db::getPid();
		$this->id = $this->id ? $this->id : $this->defaultPid;


		// Access check!
		// The page will show only if there is a valid page and if this page may be viewed by the user
		$this->pageinfo = t3lib_BEfunc::readPageAccess($this->id,$this->perms_clause);
		$access = is_array($this->pageinfo) ? 1 : 0;

		if (($this->id && $access) || ($BE_USER->user['admin'] && !$this->id))	{

				// Draw the header.
			$this->doc = t3lib_div::makeInstance('mediumDoc');
			$this->doc->backPath = $BACK_PATH;
			$this->doc->form='<form action="" method="post">';

				// JavaScript
			$this->doc->JScode = '
				<script language="javascript" type="text/javascript">
					script_ended = 0;
					function jumpToUrl(URL)	{
						document.location = URL;
					}
				</script>
			';
			$this->doc->postCode='
				<script language="javascript" type="text/javascript">
					script_ended = 1;
				</script>
			';


				// should be done on changes only, but that's not possible (tce_db.php react on 'pages' only)
			t3lib_BEfunc::getSetUpdateSignal('updatePageTree');

			###$headerSection = $this->doc->getHeader('pages',$this->pageinfo,$this->pageinfo['_thePath']).'<br>'.$LANG->sL('LLL:EXT:lang/locallang_core.xml:labels.path',1).': '.t3lib_div::fixed_lgd_pre($this->pageinfo['_thePath'],50);

			$this->content.=$this->doc->startPage($LANG->getLL('title'));
			$this->content.=$this->doc->header($LANG->getLL('title'));
			$this->content.=$this->doc->spacer(5);
			###$this->content.=$this->doc->section('',$this->doc->funcMenu($headerSection,t3lib_BEfunc::getFuncMenu($this->id,'SET[function]',$this->MOD_SETTINGS['function'],$this->MOD_MENU['function'])));
			###$this->content.=$this->doc->divider(5);


			// Render content:
			$this->moduleContent();


			// ShortCut
			if ($BE_USER->mayMakeShortcut())	{
				$this->content.=$this->doc->spacer(20).$this->doc->section('',$this->doc->makeShortcutIcon('id',implode(',',array_keys($this->MOD_MENU)),$this->MCONF['name']));
			}

			$this->content.=$this->doc->spacer(10);
		} else {
				// If no access or if ID == zero

			$this->doc = t3lib_div::makeInstance('mediumDoc');
			$this->doc->backPath = $BACK_PATH;

			$this->content.=$this->doc->startPage($LANG->getLL('title'));
			$this->content.=$this->doc->header($LANG->getLL('title'));
			$this->content.=$this->doc->spacer(5);
			$this->content.=$this->doc->spacer(10);
		}
	}

	/**
	 * Prints out the module HTML
	 */
	function printContent()	{

		$this->content.=$this->doc->endPage();
		$this->content = $this->doc->insertStylesAndJS($this->content);
		echo $this->content;
	}

	/**
	 * Generates the module content
	 */
	function moduleContent()	{
		global $BE_USER,$LANG,$BACK_PATH,$TCA,$TYPO3_CONF_VARS;

//		$content='This is the GET/POST vars sent to the script:<BR>'.
//			'GET:'.t3lib_div::view_array($GLOBALS['HTTP_GET_VARS']).'<BR>'.
//			'POST:'.t3lib_div::view_array($GLOBALS['HTTP_POST_VARS']).'<BR>'.
//			'';
//		$content=$this->doc->section('Message #1:',$content,0,1);

		$cmd = t3lib_div::GParrayMerged('SLCMD');

		if (is_array($cmd['SELECT']['txdamCat'])) {
			$uid = intval(key($cmd['SELECT']['txdamCat']));
		}


			$treedb = t3lib_div::makeInstance('tx_damcatedit_db');
			$treedb->init('tx_dam_cat', 'parent_id');
			$treedb->setPidList($this->id);

			$this->selection->pointer->setTotalCount($treedb->countSubRecords($uid));

			if($this->selection->pointer->countTotal) {


				$dblist = t3lib_div::makeInstance('tx_dam_db_list');
				$dblist->init('tx_dam_cat');

				$dblist->backPath = $BACK_PATH;
				$dblist->returnURL = t3lib_div::linkThisScript(array('SLCMD[SELECT][txdamCat]['.$uid.']'=>'1'));
				$dblist->staticParams = '&SLCMD[SELECT][txdamCat]['.$uid.']=1';
				$dblist->calcPerms = $BE_USER->calcPerms($this->pageinfo);
				$dblist->alternateBgColors=$this->modTSconfig['properties']['alternateBgColors']?1:0;
				$dblist->thumbs = false;

				$dblist->pointer = $this->selection->pointer;

				$dblist->searchString = trim(t3lib_div::_GP('search_field'));
				$dblist->sortField = t3lib_div::_GP('sortField');
				$dblist->sortRev = t3lib_div::_GP('sortRev');


				$dblist->setDispFields();
				#		$fieldList	= 'tx_dam_cat.'.implode(',tx_dam_cat.',t3lib_div::trimExplode(',',$dblist->setFields['tx_dam_cat'],1));
				#		$this->selection->qg->query['FROM']['tx_dam_cat']=$fieldList;

				$orderBy = ($TCA['tx_dam_cat']['ctrl']['sortby']) ? 'tx_dam_cat.'.$TCA['tx_dam_cat']['ctrl']['sortby'] : 'tx_dam_cat.sorting';

				if ($dblist->sortField)	{
					if (in_array($dblist->sortField,$dblist->makeFieldList('tx_dam_cat',1)))	{
						$orderBy = 'tx_dam_cat.'.$dblist->sortField;
						if ($dblist->sortRev)	$orderBy.=' DESC';
					}
				}

				$treedb->setResReturn(true);
				$treedb->setSortFields($orderBy);
				$dblist->res = $treedb->getSubRecords($uid, 'tx_dam_cat.*');



	#TODO ???				// It is set, if the clickmenu-layer is active AND the extended view is not enabled.
#				$dblist->dontShowClipControlPanels = $CLIENT['FORMSTYLE'] && !$BE_USER->uc['disableCMlayers'];

				$dblist->generateList();


					// JavaScript
				$this->doc->JScodeArray['redirectUrls'] = $this->doc->redirectUrls(t3lib_div::getIndpEnv('REQUEST_URI'));
				$this->doc->JScodeArray['jumpExt'] = '
					function jumpExt(URL,anchor)	{
						var anc = anchor?anchor:"";
						document.location = URL+(T3_THIS_LOCATION?"&returnUrl="+T3_THIS_LOCATION:"")+anc;
					}
					';


				$content.= '<form action="'.$dblist->listURL().'" method="post" name="dblistForm">';
				$content.= $dblist->HTMLcode;
				$content.= '<input type="hidden" name="cmd_table"><input type="hidden" name="cmd"></form>';
				$content.= $dblist->fieldSelectBox();
			}

		$this->content.= $content;
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dam_catedit/mod1/index.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dam_catedit/mod1/index.php']);
}




// Make instance:
$SOBE = t3lib_div::makeInstance('tx_damcatedit_module1');
$SOBE->init();

// Include files?
foreach($SOBE->include_once as $INC_FILE)	include_once($INC_FILE);

$SOBE->main();
$SOBE->printContent();

?>