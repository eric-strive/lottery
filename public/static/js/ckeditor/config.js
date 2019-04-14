/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	CKEDITOR.on('dialogDefinition', function(ev) {
        // Take the dialog name and its definition from the event data
        var dialogName = ev.data.name;
        var dialogDefinition = ev.data.definition;
        var editor = ev.editor;
        if (dialogName == 'image') {
           dialogDefinition.onOk = function(e) {
              var imageSrcUrl = e.sender.originalElement.$.src;
              var imgHtml = CKEDITOR.dom.element.createFromHtml("<img src='" + imageSrcUrl + "' alt=''/>");
              editor.insertElement(imgHtml);
           };
        }
    });
};
