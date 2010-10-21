<?php

class damQuery {
	
	function init() {
		$damUid = intVal(t3lib_div::_GP('damRecUid'));
		$fullPath = $this->getDamPathById($damUid);
		$out = new stdClass;
		$out->pathString = $fullPath;
		print json_encode($out);
		die();
		
	}

	function getDamPathById($damUid) {
		$select = 'CONCAT(file_path,file_name) as full_path';
		$table = 'tx_dam';
		$where = 'UID = '.$GLOBALS['TYPO3_DB']->fullQuoteStr($damUid,$table);
		$limit = '1';

		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select,$table,$where,$groupBy,$orderBy,$limit);
		$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		return $row['full_path'];
	}
	
}

?>