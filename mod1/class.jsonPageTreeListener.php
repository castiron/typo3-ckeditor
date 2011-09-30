<?php

class jsonPageTreeListener {

	function getIconClass($row) {
		$class = t3lib_iconWorks::getSpriteIconForRecord('pages',$row);
		return $class;
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
			$icon = $this->getIconClass($rec);
			$obj = new stdClass;
			
			if(!$element['hasSub']) $obj->leaf = true;
			$obj->iconClass = $icon;
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
