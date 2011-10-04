<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2005-2007 Zach Davis <zach(at)castironcoding.com>
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
 * Configuration of the wmyeditor RTE extension
 *
 * @author	Zach Davis <zach(at)castironcoding.com>
 *
 */
 
require_once(PATH_t3lib.'class.t3lib_rteapi.php');
require_once(PATH_t3lib.'class.t3lib_cs.php');

class tx_ckeditor_base extends t3lib_rteapi {
		
	var $debug = false;
	var $disableRTE = false;	
	var $debugMessages = array();
	var $parentObj = false;
	var $recordTable = false;
	var $recordField = false;
	var $recordRow = false;
	var $recordPid = false;
	var $fieldConf = false;
	var $specialFieldConf = false;
	var $rteConf = false;
	var $typeValue = false;
	var $rteRelPath = false;
	var $fieldValue = false;
	var $fieldName = false;
	var $editorId = false;
	
	

//	class, blockstylelabel, blockstyle, textstylelabel, textstyle,
//	formatblock, bold, italic, subscript, superscript,
//	orderedlist, unorderedlist, outdent, indent, textindicator,
//	insertcharacter, link, table, findreplace, chMode, removeformat, undo, redo, about,
//	toggleborders, tableproperties,
//	rowproperties, rowinsertabove, rowinsertunder, rowdelete, rowsplit,
//	columninsertbefore, columninsertafter, columndelete, columnsplit,
//	cellproperties, cellinsertbefore, cellinsertafter, celldelete, cellsplit, cellmerge
	
	var $availableButtons = array(
		// Buttons to include
		'Source' => array('avail' => 1,'map' => 'chMode'),
		'Preview' => array('avail' => 1),
		'Cut' => array('avail' => 1,'map' => 'cut'),
		'Copy' => array('avail' => 1,'map' => 'copy'),
		'Paste' => array('avail' => 1,'map' => 'paste'),
		'PasteText' => array('avail' => 1),
		'PasteFromWord' => array('avail' => 1),
		'Undo' => array('avail' => 1,'map' => 'undo'),
		'Redo' => array('avail' => 1,'map' => 'redo'),
		'Find' => array('avail' => 1,'map' => 'findreplace'),
		'Replace' => array('avail' => 1,'map' => 'findreplace'),
		'SelectAll' => array('avail' => 1),
		'RemoveFormat' => array('avail' => 1,'map' => 'removeformat'),
		'Bold' => array('avail' => 1,'map' => 'bold'),
		'Italic' => array('avail' => 1,'map' => 'italic'),
		'Underline' => array('avail' => 1,'map' => 'underline'),
		'Strike' => array('avail' => 1,'map' => 'strikethrough'),
		'Subscript' => array('avail' => 1,'map' => 'subscript'),
		'Superscript' => array('avail' => 1,'map' => 'superscript'),
		'NumberedList' => array('avail' => 1,'map' => 'orderedList'),
		'BulletedList' => array('avail' => 1,'map' => 'unorderedList'),
		'Outdent' => array('avail' => 1,'map' => 'outdent'),
		// 't3ImageBtn' => array(
		// 	'avail' => 1,
		// 	'dialog' => array(
		// 		'key' => 't3Image',
		// 		'file' => 'EXT:ckeditor/lib/js/t3image.js',
		// 		'label' => 'Image',
		// 		'css' => '.cke_button_t3ImageCmd .cke_icon { display: block; background-position: 0 -576px !important;}'
		// 	)
		// ),
		'Indent' => array('avail' => 1,'map' => 'indent'),
		'Blockquote' => array('avail' => 1,'map' => 'blockquote'),
		'JustifyLeft' => array('avail' => 1,'map' => 'left'),
		'JustifyCenter' => array('avail' => 1,'map' => 'center'),
		'JustifyRight' => array('avail' => 1,'map' => 'right'),
		'JustifyBlock' => array('avail' => 1,'map' => 'justifyfull'),
		'Unlink' => array('avail' => 1,'map' => 'unlink'),
		'Image' => array('avail' => 1),
		't3LinkBtn' => array(
			'avail' => 1,
			'map' => 'link',
			'dialog' => array(
				'key' => 't3Link',
				'file' => 'EXT:ckeditor/lib/js/t3link.js',
				'label' => 'Link',
				'css' => '.cke_button_t3LinkCmd .cke_icon { display: block; background-position: 0 -528px !important;}'
			)
		),
		'Table' => array('avail' => 1,'map' => 'table'),
		'HorizontalRule' => array('avail' => 1),
		'SpecialChar' => array('avail' => 1,'map' => 'insertcharacter'),
		'PageBreak' => array('avail' => 1),
		'Styles' => array('avail' => 1,'map' => 'blockstyle,textstyle'),
		'Format' => array('avail' => 1,'map' => 'formatblock'),
		'TextColor' => array('avail' => 1,'map' => 'textcolor'),
		'BGColor' => array('avail' => 1,'map' => 'bgcolor'),
		'ShowBlocks' => array('avail' => 1),
		'About' => array('avail' => 1,'map' => 'about'),
	);
	
	
	function isAvailable()	{
		global $TYPO3_CONF_VARS;
		return true;
	}


