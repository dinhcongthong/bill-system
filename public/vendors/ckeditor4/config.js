/**
* @license Copyright (c) 2003-2018, CKSource - Frederico Knabben. All rights reserved.
* For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
*/

CKEDITOR.stylesSet.add('content_style', [
	// Block-level styles
	{ name: 'Normal', element: 'p', styles: { 'font-size': '18px' }},
	{ name: 'H2 Title', element: 'h2', attributes: { 'class': 'h2-title'}},
	{ name: 'H3 Title', element: 'h3', attributes: { 'class': 'h3-title' }},
	{ name: 'H4 Title', element: 'h4', attributes: { 'class': 'h4-title' }},
	
	// Inline styles
	{ name: 'CSS Style', element: 'span', attributes: { 'class': 'my_style' } },
	{ name: 'Marker: Yellow', element: 'span', styles: { 'background-color': 'Yellow' } }
]);

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	
	// Disable ACF and script tag
	config.disallowedContent = 'script';
	config.allowedContent = true;
	
	config.toolbar_Basic =
	[
		['Bold', 'Italic', 'Underline', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink', '-', 'About']
	];

	config.contentsCss = base_url + '/vendors/ckeditor4/plugins/styles/content.css';
	
	config.stylesSet = 'content_style';
};
