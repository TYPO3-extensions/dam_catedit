<?php
/***************************************************************
*  Copyright notice
*
*  (c) 1999-2004 Kasper Skaarhoj (kasper@typo3.com)
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
 * Contains class for creating a position map.
 *
 * $Id: class.tx_damcatedit_positionmap.php,v 1.2 2005/06/16 09:26:55 cvsrene Exp $
 * Revised for TYPO3 3.6 November/2003 by Kasper Skaarhoj
 * XHTML compliant (should be)
 *
 * @author	Kasper Skaarhoj <kasper@typo3.com>
 */
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   90: class localPageTree extends t3lib_treeView
 *  102:     function init($clause='')
 *  134:     function expandNext($id)
 *  151:     function PMicon($row,$a,$c,$nextCount,$exp)
 *  165:     function wrapIcon($icon,$row)
 *  176:     function initializePositionSaving()
 *
 *
 *  194: class tx_damcatedit_positionMap
 *
 *              SECTION: Page position map:
 *  241:     function positionTree($id,$pageinfo,$perms_clause,$R_URI)
 *  355:     function JSimgFunc($prefix='')
 *  385:     function boldTitle($t_code,$dat,$id)
 *  402:     function onClickEvent($pid,$newPagePID)
 *  421:     function insertlabel()
 *  433:     function linkPageTitle($str,$rec)
 *  444:     function checkNewPageInPid($pid)
 *  460:     function getModConfig($pid)
 *  475:     function insertQuadLines($codes,$allBlank=0)
 *
 *              SECTION: Content element positioning:
 *  513:     function printContentElementColumns($pid,$moveUid,$colPosList,$showHidden,$R_URI)
 *  549:     function printRecordMap($lines,$colPosArray)
 *  587:     function wrapColumnHeader($str,$vv)
 *  601:     function insertPositionIcon($row,$vv,$kk,$moveUid,$pid)
 *  618:     function onClickInsertRecord($row,$vv,$moveUid,$pid,$sys_lang=0)
 *  638:     function wrapRecordHeader($str,$row)
 *  648:     function getRecordHeader($row)
 *  661:     function wrapRecordTitle($str,$row)
 *
 * TOTAL FUNCTIONS: 22
 * (This index is automatically created/updated by the script "update-class-index")
 *
 */



require_once (PATH_t3lib.'class.t3lib_treeview.php');

/**
 * Class for generating a page tree.
 *
 * @author	Kasper Skaarhoj <kasper@typo3.com>
 * @coauthor	Rene Fritz <r.fritz@colorcube.de>
 * @see t3lib_treeView, t3lib_browseTree
 * @package TYPO3
 * @subpackage t3lib
 */
class localPageTree extends t3lib_treeView	{
	var $fieldArray = Array('uid','title');
	var $defaultList = 'uid,pid,tstamp,sorting,deleted,crdate,cruser_id';
	var $setRecs = 0;

	/**
	 * Init function
	 * REMEMBER to feed a $clause which will filter out non-readable pages!
	 *
	 * @param	string		Part of where query which will filter out non-readable pages.
	 * @return	void
	 */
	function init($clause='')	{
		global $LANG, $BACK_PATH;

		parent::init(' AND deleted=0 '.$clause, 'tx_dam_cat.sorting,tx_dam_cat.title');


		$this->table='tx_dam_cat';
		$this->fieldArray=array_merge($this->fieldArray,array('hidden','starttime','endtime','fe_group'));
		$this->parentField='parent_id';
		$this->treeName='txdamCat';
		$this->domIdPrefix=$this->treeName;


		$this->title=$LANG->sL('LLL:EXT:dam/lib/locallang.xml:categories',1);

		$this->iconName = 'cat.gif';
		$this->iconPath = $BACK_PATH.PATH_txdam_rel.'i/';
		#$this->clause=' AND deleted=0 ORDER BY sorting,title';
		#$this->fieldArray = Array('uid','title');
		$this->defaultList = 'uid,pid,tstamp,sorting';
		$this->ext_IconMode = '1'; // no context menu on icons


	}

	/**
	 * Determines whether to expand a branch or not.
	 * Here the branch is expanded if the current id matches the global id for the listing/new
	 *
	 * @param	integer		The ID (page id) of the element
	 * @return	boolean		Returns true if the IDs matches
	 */
	function expandNext($id)	{
		return $id==$GLOBALS['SOBE']->id ? 1 : 0;
	}

