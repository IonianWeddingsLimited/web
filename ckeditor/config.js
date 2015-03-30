/*

Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.

For licensing, see LICENSE.html or http://ckeditor.com/license

*/



CKEDITOR.editorConfig = function( config )

{

	config.startupOutlineBlocks = true;
	config.forcePasteAsPlainText = true;

	config.toolbar = 'Note';
	
	config.toolbar_Note =
	
	[
	
		['ShowBlocks'],
	
		['Bold','Italic','Underline','BulletedList','-','Link'],
	
	];
	
	config.toolbar = 'Bullet';
	
	config.toolbar_Bullet =
	
	[
	
		['ShowBlocks'],
	
		['Bold','Italic','Underline','BulletedList','-','Link'],
	
	];

	config.toolbar = 'Full';
	
	config.toolbar_Full =
	
	[
	
		['Source','-','Preview','-','ShowBlocks'],
	
		['Bold','Italic','Underline','Strike','-','Subscript'],
	
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],	
	
		['Format','Font','FontSize','TextColor'],
	
		'/',	
	
		['Cut','Copy','Paste','PasteText','PasteFromWord','-', 'SpellChecker', 'Scayt'],
	
		['Undo','Redo'],
	
		['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
	
		['Link','Unlink','Anchor','Image','Table','HorizontalRule','SpecialChar','PageBreak'],
	
	];

	config.toolbar = 'Basic';

	config.toolbar_Basic =
	
	[
	
		['Source','-','ShowBlocks'],
	
		['Bold','Italic','Underline','Strike','-','Subscript'],
	
		['NumberedList','BulletedList'],
	
		['Cut','Copy','Paste','PasteText','PasteFromWord','-', 'SpellChecker', 'Scayt'],
	
	];

	config.toolbar = 'Min';
	
	config.toolbar_Min =
	
	[
	
		['ShowBlocks'],
	
		['Bold','Italic','Underline'],
	
		['Cut','Copy','Paste','PasteText','PasteFromWord','-', 'SpellChecker', 'Scayt'],
	
	];

	config.toolbar = 'PDF';
	
	config.toolbar_PDF =
	
	[
	
		['ShowBlocks'],
	
		['Bold','Italic','Underline','-','Link'],
	
	];

};



