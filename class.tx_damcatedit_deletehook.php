<?php
/**
 * ************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Lorenz Ulrich (lorenz.ulrich@visol.ch)
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
 * **************************************************************/

class tx_damcatedit_deletehook {

	/**
	 * Main function. Hook from t3lib/class.t3lib_tcemain.php
	 *
	 * @param	string		$table: the table of the record to delete
	 * @param	integer		$uid: uid of the record to delete
	 * @param	array		$recordToDelete: array of the record to delete
	 * @param	boolean		$recordWasDeleted: was the record already deleted?
	 * @param	object		$pObj: parent object
	 * @return	void
	 */
	function processCmdmap_deleteAction($table, $id, $recordToDelete, $recordWasDeleted, $pObj) {

			// Return if not the tx_dam_cat table
		if ($table != 'tx_dam_cat') return;

			// If not returned, update the dam_catedit tree.
		t3lib_BEfunc::setUpdateSignal('updateFolderTree');

	}


}


?>