	/**
	 * Generate the plus/minus icon for the browsable tree.
	 * In this case, there is no plus-minus icon displayed.
	 *
	 * @param	array		record for the entry
	 * @param	integer		The current entry number
	 * @param	integer		The total number of entries. If equal to $a, a 'bottom' element is returned.
	 * @param	integer		The number of sub-elements to the current element.
	 * @param	boolean		The element was expanded to render subelements if this flag is set.
	 * @return	string		Image tag with the plus/minus icon.
	 * @access private
	 * @see t3lib_treeView::PMicon()
	 */
	function PMicon($row,$a,$c,$nextCount,$exp)	{
		$PM = 'join';
		$BTM = ($a==$c)?'bottom':'';
		$icon = '<img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/ol/'.$PM.$BTM.'.gif','width="18" height="16"').' alt="" />';
		return $icon;
	}

	/**
	 * Inserting uid-information in title-text for an icon
	 *
	 * @param	string		Icon image
	 * @param	array		Item row
	 * @return	string		Wrapping icon image.
	 */
	function wrapIcon($icon,$row)	{
		return $this->addTagAttributes($icon,' title="id='.htmlspecialchars($row['uid']).'"');
	}

	/**
	 * Get stored tree structure AND updating it if needed according to incoming PM GET var.
	 * - Here we just set it to nothing since we want to just render the tree, nothing more.
	 *
	 * @return	void
	 * @access private
	 */
	function initializePositionSaving()	{
		$this->stored=array();
	}
}






/**
 * Position map class - generating a page tree / content element list which links for inserting (copy/move) of records.
 * Used for pages / tt_content element wizards of various kinds.
 *
 * @author	Kasper Skaarhoj <kasper@typo3.com>
 * @package TYPO3
 * @subpackage t3lib
 */
class tx_damcatedit_positionMap {

		// EXTERNAL, static:
	var $moveOrCopy='move';
	var $dontPrintPageInsertIcons=0;
	var $backPath='';
	var $depth=2; 		// How deep the position page tree will go.
	var $cur_sys_language;	// Can be set to the sys_language uid to select content elements for.


		// INTERNAL, dynamic:
	var $R_URI='';			// Request uri
	var $elUid='';			// Element id.
	var $moveUid='';		// tt_content element uid to move.

		// Caching arrays:
	var $getModConfigCache=array();
	var $checkNewPageCache=Array();

		// Label keys:
	var $l_insertNewPageHere = 'insertNewPageHere';
	var $l_insertNewRecordHere = 'insertNewRecordHere';

	var $modConfigStr='mod.web_list.newPageWiz';







	/*************************************
	 *
	 * Page position map:
	 *
	 **************************************/

