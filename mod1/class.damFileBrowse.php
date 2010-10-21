<?php


class damFileBrowse {
	
	function getFullFileName($filename) {
		if (substr($filename,0,4)=='EXT:')      {       // extension
			list($extKey,$local) = explode('/',substr($filename,4),2);
			$newFilename = '';
			if (strcmp($extKey,'') &&  t3lib_extMgm::isLoaded($extKey) && strcmp($local,'')) {
				$newFilename = $this->siteURL . t3lib_extMgm::siteRelPath($extKey) . $local;
			}
		} elseif (substr($filename,0,1) != '/') {
			$newFilename = $this->siteURL . $filename;
		} else {
			$newFilename = $this->siteURL . substr($filename,1);
		}
		return $newFilename;
	}
	
	function init() {
		// Setting GPvars:
		$mode =t3lib_div::_GP('mode');
		$bparams = t3lib_div::_GP('bparams');
		$ckFuncNum = t3lib_div::_GP('CKEditorFuncNum');

		// Set doktype:
		$GLOBALS['TBE_TEMPLATE']->docType='xhtml_frames';
		
#		$path = $this->getFullFileName('EXT:ckeditor/contrib/ckeditor/ckeditor.js');
		$path = $this->getFullFileName('EXT:ckeditor/contrib/ckeditor_svn/ckeditor_source.js');

		$GLOBALS['TBE_TEMPLATE']->JScode = $extJsInclude .= '<script src="' . $this->backPath . 'contrib/extjs/adapter/ext/ext-base.js" type="text/javascript"></script>' . chr(10);
		$GLOBALS['TBE_TEMPLATE']->JScode = $extJsInclude .= '<script src="' . $this->backPath . 'contrib/extjs/ext-all' . ($this->enableExtJsDebug ? '-debug' : '') . '.js" type="text/javascript"></script>' . chr(10);
		$GLOBALS['TBE_TEMPLATE']->JScode.= '<script type="text/javascript" src="/'.$path.'"></script>'."\n";

		// Callback JS
		
		
		$GLOBALS['TBE_TEMPLATE']->JScode.= $GLOBALS['TBE_TEMPLATE']->wrapScriptTags('
				function closing()	{	//
					//close();
				}
				function setParams(mode,params)	{	//
					parent.content.location.href = "browse_links.php?mode="+mode+"&bparams="+params;
				}
				if (!window.opener)	{
					alert("ERROR: Sorry, no link to main window... Closing");
					close();
				}
				window.opener.browserHandler = {
					imagePath: false,
					getDamRecord: function(allowed,table,uid,type) {
						funcNum = '.$ckFuncNum.';
						window.opener.CKEDITOR.tools.callFunction(funcNum, \'media:\'+uid);
					},
					updateParent: function(allowed,table,uid,type) {
					}
				};
		');

		$this->content.=$GLOBALS['TBE_TEMPLATE']->startPage($GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.php:TYPO3_Element_Browser'));
		
		// URL for the inner main frame:
		$url = 'browse_links.php?mode=db&bparams=|||tx_dam|jpg||browserHandler.updateParent|browserHandler.getDamRecord';

		// Create the frameset for the window:
		// Formerly there were a ' onunload="closing();"' in the <frameset> tag - but it failed on Safari browser on Mac unless the handler was "onUnload"
		$this->content.='
			<frameset rows="*,1" framespacing="0" frameborder="0" border="0">
				<frame name="content" src="'.htmlspecialchars($url).'" marginwidth="0" marginheight="0" frameborder="0" scrolling="auto" noresize="noresize" />
				<frame name="menu" src="'.$GLOBALS['BACK_PATH'].'dummy.php" marginwidth="0" marginheight="0" frameborder="0" scrolling="no" noresize="noresize" />
			</frameset>
		';

		$this->content.='
		</html>';
	
		echo $this->content;
#		require_once(PATH_typo3.'class.browse_links.php');
#		require_once(t3lib_extMgm::extPath('dam').'class.tx_dam_browse_media.php');
#		$dam = new tx_dam_browse_media;
#		$dam->initDAM();
#		$dam->initDAMSelection();
#		$files = $dam->getFileListArr(array('gif','jpg','jpeg','tif','bmp','pcx','tga','png','pdf','ai'), array(), 'db');
#		t3lib_div::debug($files);
#		die();

	}

	
}

?>