	/**
	 * Draws the RTE as an iframe
	 *
	 * @param	object		Reference to parent object, which is an instance of the TCEforms.
	 * @param	string		The table name
	 * @param	string		The field name
	 * @param	array		The current row from which field is being rendered
	 * @param	array		Array of standard content for rendering form fields from TCEforms. See TCEforms for details on this. Includes for instance the value and the form field name, java script actions and more.
	 * @param	array		"special" configuration - what is found at position 4 in the types configuration of a field from record, parsed into an array.
	 * @param	array		Configuration for RTEs; A mix between TSconfig and otherwise. Contains configuration for display, which buttons are enabled, additional transformation information etc.
	 * @param	string		Record "type" field value.
	 * @param	string		Relative path for images/links in RTE; this is used when the RTE edits content from static files where the path of such media has to be transformed forth and back!
	 * @param	integer		PID value of record (true parent page id)
	 * @return	string		HTML code for RTE!
	 */
	function drawRTE(&$pObj,$table,$field,$row,$PA,$specConf,$thisConfig,$RTEtypeVal,$RTErelPath,$thePidValue)	{
		global $BE_USER,$LANG, $TYPO3_DB, $TYPO3_CONF_VARS;
		$this->TCEform = $pObj;
		//$LANG->includeLLFile('EXT:' . $this->ID . '/locallang.xml');
		//$this->client = $this->clientInfo();
		//$this->typoVersion = t3lib_div::int_from_ver(TYPO3_version);
		//$this->userUid = 'BE_' . $BE_USER->user['uid'];
	
		unset($this->RTEsetup);
		$this->RTEsetup = $BE_USER->getTSConfig('RTE',t3lib_BEfunc::getPagesTSconfig($this->tscPID));
		$this->thisConfig = $thisConfig;
        
		if ($this->disableRTE == true) {
			$out = parent::drawRTE($pObj,$table,$field,$row,$PA,$specConf,$thisConfig,$RTEtypeVal,$RTErelPath,$thePidValue);
		} else {
			$this->setProperties($pObj,$table,$field,$row,$PA,$specConf,$thisConfig,$RTEtypeVal,$RTErelPath,$thePidValue);
		    $this->init();
			$out = $this->renderRte();
		}
		return $out;
	}
	
	function setProperties($pObj,$table,$field,$row,$PA,$specConf,$thisConfig,$RTEtypeVal,$RTErelPath,$thePidValue) {
		$this->log('Setting object attributes based on passed values','MESSAGE');
		$this->parentObj = $pObj;
		$this->recordTable = $table;
		$this->recordField = $field;
		$this->recordRow = $row;
		$this->recordPid = $thePidValue;
		$this->fieldConf = $PA;
		$this->specialFieldConf = $specConf;
		$this->rteConf = $thisConfig;
		$this->typeValue = $RTEtypeVal;
		$this->rteRelPath = $RTErelPath;
		$this->fieldValue = $this->fieldConf['itemFormElValue'];
		$this->fieldName = $this->fieldConf['itemFormElName'];
		$this->editorId = 'ckEditor'.$this->parentObj->RTEcounter;
		
		foreach($this->availableButtons as $ckBtnId => $btnConf) {
			if($btnConf['map']) {
				$t = explode(',',$btnConf['map']);
				foreach($t as $oldRteId) {
					$btnMapping[$oldRteId] = $ckBtnId;
				}
			}
		}
		$this->log('Parsing button conf to determine mapping between htmlarea RTE and ckEditor RTE button IDs','MESSAGE');
		$this->btnMapping = $btnMapping;

		
		
	}
	