	/**
	 * Creates a "position tree" based on the page tree.
	 * Notice: A class, "localPageTree" must exist and probably it is an extension class of the t3lib_pageTree class. See "db_new.php" in the core for an example.
	 *
	 * @param	integer		Current page id
	 * @param	array		Current page record.
	 * @param	string		Page selection permission clause.
	 * @param	string		Current REQUEST_URI
	 * @return	string		HTML code for the tree.
	 */
	function positionTree($id,$pageinfo,$perms_clause,$R_URI)	{
		global $LANG, $BE_USER, $TYPO3_CONF_VARS;

			// Make page tree object:
		$t3lib_pageTree = t3lib_div::makeInstance('localPageTree');
		$t3lib_pageTree->init(' AND '.$perms_clause);
		$t3lib_pageTree->addField('pid');

			// Initialize variables:
		$this->R_URI = $R_URI;
		$this->elUid = $id;

			// Create page tree, in $this->depth levels.
		$t3lib_pageTree->getTree($pageinfo['pid'], $this->depth);
		if (!$this->dontPrintPageInsertIcons)	$code.=$this->JSimgFunc();

			// Initialize variables:
		$saveBlankLineState=array();
		$saveLatestUid=array();
		$latestInvDepth=$this->depth;

			// Traverse the tree:
		foreach($t3lib_pageTree->tree as $cc => $dat)	{

				// Make link + parameters.
			$latestInvDepth=$dat['invertedDepth'];
			$saveLatestUid[$latestInvDepth]=$dat;
			if (isset($t3lib_pageTree->tree[$cc-1]))	{
				$prev_dat = $t3lib_pageTree->tree[$cc-1];

					// If current page, subpage?
				if ($prev_dat['row']['uid']==$id)	{
					if (!$this->dontPrintPageInsertIcons && $this->checkNewPageInPid($id) && !($prev_dat['invertedDepth']>$t3lib_pageTree->tree[$cc]['invertedDepth']))	{	// 1) It must be allowed to create a new page and 2) If there are subpages there is no need to render a subpage icon here - it'll be done over the subpages...
						$code.='<span class="nobr">'.
							$this->insertQuadLines($dat['blankLineCode']).
							'<img src="clear.gif" width="18" height="8" align="top" alt="" />'.
							'<a href="#" onclick="'.htmlspecialchars($this->onClickEvent($id,$id,1)).'" onmouseover="'.htmlspecialchars('changeImg(\'mImgSubpage'.$cc.'\',0);').'" onmouseout="'.htmlspecialchars('changeImg(\'mImgSubpage'.$cc.'\',1);').'">'.
							'<img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/newrecord_marker_d.gif','width="281" height="8"').' name="mImgSubpage'.$cc.'" border="0" align="top" title="'.$this->insertlabel().'" alt="" />'.
							'</a></span><br />';
					}
				}

				if ($prev_dat['invertedDepth']>$t3lib_pageTree->tree[$cc]['invertedDepth'])	{	// If going down
					$prevPid = $t3lib_pageTree->tree[$cc]['row']['pid'];
				} elseif ($prev_dat['invertedDepth']<$t3lib_pageTree->tree[$cc]['invertedDepth'])	{		// If going up
					// First of all the previous level should have an icon:
					if (!$this->dontPrintPageInsertIcons && $this->checkNewPageInPid($prev_dat['row']['pid']))	{
						$prevPid = (-$prev_dat['row']['uid']);
						$code.='<span class="nobr">'.
							$this->insertQuadLines($dat['blankLineCode']).
							'<img src="clear.gif" width="18" height="1" align="top" alt="" />'.
							'<a href="#" onclick="'.htmlspecialchars($this->onClickEvent($prevPid,$prev_dat['row']['pid'],2)).'" onmouseover="'.htmlspecialchars('changeImg(\'mImgAfter'.$cc.'\',0);').'" onmouseout="'.htmlspecialchars('changeImg(\'mImgAfter'.$cc.'\',1);').'">'.
							'<img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/newrecord_marker_d.gif','width="281" height="8"').' name="mImgAfter'.$cc.'" border="0" align="top" title="'.$this->insertlabel().'" alt="" />'.
							'</a></span><br />';
					}

					// Then set the current prevPid
					$prevPid = -$prev_dat['row']['pid'];
				} else {
					$prevPid = -$prev_dat['row']['uid'];	// In on the same level
				}
			} else {
				$prevPid = $dat['row']['pid'];	// First in the tree
			}
			if (!$this->dontPrintPageInsertIcons && $this->checkNewPageInPid($dat['row']['pid']))	{
				$code.='<span class="nobr">'.
					$this->insertQuadLines($dat['blankLineCode']).
					'<a href="#" onclick="'.htmlspecialchars($this->onClickEvent($prevPid,$dat['row']['pid'],3)).'" onmouseover="'.htmlspecialchars('changeImg(\'mImg'.$cc.'\',0);').'" onmouseout="'.htmlspecialchars('changeImg(\'mImg'.$cc.'\',1);').'">'.
					'<img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/newrecord_marker_d.gif','width="281" height="8"').' name="mImg'.$cc.'" border="0" align="top" title="'.$this->insertlabel().'" alt="" />'.
					'</a></span><br />';
			}

				// The line with the icon and title:
			$t_code='<span class="nobr">'.
				$dat['HTML'].
				$this->linkPageTitle($this->boldTitle(htmlspecialchars(t3lib_div::fixed_lgd_cs($dat['row']['title'],$BE_USER->uc['titleLen'])),$dat,$id),$dat['row']).
				'</span><br />';
			$code.=$t_code;
		}

			// If the current page was the last in the tree:
		$prev_dat = end($t3lib_pageTree->tree);
		if ($prev_dat['row']['uid']==$id)	{
			if (!$this->dontPrintPageInsertIcons && $this->checkNewPageInPid($id))	{
				$code.='<span class="nobr">'.
					$this->insertQuadLines($saveLatestUid[$latestInvDepth]['blankLineCode'],1).
					'<img src="clear.gif" width="18" height="8" align="top" alt="" />'.
					'<a href="#" onclick="'.$this->onClickEvent($id,$id,4).'" onmouseover="'.htmlspecialchars('changeImg(\'mImgSubpage'.$cc.'\',0);').'" onmouseout="'.htmlspecialchars('changeImg(\'mImgSubpage'.$cc.'\',1);').'">'.
					'<img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/newrecord_marker_d.gif','width="281" height="8"').' name="mImgSubpage'.$cc.'" border="0" align="top" title="'.$this->insertlabel().'" alt="" />'.
					'</a></span><br />';
			}
		}

		for ($a=$latestInvDepth;$a<=$this->depth;$a++)	{
			$dat = $saveLatestUid[$a];
			$prevPid = (-$dat['row']['uid']);
			if (!$this->dontPrintPageInsertIcons && $this->checkNewPageInPid($dat['row']['pid']))	{
				$code.='<span class="nobr">'.
					$this->insertQuadLines($dat['blankLineCode'],1).
					'<a href="#" onclick="'.htmlspecialchars($this->onClickEvent($prevPid,$dat['row']['pid'],5)).'" onmouseover="'.htmlspecialchars('changeImg(\'mImgEnd'.$a.'\',0);').'" onmouseout="'.htmlspecialchars('changeImg(\'mImgEnd'.$a.'\',1);').'">'.
					'<img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/newrecord_marker_d.gif','width="281" height="8"').' name="mImgEnd'.$a.'" border="0" align="top" title="'.$this->insertlabel().'" alt="" />'.
					'</a></span><br />';
			}
		}

		return $code;
	}

	/**
	 * Creates the JavaScritp for insert new-record rollover image
	 *
	 * @param	string		Insert record image prefix.
	 * @return	string		<script> section
	 */
	function JSimgFunc($prefix='')	{
		$code.=$GLOBALS['TBE_TEMPLATE']->wrapScriptTags('

			var img_newrecord_marker=new Image();
			img_newrecord_marker.src = "'.t3lib_iconWorks::skinImg($this->backPath,'gfx/newrecord'.$prefix.'_marker.gif','',1).'";

			var img_newrecord_marker_d=new Image();
			img_newrecord_marker_d.src = "'.t3lib_iconWorks::skinImg($this->backPath,'gfx/newrecord'.$prefix.'_marker_d.gif','',1).'";

			function changeImg(name,d)	{	//
				if (document[name]) {
					if (d)	{
						document[name].src = img_newrecord_marker_d.src;
					} else {
						document[name].src = img_newrecord_marker.src;
					}
				}
			}
		');
		return $code;
	}

	/**
	 * Wrap $t_code in bold IF the $dat uid matches $id
	 *
	 * @param	string		Title string
	 * @param	array		Infomation array with record array inside.
	 * @param	integer		The current id.
	 * @return	string		The title string.
	 */
	function boldTitle($t_code,$dat,$id)	{
		if ($dat['row']['uid']==$id)	{
			$t_code='<strong>'.$t_code.'</strong>';
		}
		return $t_code;
	}

	/**
	 * Creates the onclick event for the insert-icons.
	 *
	 * TSconfig mod.web_list.newPageWiz.overrideWithExtension may contain an extension which provides a module
	 * to be used instead of the normal create new page wizard.
	 *
	 * @param	integer		The pid.
	 * @param	integer		New page id.
	 * @return	string		Onclick attribute content
	 */
	function onClickEvent($pid,$newPagePID)	{
		$TSconfigProp = $this->getModConfig($newPagePID);

		if ($TSconfigProp['overrideWithExtension'])	{
			if (t3lib_extMgm::isLoaded($TSconfigProp['overrideWithExtension']))	{
				$onclick = "document.location='".t3lib_extMgm::extRelPath($TSconfigProp['overrideWithExtension']).'mod1/index.php?cmd=crPage&positionPid='.$pid."';";
				return $onclick;
			}
		}

		$params='&edit[pages]['.$pid.']=new&returnNewPageId=1';
		return t3lib_BEfunc::editOnClick($params,'',$this->R_URI);
	}

	/**
	 * Get label, htmlspecialchars()'ed
	 *
	 * @return	string		The localized label for "insert new page here"
	 */
	function insertlabel()	{
		global $LANG;
		return $LANG->getLL($this->l_insertNewPageHere,1);
	}

	/**
	 * Wrapping page title.
	 *
	 * @param	string		Page title.
	 * @param	array		Page record (?)
	 * @return	string		Wrapped title.
	 */
	function linkPageTitle($str,$rec)	{
		return $str;
	}

	/**
	 * Checks if the user has permission to created pages inside of the $pid page.
	 * Uses caching so only one regular lookup is made - hence you can call the function multiple times without worrying about performance.
	 *
	 * @param	integer		Page id for which to test.
	 * @return	boolean
	 */
	function checkNewPageInPid($pid)	{
		global $BE_USER;
		if (!isset($this->checkNewPageCache[$pid]))	{
			$pidInfo = t3lib_BEfunc::getRecord('pages',$pid);
			$this->checkNewPageCache[$pid] = ($BE_USER->isAdmin() || $BE_USER->doesUserHaveAccess($pidInfo,8));
		}
		return $this->checkNewPageCache[$pid];
	}

	/**
	 * Returns module configuration for a pid.
	 *
	 * @param	integer		Page id for which to get the module configuration.
	 * @return	array		The properties of teh module configuration for the page id.
	 * @see onClickEvent()
	 */
	function getModConfig($pid)	{
		if (!isset($this->getModConfigCache[$pid]))	{
				// Acquiring TSconfig for this PID:
			$this->getModConfigCache[$pid] = t3lib_BEfunc::getModTSconfig($pid,$this->modConfigStr);
		}
		return $this->getModConfigCache[$pid]['properties'];
	}

	/**
	 * Insert half/quad lines.
	 *
	 * @param	string		keywords for which lines to insert.
	 * @param	boolean		If true all lines are just blank clear.gifs
	 * @return	string		HTML content.
	 */
	function insertQuadLines($codes,$allBlank=0)	{
		$codeA = t3lib_div::trimExplode(',',$codes.",line",1);

		$lines=array();
		while(list(,$code)=each($codeA))	{
			if ($code=="blank" || $allBlank)	{
				$lines[]='<img src="clear.gif" width="18" height="8" align="top" alt="" />';
			} else {
				$lines[]='<img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/ol/halfline.gif','width="18" height="8"').' align="top" alt="" />';
			}
		}
		return implode('',$lines);
	}









	/*************************************
	 *
	 * Content element positioning:
	 *
	 **************************************/

	/**
	 * Creates HTML for inserting/moving content elements.
	 *
	 * @param	integer		page id onto which to insert content element.
	 * @param	integer		Move-uid (tt_content element uid?)
	 * @param	string		List of columns to show
	 * @param	boolean		If not set, then hidden/starttime/endtime records are filtered out.
	 * @param	string		Request URI
	 * @return	string		HTML
	 */
	function printContentElementColumns($pid,$moveUid,$colPosList,$showHidden,$R_URI)	{
		$this->R_URI = $R_URI;
		$this->moveUid = $moveUid;
		$colPosArray = t3lib_div::trimExplode(',',$colPosList,1);

		$lines=array();
		while(list($kk,$vv)=each($colPosArray))	{
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
							'*',
							'tt_content',
							'pid='.intval($pid).
								($showHidden ? '' : t3lib_BEfunc::BEenableFields('tt_content')).
								' AND colPos='.intval($vv).
								(strcmp($this->cur_sys_language,'') ? ' AND sys_language_uid='.intval($this->cur_sys_language) : '').
								t3lib_BEfunc::deleteClause('tt_content'),
							'',
							'sorting'
						);
			$lines[$kk]=array();
			$lines[$kk][]=$this->insertPositionIcon('',$vv,$kk,$moveUid,$pid);
			while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res))		{
				$lines[$kk][]=$this->wrapRecordHeader($this->getRecordHeader($row),$row);
				$lines[$kk][]=$this->insertPositionIcon($row,$vv,$kk,$moveUid,$pid);
			}
			$GLOBALS['TYPO3_DB']->sql_free_result($res);
		}
		return $this->printRecordMap($lines,$colPosArray);
	}

	/**
	 * Creates the table with the content columns
	 *
	 * @param	array		Array with arrays of lines for each column
	 * @param	array		Column position array
	 * @return	string		HTML
	 */
	function printRecordMap($lines,$colPosArray)	{
		$row1='';
		$row2='';
		$count = tx_dam::forceIntegerInRange(count($colPosArray),1);

			// Traverse the columns here:
		foreach($colPosArray as $kk => $vv)	{
			$row1.='<td align="center" width="'.round(100/$count).'%"><span class="uppercase"><strong>'.
					$this->wrapColumnHeader($GLOBALS['LANG']->sL(t3lib_BEfunc::getLabelFromItemlist('tt_content','colPos',$vv),1),$vv).
					'</strong></span></td>';
			$row2.='<td valign="top" nowrap="nowrap">'.
					implode('<br />',$lines[$kk]).
					'</td>';
		}

		$table = '

		<!--
			Map of records in columns:
		-->
		<table border="0" cellpadding="0" cellspacing="1" id="typo3-ttContentList">
			<tr class="bgColor5">'.$row1.'</tr>
			<tr>'.$row2.'</tr>
		</table>

		';

		return $this->JSimgFunc('2').$table;
	}

	/**
	 * Wrapping the column header
	 *
	 * @param	string		Header value
	 * @param	string		Column info.
	 * @return	string
	 * @see printRecordMap()
	 */
	function wrapColumnHeader($str,$vv)	{
		return $str;
	}

	/**
	 * Creates a linked position icon.
	 *
	 * @param	array		Element row.
	 * @param	string		Column position value.
	 * @param	integer		Column key.
	 * @param	integer		Move uid
	 * @param	integer		PID value.
	 * @return	string
	 */
	function insertPositionIcon($row,$vv,$kk,$moveUid,$pid)	{
		$cc = hexdec(substr(md5($row['uid'].'-'.$vv.'-'.$kk),0,4));
		return '<a href="#" onclick="'.htmlspecialchars($this->onClickInsertRecord($row,$vv,$moveUid,$pid,$this->cur_sys_language)).'" onmouseover="'.htmlspecialchars('changeImg(\'mImg'.$cc.'\',0);').'" onmouseout="'.htmlspecialchars('changeImg(\'mImg'.$cc.'\',1);').'">'.
			'<img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/newrecord2_marker_d.gif','width="100" height="8"').' name="mImg'.$cc.'" border="0" align="top" title="'.$GLOBALS['LANG']->getLL($this->l_insertNewRecordHere,1).'" alt="" />'.
			'</a>';
	}

	/**
	 * Create on-click event value.
	 *
	 * @param	array		The record.
	 * @param	string		Column position value.
	 * @param	integer		Move uid
	 * @param	integer		PID value.
	 * @param	integer		System language (not used currently)
	 * @return	string
	 */
	function onClickInsertRecord($row,$vv,$moveUid,$pid,$sys_lang=0) {
		$table='tt_content';
		if (is_array($row))	{
			$location='tce_db.php?cmd['.$table.']['.$moveUid.']['.$this->moveOrCopy.']=-'.$row['uid'].'&prErr=1&uPT=1&vC='.$GLOBALS['BE_USER']->veriCode();
		} else {
			$location='tce_db.php?cmd['.$table.']['.$moveUid.']['.$this->moveOrCopy.']='.$pid.'&data['.$table.']['.$moveUid.'][colPos]='.$vv.'&prErr=1&vC='.$GLOBALS['BE_USER']->veriCode();
		}
//		$location.='&redirect='.rawurlencode($this->R_URI);		// returns to prev. page
		$location.='&uPT=1&redirect='.rawurlencode(t3lib_div::getIndpEnv('REQUEST_URI'));		// This redraws screen

		return 'document.location=\''.$location.'\';return false;';
	}

	/**
	 * Wrapping the record header  (from getRecordHeader())
	 *
	 * @param	string		HTML content
	 * @param	array		Record array.
	 * @return	string		HTML content
	 */
	function wrapRecordHeader($str,$row)	{
		return $str;
	}

	/**
	 * Create record header (includes teh record icon, record title etc.)
	 *
	 * @param	array		Record row.
	 * @return	string		HTML
	 */
	function getRecordHeader($row)	{
		$line = t3lib_iconWorks::getIconImage('tt_content',$row,$this->backPath,' align="top" title="'.htmlspecialchars(t3lib_BEfunc::getRecordIconAltText($row,'tt_content')).'"');
		$line.= t3lib_BEfunc::getRecordTitle('tt_content',$row,1);
		return $this->wrapRecordTitle($line,$row);
	}

	/**
	 * Wrapping the title of the record.
	 *
	 * @param	string		The title value.
	 * @param	array		The record row.
	 * @return	string		Wrapped title string.
	 */
	function wrapRecordTitle($str,$row)	{
		return '<a href="'.htmlspecialchars(t3lib_div::linkThisScript(array('uid'=>intval($row['uid']),'moveUid'=>''))).'">'.$str.'</a>';
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dam_catedit/class.tx_damcatedit_positionmap.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/dam_catedit/class.tx_damcatedit_positionmap.php']);
}
?>