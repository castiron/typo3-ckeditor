<?php 

class jsonPageTree extends webPageTree {
	
	function __construct() {
		
	}
	
	function getTree($uid, $depth=999, $blankLineCode='', $subCSSclass='') {

			// Buffer for id hierarchy is reset:
		$this->buffer_idH = array();

			// Init vars
		$depth = intval($depth);
		$HTML = '';
		$a = 0;

		$res = $this->getDataInit($uid, $subCSSclass);
		$c = $this->getDataCount($res);
		$crazyRecursionLimiter = 999;

		$inMenuPages = array();
		$outOfMenuPages = array();
		$outOfMenuPagesTextIndex = array();
		while ($crazyRecursionLimiter > 0 && $row = $this->getDataNext($res,$subCSSclass))	{
			$crazyRecursionLimiter--;

				// Not in menu:
				// @TODO: RFC #7370: doktype 2&5 are deprecated since TYPO3 4.2-beta1
			if ($this->ext_separateNotinmenuPages && (t3lib_div::inList('5,6',$row['doktype']) || $row['doktype']>=200 || $row['nav_hide']))	{
				$outOfMenuPages[] = $row;
				$outOfMenuPagesTextIndex[] = ($row['doktype']>=200 ? 'zzz'.$row['doktype'].'_' : '').$row['title'];
			} else {
				$inMenuPages[] = $row;
			}
		}

		$label_shownAlphabetically = "";
		if (count($outOfMenuPages))	{
				// Sort out-of-menu pages:
			$outOfMenuPages_alphabetic = array();
			if ($this->ext_alphasortNotinmenuPages)	{
				asort($outOfMenuPagesTextIndex);
				$label_shownAlphabetically = " (alphabetic)";
			}
			foreach($outOfMenuPagesTextIndex as $idx => $txt)	{
				$outOfMenuPages_alphabetic[] = $outOfMenuPages[$idx];
			}

				// Merge:
			$outOfMenuPages_alphabetic[0]['_FIRST_NOT_IN_MENU']=TRUE;
			$allRows = array_merge($inMenuPages,$outOfMenuPages_alphabetic);
		} else {
			$allRows = $inMenuPages;
		}

			// Traverse the records:
		foreach ($allRows as $row)	{
			$a++;

			$newID = $row['uid'];
			$this->tree[]=array();	  // Reserve space.
			end($this->tree);
			$treeKey = key($this->tree);	// Get the key for this space
			$LN = ($a==$c) ? 'blank' : 'line';

				// If records should be accumulated, do so
			if ($this->setRecs) { $this->recs[$row['uid']] = $row; }

				// Accumulate the id of the element in the internal arrays
			$this->ids[]=$idH[$row['uid']]['uid'] = $row['uid'];
			$this->ids_hierarchy[$depth][] = $row['uid'];

				// Make a recursive call to the next level
			if ($depth > 1 && $this->expandNext($newID) && !$row['php_tree_stop'])	{
				$nextCount=$this->getTree(
					$newID,
					$depth-1,
					$blankLineCode.','.$LN,
					$row['_SUBCSSCLASS']
				);
				if (count($this->buffer_idH)) { $idH[$row['uid']]['subrow']=$this->buffer_idH; }
				$exp = 1; // Set "did expand" flag
			} else {
				$nextCount = $this->getCount($newID);
				$exp = 0; // Clear "did expand" flag
			}

				// Set HTML-icons, if any:
			if ($this->makeHTML)	{
				if ($row['_FIRST_NOT_IN_MENU'])	{
					$HTML = '<img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/ol/line.gif').' alt="" /><br/><img'.t3lib_iconWorks::skinImg($this->backPath,'gfx/ol/line.gif').' alt="" /><i>Not shown in menu'.$label_shownAlphabetically.':</i><br>';
				} else {
					$HTML = '';
				}

				$HTML.= $this->PMicon($row,$a,$c,$nextCount,$exp);
				$HTML.= $this->wrapStop($this->getIcon($row),$row);
			}

				// Finally, add the row/HTML content to the ->tree array in the reserved key.
			$this->tree[$treeKey] = array(
				'row'    => $row,
				'HTML'   => $HTML,
				'hasSub' => $nextCount, // changed line - ZD CIC
				'isFirst'=> $a==1,
				'isLast' => false,
				'invertedDepth'=> $depth,
				'blankLineCode'=> $blankLineCode,
				'bank' => $this->bank
			);
		}

		if($a) { $this->tree[$treeKey]['isLast'] = true; }

		$this->getDataFree($res);
		$this->buffer_idH = $idH;
		return $c;
	}
}