	function transformContent()	{
		$transformDirection = 'rte'; // this can typically have 2 values, either "db" or "rte" and it specifies the direction of the transformation in RTE_transform

		if ($this->specialFieldConf['rte_transform'])	{
			$p = t3lib_BEfunc::getSpecConfParametersFromArray($this->specialFieldConf['rte_transform']['parameters']);
			if ($p['mode'])	{	// There must be a mode set for transformation
				$parseHTML = t3lib_div::makeInstance('t3lib_parsehtml_proc');
				$parseHTML->init($this->recordTable.':'.$this->recordField, $this->recordPid);
				$parseHTML->setRelPath($this->rteRelPath);

				// Perform transformation:
				$value = $parseHTML->RTE_transform($this->fieldValue, $this->specialFieldConf, $transformDirection, $this->rteConf);
			}
		}

		return $value;
	}
	
	
	
	function init() {
		// transform the raw field to prepare it for insertion in the RTE textarea.
	    $value = $this->transformContent();
		$this->fieldValueTransformed = $value;
		
		// load Javascript and CSS
		$this->includeJsCkeditor();		
		$this->includeJsCkeditorVars();
		$this->includeCssCkeditor();
	    
	}

	function log($v,$l = false) {
		if(is_array($v)) {
			$this->debugMessages[] = $l.'<br />'.t3lib_div::view_array($v);
		} else {
			if($l) {
				$this->debugMessages[] = '<b>'.$l.'</b>: '.$v;
			} else {
				$this->debugMessages[] = $v;
			}
		}
	}

	function renderRte() {
		// begin building the HTML for the textarea and accompanying javascript
		$out2.= $this->renderTextArea($value);

		if($this->debug == true) {
			$out1.= $this->renderDebugArea();
		}

		// render all css and js in the head
		$this->renderHeaderContent();

		$out = $out1.$out2;
		return $out;
	}
		
