<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Zachary Davis, Cast Iron Coding <zach@castironcoding.com>
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
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */


//$LANG->includeLLFile('EXT:ckeditor/mod1/locallang.xml');
//require_once(PATH_t3lib . 'class.t3lib_scbase.php');
// how to check access for a psuedo module like this one?
#$BE_USER->modAccess($MCONF,1);	// This checks permissions and exits if the users has no permission for entry.

require_once(PATH_t3lib.'class.t3lib_browsetree.php');
require_once(PATH_typo3.'class.webpagetree.php');
require_once(PATH_t3lib.'class.t3lib_pagetree.php');
require_once(t3lib_div::getFileAbsFileName('EXT:ckeditor/mod1/class.jsonPageTree.php'));
require_once(t3lib_div::getFileAbsFileName('EXT:ckeditor/mod1/class.jsonPageTreeListener.php'));
require_once(t3lib_div::getFileAbsFileName('EXT:ckeditor/mod1/class.damFileBrowse.php'));
require_once(t3lib_div::getFileAbsFileName('EXT:ckeditor/mod1/class.damQuery.php'));

// Make instance:
$action = t3lib_div::_GP('action');
switch ($action) {
	case 'pageTree':
		$obj = new jsonPageTreeListener;
		$obj->init();
	break;
	case 'fileBrowse':
		$obj = new damFileBrowse;
		$obj->init();
	break;
	case 'getDamRecord':
		$obj = new damQuery;
		$obj->init();
	break;
}

?>