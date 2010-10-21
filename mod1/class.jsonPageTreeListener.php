<?php

class jsonPageTreeListener {

	function getIcon($row) {
		global $PAGES_TYPES;
		if ($row['nav_hide'] && ($row['doktype']==1||$row['doktype']==2)) $row['doktype'] = 5;  // Workaround to change the icon if "Hide in menu" was set
		if (!$iconfile = $PAGES_TYPES[$row['doktype']]['icon']) {
			$iconfile = $PAGES_TYPES['default']['icon'];
		}
		if ($row['module'] && $ICON_TYPES[$row['module']]['icon']) {
			$iconfile = $ICON_TYPES[$row['module']]['icon'];
		}
		if (!strstr($iconfile, '/')) {
			$iconfile = 'gfx/i/'.$iconfile;
		}
		$icon = t3lib_iconWorks::skinImg($GLOBALS['BACK_PATH'],$iconfile,'',1);
		$icon = '/typo3/'.$icon;
		return $icon;
	}
	
	function pathCheck($pid) {
		$obj = new stdClass;
		$pArr = t3lib_BEfunc::BEgetRootLine($pid);
		foreach($pArr as $page) {
			$pids[] = $page['uid'];
		}
		$pids = array_reverse($pids);
		$path = implode('/',$pids);
		$obj->pathString = '/'.$path;
		print json_encode($obj);
		die();	
	}
	
	function init() {		
		
		$pathCheckPid = intval(t3lib_div::_GP('path'));
		if($pathCheckPid) {
			$this->pathCheck($pathCheckPid);
		}
		
		//global $BE_USER,$LANG,$BACK_PATH,$TCA_DESCR,$TCA,$CLIENT,$TYPO3_CONF_VARS;
		$this->tree = new jsonPageTree;
		$this->tree->init();
		$this->tree->addField('alias');
		$this->tree->addField('shortcut');
		$this->tree->addField('shortcut_mode');
		$this->tree->addField('mount_pid');
		$this->tree->addField('mount_pid_ol');
		$this->tree->addField('nav_hide');
		$this->tree->addField('nav_title');
		$this->tree->addField('url');
		
		$nodeUid = intval(t3lib_div::_GP('node'));
		if($nodeUid) {
			$this->tree->getTree($nodeUid);
		} else {
			$this->tree->getTree();
		}

		foreach($this->tree->tree as $element) {
			$rec = $element['row'];
			$icon = $this->getIcon($rec);
			$obj = new stdClass;
			
			if(!$element['hasSub']) $obj->leaf = true;
			$obj->icon = $icon;
			$obj->draggable = false;
			$obj->editable = false;
			$obj->qtip = 'uid: '.$rec['uid'];
			$obj->text = $rec['title'];
			$obj->id = $rec['uid'];
			$obj->cls = 'file';
			$out[] = $obj;
		}
		print json_encode($out);
		die();
	}

}

?>