	function getButtons() {
		
		$tsConfigShowButtons = $this->rteConf['showButtons'];
		$this->log('Requested buttons from tsConfig are: '.$tsConfigShowButtons,'MESSAGE');
		$requestedButtons = t3lib_div::trimExplode(',',$tsConfigShowButtons,true);
		foreach($requestedButtons as $requestedBtnId) {
			// is this a valid ckeditor button?
			if($requestedBtnId == '/' || $requestedBtnId == '-') {
				$renderButtons[] = $requestedBtnId; // if the request is for a space or a line break, allow it
			}elseif($this->availableButtons[$requestedBtnId]['avail']) {
				$renderButtons[$requestedBtnId] = $requestedBtnId; // if the request is for a button in the availableButtons array that is set to "avail", allow it
				$this->includedButtons[$requestedBtnId] = $requestedBtnId;
			}elseif($this->btnMapping[$requestedBtnId] == true && $this->availableButtons[$this->btnMapping[$requestedBtnId]]['avail'] == true) { 
				$renderButtons[$this->btnMapping[$requestedBtnId]] = $this->btnMapping[$requestedBtnId]; // if the request is for an htmlarea button that has mapping in the mapping array and the actual button is "avail", allow it
				$this->log('HTMLArea button was requested and was found in mapping to be ckEditor button "'.$this->btnMapping[$requestedBtnId].'": '.$requestedBtnId,'WARNING');
				$this->includedButtons[$this->btnMapping[$requestedBtnId]] = $this->btnMapping[$requestedBtnId];

			} else {
				$this->log('button was requested but not found among available buttons: '.$requestedBtnId,'ERROR');
			}
		}
		$this->log('After checking button availability and HTMLArea button mapping, we\'ve decided to render these buttons: '.implode(',',$renderButtons),'MESSAGE');
		
		// first pass
		$row = array();
		$rows = array();
		foreach($renderButtons as $k => $v) {
			if($v == '/') {
				$rows[] = $row;
				$row = array();
			} else {
				$row[] = $v;
			}
		}
		$rows[] = $row;

		// second pass
		foreach($rows as $row) {
			$btnsTmp = array();
			foreach($row as $btnId) {
				$btnsTmp[] = '\''.$btnId.'\'';
			}
			$btnsTmp = implode(',',$btnsTmp);
			$btnsStrings[] = "\n".'						['.$btnsTmp.']';
		}


		$btnsStr = implode(",'/',",$btnsStrings);
		return $btnsStr;
	}
		
		
	function renderDebugArea() {
		$this->log('Rendering the debug area','MESSAGE');
		
		$messages = $this->debugMessages;
		foreach($messages as $k => $v) {
			$newStr = ''.sprintf('%03d',$k).': '.$v.'';
			$messages[$k] = $newStr;
		}
		$messages = implode('<br />',$messages);
		$out = '
		<div id="debugLog-'.$this->editorId.'">
			'.$messages.'
		</div>
		<div id="debugPanel-'.$this->editorId.'"></div><br />';

		$jsSnippet = '
		Ext.onReady(function(){
			var p = new Ext.Panel({
				autoWidth: true,
				autoScroll: true,
				height: 250,
				collapsed: true,
				titleCollapse: true,
				collapseFirst: true,
				shadow: true,
				bodyStyle: \'padding: 10px;\',
		        title: \'RTE Debug Panel for #'.$this->editorId.'\',
				collapsible:true,
		        renderTo: \'debugPanel-'.$this->editorId.'\',
		        contentEl: \'debugLog-'.$this->editorId.'\'
		    });
		});
		';
		
		//
		$this->includeJsSnippet($jsSnippet);
		
		return $out;
	}

	function getBtnDialogConf($btnId) {
			if(is_array($this->availableButtons[$btnId]['dialog'])) {
				return $this->availableButtons[$btnId]['dialog'];
			} else {
				return false;
			}
	}
	
	function getDialogsConf() {
		if(!is_array($this->includedButtons)) {
			$this->log('Method getDialogs called before includedButtons property was set. Need to construct the buttons string before rendering dialogs','ERROR');
			return false;
		} 
		foreach($this->includedButtons as $k => $btnId) {
			$conf = $this->getBtnDialogConf($btnId);
			if($conf != false) $dialogsConf[$btnId] = $conf;
		}
		return $dialogsConf;
	}

