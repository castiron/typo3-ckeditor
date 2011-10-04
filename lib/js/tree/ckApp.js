/***************************************************************
*  Copyright notice
*
*  (c) 2010-2011 Stefan Galinski <stefan.galinski@gmail.com>
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
Ext.namespace('TYPO3.Components.PageTree');

/**
 * @class TYPO3.Components.PageTree.CkApp
 *
 * Page tree main application that controls setups the components
 *
 * @namespace TYPO3.Components.PageTree
 * @extends Ext.Panel
 * @author Stefan Galinski <stefan.galinski@gmail.com>
 */
TYPO3.Components.PageTree.CkApp = Ext.extend(Ext.Panel, {
	/**
	 * Panel id
	 *
	 * @type {String}
	 */
	id: 'typo3-pagetree',

	/**
	 * Border
	 *
	 * @type {Boolean}
	 */
	border: false,

	/**
	 * Layout Type
	 *
	 * @type {String}
	 */
	layout:'fit',

	/**
	 * Active tree
	 *
	 * @type {TYPO3.Components.PageTree.CkTree}
	 */
	activeTree: null,

	/**
	 * Initializes the application
	 *
	 * Set's the necessary language labels, configuration options and sprite icons by an
	 * external call and initializes the needed components.
	 *
	 * @return {void}
	 */
	initComponent: function() {
		TYPO3.Components.PageTree.DataProvider.loadResources(function(response) {
			TYPO3.Components.PageTree.LLL = response['LLL'];
			TYPO3.Components.PageTree.Configuration = response['Configuration'];
			TYPO3.Components.PageTree.Sprites = response['Sprites'];

			var tree = this.activeTree = new TYPO3.Components.PageTree.CkTree({
				id: 'typo3-pagetree',
				stateful: true,
				stateId: 'Pagetree' + TYPO3.Components.PageTree.Configuration.temporaryMountPoint,
				stateEvents: [],
				autoScroll: true,
				autoHeight: false,
				plugins: new Ext.ux.state.TreePanel(),
				treeDataProvider: TYPO3.Components.PageTree.DataProvider,
				app: this,
			});

			this.add({
				layout: 'border',
				items: [
					{
						border: false,
						id: this.id + '-treeContainer',
						region: 'center',
						layout: 'fit',
						items: [tree]
					}
				]
			});

			if (TYPO3.Components.PageTree.Configuration.temporaryMountPoint) {
				topPanelItems.on('afterrender', function() {
					this.addTemporaryMountPointIndicator();
				}, this);
			}

			if (TYPO3.Components.PageTree.Configuration.indicator !== '') {
				this.addIndicatorItems();
			}
			this.doLayout();

		}, this);

		TYPO3.Components.PageTree.CkApp.superclass.initComponent.apply(this, arguments);
	},
	
	/**
	 * TODO: Document
	 *
	 */
	getSelectedPid: function() {
		node = this.activeTree.getSelectionModel().getSelectedNode();
		if(node) {
			pid = node.attributes.realId;
			return pid;
		}
	},

	/**
	 * Adds the default indicator items
	 *
	 * @return {void}
	 */
	addIndicatorItems: function() {
		this.addIndicator({
			border: false,
			id: this.id + '-indicatorBar-indicatorTitle',
			cls: this.id + '-indicatorBar-item',
			html: TYPO3.Components.PageTree.Configuration.indicator
		});
	},

	/**
	 * Adds the temporary mount point indicator item
	 *
	 * @return {void}
	 */
	addTemporaryMountPointIndicator: function() {
		this.temporaryMountPointInfoIndicator = this.addIndicator({
			border: false,
			id: this.id + '-indicatorBar-temporaryMountPoint',
			cls: this.id + '-indicatorBar-item',

			listeners: {
				afterrender: {
					fn: function() {
						var element = Ext.fly(this.id + '-indicatorBar-temporaryMountPoint-clear');
						element.on('click', function() {
							TYPO3.BackendUserSettings.ExtDirect.unsetKey(
								'pageTree_temporaryMountPoint',
								function() {
									TYPO3.Components.PageTree.Configuration.temporaryMountPoint = null;
									this.removeIndicator(this.temporaryMountPointInfoIndicator);
									this.getTree().refreshTree();
									this.getTree().stateId = 'Pagetree';
								},
								this
							);
						}, this);
					},
					scope: this
				}
			},

			html: '<p>' +
					'<span id="' + this.id + '-indicatorBar-temporaryMountPoint-info' + '" ' +
						'class="' + this.id + '-indicatorBar-item-leftIcon ' +
							TYPO3.Components.PageTree.Sprites.Info + '">&nbsp;' +
					'</span>' +
					'<span id="' + this.id + '-indicatorBar-temporaryMountPoint-clear' + '" ' +
						'class="' + this.id + '-indicatorBar-item-rightIcon ' + '">X' +
					'</span>' +
					TYPO3.Components.PageTree.LLL.temporaryMountPointIndicatorInfo + '<br />' +
						TYPO3.Components.PageTree.Configuration.temporaryMountPoint +
				'</p>'
		});
	},

	/**
	 * Adds an indicator item
	 *
	 * @param {Object} component
	 * @return {void}
	 */
	addIndicator: function(component) {
		if (component.listeners && component.listeners.afterrender) {
			component.listeners.afterrender.fn = component.listeners.afterrender.fn.createSequence(
				this.afterTopPanelItemAdded, this
			);
		} else {
			if (component.listeners) {
				component.listeners = {}
			}

			component.listeners.afterrender = {
				scope: this,
				fn: this.afterTopPanelItemAdded
			}
		}

		var indicator = Ext.getCmp(this.id + '-indicatorBar').add(component);
		this.doLayout();

		return indicator;
	},

	/**
	 * Recalculates the top panel items height after an indicator was added
	 *
	 * @param {Ext.Component} component
	 * @return {void}
	 */
	afterTopPanelItemAdded: function(component) {
		var topPanelItems = Ext.getCmp(this.id + '-topPanelItems');
		topPanelItems.setHeight(topPanelItems.getHeight() + component.getHeight() + 3);
		this.doLayout();
	},

	/**
	 * Removes an indicator item from the indicator bar
	 *
	 * @param {Ext.Component} component
	 * @return {void}
	 */
	removeIndicator: function(component) {
		var topPanelItems = Ext.getCmp(this.id + '-topPanelItems');
		topPanelItems.setHeight(topPanelItems.getHeight() - component.getHeight() - 3);
		Ext.getCmp(this.id + '-indicatorBar').remove(component);
		this.doLayout();
	},

	/**
	 * Compatibility method that calls refreshTree()
	 *
	 * @return {void}
	 */
	refresh: function() {
		this.refreshTree();
	},

	/**
	 * Another compatibility method that calls refreshTree()
	 *
	 * @return {void}
	 */
	refresh_nav: function() {
		this.refreshTree();
	},

	/**
	 * Refreshes the tree and selects the node defined by fsMod.recentIds['web']
	 *
	 * @return {void}
	 */
	refreshTree: function() {
		if (!isNaN(fsMod.recentIds['web']) && fsMod.recentIds['web'] !== '') {
			this.select(fsMod.recentIds['web'], true);
		}

		TYPO3.Components.PageTree.DataProvider.getIndicators(function(response) {
			var indicators = Ext.getCmp(this.id + '-indicatorBar-indicatorTitle');
			if (indicators) {
				this.removeIndicator(indicators);
			}

			if (response._COUNT > 0) {
				TYPO3.Components.PageTree.Configuration.indicator = response.html;
				this.addIndicatorItems();
			}
		}, this);

		this.activeTree.refreshTree();
	},

	/**
	 * Returns the current active tree
	 *
	 * @return {TYPO3.Components.PageTree.Tree}
	 */
	getTree: function() {
		return this.activeTree;
	},

	/**
	 * Selects a node defined by the page id. If the second parameter is set, we
	 * store the new location into the state hash.
	 *
	 * @param {int} pageId
	 * @param {Boolean} saveState
	 * @return {Boolean}
	 */
	select: function(pageId, saveState) {
		if (saveState !== false) {
			saveState = true;
		}

		var tree = this.getTree();

		var succeeded = false;
		var node = tree.getRootNode().findChild('realId', pageId, true);

		if (node) {
			succeeded = true;
			tree.selectPath(node.getPath());
			if (!!saveState && tree.stateHash) {
				tree.stateHash.lastSelectedNode = node.id;
			}
		}

		return succeeded;
	},

	/**
	 * Returns the currently selected node
	 *
	 * @return {Ext.tree.TreeNode}
	 */
	getSelected: function() {
		var node = this.getTree().getSelectionModel().getSelectedNode();
		return node ? node : null;
	}
});


// XTYPE Registration
Ext.reg('TYPO3.Components.PageTree.CkApp', TYPO3.Components.PageTree.CkApp);
