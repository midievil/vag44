/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	config.language = 'ru';
	// config.uiColor = '#AADC6E';
	
	config.toolbar = 'MyToolbar';
 
	config.toolbar_MyToolbar =
	[	
		{ name: 'styles', items : [ 'Styles','Format' ] },
		{ name: 'insert', items : [ 'Smiley', 'Image','Flash','Table','SpecialChar','PageBreak','Iframe' ] },                
		{ name: 'clipboard', items : [ 'Undo','Redo' ] },				
		{ name: 'basicstyles', items : [ 'Bold','Italic','Strike','-','RemoveFormat' ] },
		{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote' ] },		
	];
};