	function renderDialogsJsAndCss($dialogs) {
		foreach($dialogs as $dialogArr) {
			$key = $dialogArr['key'];
			$cmd = $key.'Cmd';
			$btn = $key.'Btn';
			$lbl = $dialogArr['label'];
			$file = $dialogArr['file'];
			if($dialogArr['css']) {
				$dialogCssIncludes.= "\n".$dialogArr['css'];
			} else {
				$dialogCssIncludes.= '
							.cke_button_'.$key.'Cmd .cke_icon {display: none !important; }
							.cke_button_'.$key.'Cmd .cke_label {display: inline !important; }
				';
			}
		    $path = $this->getFullFileName($file);	
			$dialogJsIncludes.= '
							// INCLUDING DIALOG: '.$key.'
							if (!CKEDITOR.dialog.exists(\''.$key.'\')) {
								CKEDITOR.dialog.add( \''.$key.'\', \'/'.$path.'\' );
							}
							this.addCommand( \''.$cmd.'\', new CKEDITOR.dialogCommand( \''.$key.'\' ) );
							this.ui.addButton( \''.$btn.'\', {
								label : \''.$lbl.'\',
								command : \''.$cmd.'\'
							});
					';
			


		}
		$out['js'] = $dialogJsIncludes;
		$out['css'] = $dialogCssIncludes;

		return $out;
	}

	function makeEditorCssInclude() {
		$css = $this->rteConf['includeCss'];
		$cssTrim = explode("\n",$css);
		foreach($cssTrim as $cssLine) {
			$out.= trim($cssLine)."\n";
		}
		$dirName = PATH_site.'typo3temp/';
		$hash = t3lib_div::shortMD5($css);
		$filename = 'tx_ckeditor_styles_'.$hash.'.css';
		$fullPath = $dirName.$filename;
		if(!file_exists($fullPath)) {
			t3lib_div::writeFileToTypo3tempDir($fullPath,$out);
		}
		#$this->includeStylesheet('/typo3temp/'.$filename);
		return '/typo3temp/'.$filename;
		
	}

	function renderStyles() {
		
		$styleKey = 'default';
		$stylesTsConfig = $this->rteConf['styles.'][$styleKey.'.'];
		foreach($stylesTsConfig as $conf) {
			$ruleBuild = array();
			foreach($conf as $key => $value) {
				if(is_array($value)) {
					$key = substr($key,0,strlen($key)-1);
					$newValueBuild = array();
					foreach($value as $k => $v) {
						$newValueBuild[] = $k.' : '."'".addslashes($v)."'";
					}
					$newValue = '{';
					$newValue.= implode(', ',$newValueBuild);
					$newValue.= '}';
					$value = $newValue;
				} else {
					$value = "'".addslashes($value)."'";
				}
				$ruleBuild[] = $key.' : '.$value;
			}
			$rules[] = '{ '.implode(", ",$ruleBuild).' }';
		}
		$rules = implode(",\n",$rules);
		return $rules;
	}

		
	function renderTextArea($value) {
		$this->log('Rendering the textarea','MESSAGE');
	
		// draw the textarea we'll be replacing
		$out.= '<textarea rows="10" cols="60" class="" id="'.$this->editorId.'" name="'.htmlspecialchars($this->fieldName).'">'.t3lib_div::formatForTextarea($this->fieldValueTransformed).'</textarea>';

		// handle buttons - needs to occur before dialogs, as dialogs are tied to butons
		$btnsStr = $this->getButtons();
		
		// handle dialogs
		$dialogsConf = $this->getDialogsConf();
		$t = $this->renderDialogsJsAndCss($dialogsConf);
		$dialogJs = $t['js'];
		$dialogCss = $t['css'];
		$this->includeCssSnippet($dialogCss);

		// get style options
		$styles = $this->renderStyles();

		// include editor styles
		$styleSheetInclude = $this->makeEditorCssInclude();

		// set the skin
		$skinName = 'cktypo3';
		$skinPath = '/'.$this->getFullFileName('EXT:ckeditor/res/skins/cktypo3/');

		// customConfig : '/".$this->getFullFileName('EXT:ckeditor/lib/js/ckConfig.js')."',

		$out.= "
			<script type=\"text/javascript\">
			/*<![CDATA[*/
				CKEDITOR.addStylesSet( 'styleTest',[
					".$styles."
				]);
			
				var ".$this->editorId." = CKEDITOR.replace('".htmlspecialchars($this->fieldName)."',{
					// TOOLBARS
					toolbar : [ ".$btnsStr." 
					],
					// uiColor : '#F69327',
					contentsCss : '".$styleSheetInclude."',
					skin : '".$skinName.",".$skinPath."',
					stylesCombo_stylesSet : 'styleTest',
					removePlugins : 'link',
					filebrowserBrowseUrl : '/typo3/mod.php?M=web_txckeditorM1&action=fileBrowse',
			        filebrowserWindowWidth : '700',
			        filebrowserWindowHeight : '700',
					on : {
						pluginsLoaded : function(ev) {
							".$dialogJs."
						},
						instanceReady: function(ev) {
							var rules = {
								indent : false,
								breakBeforeOpen : false,
								breakAfterOpen : false,
								breakBeforeClose : false,
								breakAfterClose : false
							};
							// changing how ckEditor writes HTML in order to make this work with standard TYPO3 
							this.dataProcessor.writer.lineBreakChars = '';
							this.dataProcessor.writer.indentationChars = '';
						}
					}
				});
			/*]]>*/	
			</script>
		";

		return $out;	
	}
	
	function includeJsFile($path) {
		$this->log('Including JS File: '.$path,'MESSAGE');
		$hash = md5($path);
		$this->headerContent['jsFiles'][$hash] = $this->getFullFileName($path);
	}
	
	function includeJsSnippet($str) {
		$hash = md5($str);
		$this->headerContent['jsSnippets'][$hash] = '
			<script type="text/javascript">
			'.$str.'
			</script>';
	}
	
	function includeStylesheet($path) {
		$this->log('Including Stylesheet: '.$path,'MESSAGE');
		$hash = md5($path);
		if(substr($path,0,1) != '/') {
			$this->headerContent['cssFiles'][$hash] = $this->getFullFileName($path);		
		} else {
			t3lib_div::debug($path);
			$this->headerContent['cssFiles'][$hash] = $path;		
		}
	}
	
	function includeCssSnippet($str) {
		$hash = md5($str);
		$this->headerContent['cssSnippets'][$hash] = $str;
	}
	
	function renderHeaderContent() {
		foreach($this->headerContent['jsFiles'] as $hash => $path) {
			$GLOBALS['SOBE']->doc->JScodeLibArray[$hash] = '<script type="text/javascript" src="/'.$path.'"></script>';
		}
		foreach($this->headerContent['jsSnippets'] as $hash => $snippet) {
			$GLOBALS['SOBE']->doc->JScodeLibArray[$hash] = $snippet;
		}
		foreach($this->headerContent['cssSnippets'] as $hash => $snippet) {
			$GLOBALS['SOBE']->doc->inDocStylesArray[$hash] = $snippet;
		}
		foreach($this->headerContent['cssFiles'] as $hash => $path) {
			$GLOBALS['SOBE']->doc->JScodeLibArray[$hash] = '<link rel="stylesheet" type="text/css" href="/'.$path.'" />'."\n";
		}
	}
	
	function includeJsCkeditor() {

		$this->includeJsFile('t3lib/js/extjs/components/pagetree/javascript/treeeditor.js');
		$this->includeJsFile('EXT:ckeditor/lib/js/tree/ckTree.js');
		$this->includeJsFile('t3lib/js/extjs/components/pagetree/javascript/filteringtree.js');
		$this->includeJsFile('EXT:ckeditor/lib/js/tree/ckNodeUi.js');
		$this->includeJsFile('t3lib/js/extjs/components/pagetree/javascript/deletiondropzone.js');
		$this->includeJsFile('t3lib/js/extjs/components/pagetree/javascript/toppanel.js');
		$this->includeJsFile('t3lib/js/extjs/components/pagetree/javascript/contextmenu.js');
		$this->includeJsFile('t3lib/js/extjs/components/pagetree/javascript/actions.js');
		$this->includeJsFile('t3lib/js/extjs/components/pagetree/javascript/Ext.ux.state.TreePanel.js');
		$this->includeJsFile('EXT:ckeditor/lib/js/tree/ckApp.js');
		$this->includeJsFile('EXT:ckeditor/contrib/ckeditor/ckeditor.js');


#		$this->includeJsFile('EXT:ckeditor/contrib/extJS/pkgs/pkg-tree.js');
#		$this->includeJsFile('EXT:ckeditor/contrib/extJS/XmlTreeLoader.js');		
	}

	function includeCssCkeditor() {
		$this->includeStylesheet('EXT:t3skin/extjs/xtheme-t3skin.css');
	}
	
	function includeJsCkeditorVars() {
		$this->log('Setting up Ckeditor vars object literal','MESSAGE');
		
		$vars = array(
			'siteName' => $GLOBALS['TYPO3_CONF_VARS']['SYS']['sitename']
		);

		$varsStrings = array();
		foreach($vars as $k => $v) {
			if(is_string($v)) {
				$varsStrings[] = $k.': \''.addslashes($v).'\'';
			} elseif(is_int($v)) {
				$varsStrings[] = $k.': '.$v;
			} elseif(is_bool($v)) {
				$varsStrings[] = $k.': '.$v;
			} else {
				$varsStrings[] = $k.': \''.$v.'\'';
			}
		}

		$varsString = implode(",\n",$varsStrings);
		$txckeditorObjLit = "
			var txckeditor = {
				".$varsString."
			}
		";

		$this->includeJsSnippet($txckeditorObjLit);		
	}
	

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

	


	
	
}

 ?>