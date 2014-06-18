<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Table tl_style
 */
$GLOBALS['TL_DCA']['tl_style'] =
[

	// Config
	'config' =>
	[
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_style_sheet',
		'enableVersioning'            => true,
		'onload_callback' =>
		[
			['tl_style', 'checkPermission'],
			['tl_style', 'updateStyleSheet']
		],
		'oncopy_callback' =>
		[
			['tl_style', 'scheduleUpdate']
		],
		'oncut_callback' =>
		[
			['tl_style', 'scheduleUpdate']
		],
		'ondelete_callback' =>
		[
			['tl_style', 'scheduleUpdate']
		],
		'onsubmit_callback' =>
		[
			['tl_style', 'scheduleUpdate']
		],
		'onrestore_callback' =>
		[
			['tl_style', 'updateAfterRestore']
		],
		'sql' =>
		[
			'keys' =>
			[
				'id' => 'primary',
				'pid' => 'index'
			]
		]
	],

	// List
	'list' =>
	[
		'sorting' =>
		[
			'mode'                    => 4,
			'fields'                  => ['sorting'],
			'panelLayout'             => 'filter;search,limit',
			'headerFields'            => ['name', 'tstamp', 'media'],
			'child_record_callback'   => ['StyleSheets', 'compileDefinition']
		],
		'global_operations' =>
		[
			'all' =>
			[
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			]
		],
		'operations' =>
		[
			'edit' =>
			[
				'label'               => &$GLOBALS['TL_LANG']['tl_style']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			],
			'copy' =>
			[
				'label'               => &$GLOBALS['TL_LANG']['tl_style']['copy'],
				'href'                => 'act=paste&amp;mode=copy',
				'icon'                => 'copy.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset()"'
			],
			'cut' =>
			[
				'label'               => &$GLOBALS['TL_LANG']['tl_style']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset()"'
			],
			'delete' =>
			[
				'label'               => &$GLOBALS['TL_LANG']['tl_style']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			],
			'toggle' =>
			[
				'label'               => &$GLOBALS['TL_LANG']['tl_style']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s,\'tl_style\')"',
				'button_callback'     => ['tl_style', 'toggleIcon']
			],
			'show' =>
			[
				'label'               => &$GLOBALS['TL_LANG']['tl_style']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			]
		]
	],

	// Palettes
	'palettes' =>
	[
		'__selector__'                => ['size', 'positioning', 'alignment', 'background', 'border', 'font', 'list'],
		'default'                     => '{selector_legend},selector,category,comment;{size_legend},size;{position_legend},positioning;{align_legend},alignment;{background_legend},background;{border_legend},border;{font_legend},font;{list_legend},list;{custom_legend:hide},own',
	],

	// Subpalettes
	'subpalettes' =>
	[
		'size'                        => 'width,height,minwidth,minheight,maxwidth,maxheight',
		'positioning'                 => 'trbl,position,floating,clear,overflow,display',
		'alignment'                   => 'margin,padding,align,verticalalign,textalign,whitespace',
		'background'                  => 'bgcolor,bgimage,bgposition,bgrepeat,shadowsize,shadowcolor,gradientAngle,gradientColors',
		'border'                      => 'borderwidth,borderstyle,bordercolor,borderradius,bordercollapse,borderspacing',
		'font'                        => 'fontfamily,fontsize,fontcolor,lineheight,fontstyle,texttransform,textindent,letterspacing,wordspacing',
		'list'                        => 'liststyletype,liststyleimage'
	],

	// Fields
	'fields' =>
	[
		'id' =>
		[
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		],
		'pid' =>
		[
			'foreignKey'              => 'tl_style_sheet.name',
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => ['type'=>'belongsTo', 'load'=>'lazy']
		],
		'sorting' =>
		[
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		],
		'tstamp' =>
		[
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		],
		'selector' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['selector'],
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => ['mandatory'=>true, 'maxlength'=>1022, 'decodeEntities'=>true, 'style'=>'height:60px'],
			'sql'                     => "varchar(1022) NOT NULL default ''"
		],
		'category' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['category'],
			'search'                  => true,
			'filter'                  => true,
			'inputType'               => 'text',
			'eval'                    => ['maxlength'=>32, 'decodeEntities'=>true, 'tl_class'=>'w50'],
			'load_callback' =>
			[
				['tl_style', 'checkCategory']
			],
			'sql'                     => "varchar(32) NOT NULL default ''"
		],
		'comment' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['comment'],
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => ['maxlength'=>255, 'decodeEntities'=>true, 'tl_class'=>'w50'],
			'sql'                     => "varchar(255) NOT NULL default ''"
		],
		'size' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['size'],
			'inputType'               => 'checkbox',
			'eval'                    => ['submitOnChange'=>true],
			'sql'                     => "char(1) NOT NULL default ''"
		],
		'width' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['width'],
			'inputType'               => 'inputUnit',
			'options'                 => ['px', '%', 'em', 'rem', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'],
			'eval'                    => ['includeBlankOption'=>true, 'rgxp'=>'digit_auto_inherit', 'maxlength' => 20, 'tl_class'=>'w50'],
			'sql'                     => "varchar(64) NOT NULL default ''"
		],
		'height' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['height'],
			'inputType'               => 'inputUnit',
			'options'                 => ['px', '%', 'em', 'rem', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'],
			'eval'                    => ['includeBlankOption'=>true, 'rgxp'=>'digit_auto_inherit', 'maxlength' => 20, 'tl_class'=>'w50'],
			'sql'                     => "varchar(64) NOT NULL default ''"
		],
		'minwidth' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['minwidth'],
			'inputType'               => 'inputUnit',
			'options'                 => ['px', '%', 'em', 'rem', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'],
			'eval'                    => ['includeBlankOption'=>true, 'rgxp'=>'digit_inherit', 'maxlength' => 20, 'tl_class'=>'w50'],
			'sql'                     => "varchar(64) NOT NULL default ''"
		],
		'minheight' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['minheight'],
			'inputType'               => 'inputUnit',
			'options'                 => ['px', '%', 'em', 'rem', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'],
			'eval'                    => ['includeBlankOption'=>true, 'rgxp'=>'digit_inherit', 'maxlength' => 20, 'tl_class'=>'w50'],
			'sql'                     => "varchar(64) NOT NULL default ''"
		],
		'maxwidth' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['maxwidth'],
			'inputType'               => 'inputUnit',
			'options'                 => ['px', '%', 'em', 'rem', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'],
			'eval'                    => ['includeBlankOption'=>true, 'rgxp'=>'digit_inherit_none', 'maxlength' => 20, 'tl_class'=>'w50'],
			'sql'                     => "varchar(64) NOT NULL default ''"
		],
		'maxheight' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['maxheight'],
			'inputType'               => 'inputUnit',
			'options'                 => ['px', '%', 'em', 'rem', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'],
			'eval'                    => ['includeBlankOption'=>true, 'rgxp'=>'digit_inherit_none', 'maxlength' => 20, 'tl_class'=>'w50'],
			'sql'                     => "varchar(64) NOT NULL default ''"
		],
		'positioning' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['positioning'],
			'inputType'               => 'checkbox',
			'eval'                    => ['submitOnChange'=>true],
			'sql'                     => "char(1) NOT NULL default ''"
		],
		'trbl' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['trbl'],
			'inputType'               => 'trbl',
			'options'                 => ['px', '%', 'em', 'rem', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'],
			'eval'                    => ['includeBlankOption'=>true, 'rgxp'=>'digit_auto_inherit', 'tl_class'=>'w50'],
			'sql'                     => "varchar(128) NOT NULL default ''"
		],
		'position' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['position'],
			'inputType'               => 'select',
			'options'                 => ['absolute', 'relative', 'static', 'fixed'],
			'eval'                    => ['includeBlankOption'=>true, 'tl_class'=>'w50'],
			'sql'                     => "varchar(32) NOT NULL default ''"
		],
		'floating' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['floating'],
			'inputType'               => 'select',
			'options'                 => ['left', 'right', 'none'],
			'eval'                    => ['includeBlankOption'=>true, 'tl_class'=>'w50'],
			'sql'                     => "varchar(32) NOT NULL default ''"
		],
		'clear' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['clear'],
			'inputType'               => 'select',
			'options'                 => ['both', 'left', 'right', 'none'],
			'eval'                    => ['includeBlankOption'=>true, 'tl_class'=>'w50'],
			'sql'                     => "varchar(32) NOT NULL default ''"
		],
		'overflow' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['overflow'],
			'inputType'               => 'select',
			'options'                 => ['auto', 'hidden', 'scroll', 'visible'],
			'eval'                    => ['includeBlankOption'=>true, 'tl_class'=>'w50'],
			'sql'                     => "varchar(32) NOT NULL default ''"
		],
		'display' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['display'],
			'inputType'               => 'select',
			'options'                 => ['block', 'inline', 'inline-block', 'list-item', 'run-in', 'compact', 'none', 'table', 'inline-table', 'table-row', 'table-cell', 'table-row-group', 'table-header-group', 'table-footer-group', 'table-column', 'table-column-group', 'table-caption'],
			'eval'                    => ['includeBlankOption'=>true, 'tl_class'=>'w50'],
			'sql'                     => "varchar(32) NOT NULL default ''"
		],
		'alignment' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['alignment'],
			'inputType'               => 'checkbox',
			'eval'                    => ['submitOnChange'=>true],
			'sql'                     => "char(1) NOT NULL default ''"
		],
		'margin' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['margin'],
			'inputType'               => 'trbl',
			'options'                 => ['px', '%', 'em', 'rem', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'],
			'eval'                    => ['includeBlankOption'=>true, 'rgxp'=>'digit_auto_inherit', 'tl_class'=>'w50'],
			'sql'                     => "varchar(128) NOT NULL default ''"
		],
		'padding' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['padding'],
			'inputType'               => 'trbl',
			'options'                 => ['px', '%', 'em', 'rem', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'],
			'eval'                    => ['includeBlankOption'=>true, 'rgxp'=>'digit_inherit', 'tl_class'=>'w50'],
			'sql'                     => "varchar(128) NOT NULL default ''"
		],
		'align' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['align'],
			'inputType'               => 'select',
			'options'                 => ['left', 'center', 'right'],
			'reference'               => &$GLOBALS['TL_LANG']['MSC'],
			'eval'                    => ['includeBlankOption'=>true, 'tl_class'=>'w50'],
			'sql'                     => "varchar(32) NOT NULL default ''"
		],
		'verticalalign' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['verticalalign'],
			'inputType'               => 'select',
			'options'                 => ['top', 'text-top', 'middle', 'text-bottom', 'baseline', 'bottom'],
			'eval'                    => ['includeBlankOption'=>true, 'tl_class'=>'w50'],
			'sql'                     => "varchar(32) NOT NULL default ''"
		],
		'textalign' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['textalign'],
			'inputType'               => 'select',
			'options'                 => ['left', 'center', 'right', 'justify'],
			'reference'               => &$GLOBALS['TL_LANG']['MSC'],
			'eval'                    => ['includeBlankOption'=>true, 'tl_class'=>'w50'],
			'sql'                     => "varchar(32) NOT NULL default ''"
		],
		'whitespace' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['whitespace'],
			'inputType'               => 'select',
			'options'                 => ['normal', 'nowrap', 'pre', 'pre-line', 'pre-wrap'],
			'eval'                    => ['includeBlankOption'=>true, 'tl_class'=>'w50'],
			'sql'                     => "varchar(8) NOT NULL default ''"
		],
		'background' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['background'],
			'inputType'               => 'checkbox',
			'eval'                    => ['submitOnChange'=>true],
			'sql'                     => "char(1) NOT NULL default ''"
		],
		'bgcolor' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['bgcolor'],
			'inputType'               => 'text',
			'eval'                    => ['maxlength'=>6, 'multiple'=>true, 'size'=>2, 'colorpicker'=>true, 'isHexColor'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50 wizard'],
			'sql'                     => "varchar(64) NOT NULL default ''"
		],
		'bgimage' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['bgimage'],
			'inputType'               => 'text',
			'eval'                    => ['filesOnly'=>true, 'extensions'=>Config::get('validImageTypes'), 'fieldType'=>'radio', 'tl_class'=>'w50 wizard'],
			'wizard' =>
			[
				['tl_style', 'filePicker']
			],
			'sql'                     => "varchar(255) NOT NULL default ''"
		],
		'bgposition' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['bgposition'],
			'inputType'               => 'select',
			'options'                 => ['left top', 'left center', 'left bottom', 'center top', 'center center', 'center bottom', 'right top', 'right center', 'right bottom'],
			'eval'                    => ['includeBlankOption'=>true, 'tl_class'=>'w50'],
			'sql'                     => "varchar(32) NOT NULL default ''"
		],
		'bgrepeat' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['bgrepeat'],
			'inputType'               => 'select',
			'options'                 => ['repeat', 'repeat-x', 'repeat-y', 'no-repeat'],
			'eval'                    => ['includeBlankOption'=>true, 'tl_class'=>'w50'],
			'sql'                     => "varchar(32) NOT NULL default ''"
		],
		'shadowsize' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['shadowsize'],
			'inputType'               => 'trbl',
			'options'                 => ['px', '%', 'em', 'rem', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'],
			'eval'                    => ['includeBlankOption'=>true, 'rgxp'=>'digit_', 'tl_class'=>'w50'],
			'sql'                     => "varchar(128) NOT NULL default ''"
		],
		'shadowcolor' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['shadowcolor'],
			'inputType'               => 'text',
			'eval'                    => ['maxlength'=>6, 'multiple'=>true, 'size'=>2, 'colorpicker'=>true, 'isHexColor'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50 wizard'],
			'sql'                     => "varchar(64) NOT NULL default ''"
		],
		'gradientAngle' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['gradientAngle'],
			'inputType'               => 'text',
			'eval'                    => ['maxlength'=>32, 'tl_class'=>'w50'],
			'sql'                     => "varchar(32) NOT NULL default ''"
		],
		'gradientColors' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['gradientColors'],
			'inputType'               => 'text',
			'eval'                    => ['multiple'=>true, 'size'=>4, 'decodeEntities'=>true, 'tl_class'=>'w50'],
			'sql'                     => "varchar(128) NOT NULL default ''"
		],
		'border' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['border'],
			'inputType'               => 'checkbox',
			'eval'                    => ['submitOnChange'=>true],
			'sql'                     => "char(1) NOT NULL default ''"
		],
		'borderwidth' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['borderwidth'],
			'inputType'               => 'trbl',
			'options'                 => ['px', '%', 'em', 'rem', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'],
			'eval'                    => ['includeBlankOption'=>true, 'rgxp'=>'digit_inherit', 'tl_class'=>'w50'],
			'sql'                     => "varchar(128) NOT NULL default ''"
		],
		'borderstyle' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['borderstyle'],
			'inputType'               => 'select',
			'options'                 => ['solid', 'dotted', 'dashed', 'double', 'groove', 'ridge', 'inset', 'outset', 'hidden'],
			'eval'                    => ['includeBlankOption'=>true, 'tl_class'=>'w50'],
			'sql'                     => "varchar(32) NOT NULL default ''"
		],
		'bordercolor' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['bordercolor'],
			'inputType'               => 'text',
			'eval'                    => ['maxlength'=>6, 'multiple'=>true, 'size'=>2, 'colorpicker'=>true, 'isHexColor'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50 wizard'],
			'sql'                     => "varchar(64) NOT NULL default ''"
		],
		'borderradius' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['borderradius'],
			'inputType'               => 'trbl',
			'options'                 => ['px', '%', 'em', 'rem', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'],
			'eval'                    => ['includeBlankOption'=>true, 'rgxp'=>'digit_', 'tl_class'=>'w50'],
			'sql'                     => "varchar(128) NOT NULL default ''"
		],
		'bordercollapse' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['bordercollapse'],
			'inputType'               => 'select',
			'options'                 => ['collapse', 'separate'],
			'eval'                    => ['includeBlankOption'=>true, 'tl_class'=>'w50'],
			'sql'                     => "varchar(32) NOT NULL default ''"
		],
		'borderspacing' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['borderspacing'],
			'inputType'               => 'inputUnit',
			'options'                 => ['px', '%', 'em', 'rem', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'],
			'eval'                    => ['includeBlankOption'=>true, 'rgxp'=>'digit_inherit', 'maxlength' => 20, 'tl_class'=>'w50'],
			'sql'                     => "varchar(64) NOT NULL default ''"
		],
		'font' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['font'],
			'inputType'               => 'checkbox',
			'eval'                    => ['submitOnChange'=>true],
			'sql'                     => "char(1) NOT NULL default ''"
		],
		'fontfamily' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['fontfamily'],
			'inputType'               => 'text',
			'eval'                    => ['maxlength'=>255, 'tl_class'=>'w50'],
			'sql'                     => "varchar(255) NOT NULL default ''"
		],
		'fontsize' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['fontsize'],
			'inputType'               => 'inputUnit',
			'options'                 => ['px', '%', 'em', 'rem', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'],
			'eval'                    => ['includeBlankOption'=>true, 'rgxp'=>'digit_inherit', 'maxlength' => 20, 'tl_class'=>'w50'],
			'sql'                     => "varchar(64) NOT NULL default ''"
		],
		'fontcolor' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['fontcolor'],
			'inputType'               => 'text',
			'eval'                    => ['maxlength'=>6, 'multiple'=>true, 'size'=>2, 'colorpicker'=>true, 'isHexColor'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50 wizard'],
			'sql'                     => "varchar(64) NOT NULL default ''"
		],
		'lineheight' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['lineheight'],
			'inputType'               => 'inputUnit',
			'options'                 => ['px', '%', 'em', 'rem', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'],
			'eval'                    => ['includeBlankOption'=>true, 'rgxp'=>'digit_normal_inherit', 'maxlength' => 20, 'tl_class'=>'w50'],
			'sql'                     => "varchar(64) NOT NULL default ''"
		],
		'fontstyle' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['fontstyle'],
			'inputType'               => 'checkbox',
			'options'                 => ['bold', 'italic', 'normal', 'underline', 'notUnderlined', 'line-through', 'overline', 'small-caps'],
			'reference'               => &$GLOBALS['TL_LANG']['tl_style'],
			'eval'                    => ['multiple'=>true, 'tl_class'=>'clr'],
			'sql'                     => "varchar(255) NOT NULL default ''"
		],
		'texttransform' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['texttransform'],
			'inputType'               => 'select',
			'options'                 => ['uppercase', 'lowercase', 'capitalize', 'none'],
			'reference'               => &$GLOBALS['TL_LANG']['tl_style'],
			'eval'                    => ['includeBlankOption'=>true, 'tl_class'=>'w50'],
			'sql'                     => "varchar(32) NOT NULL default ''"
		],
		'textindent' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['textindent'],
			'inputType'               => 'inputUnit',
			'options'                 => ['px', '%', 'em', 'rem', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'],
			'eval'                    => ['includeBlankOption'=>true, 'rgxp'=>'digit_inherit', 'maxlength' => 20, 'tl_class'=>'w50'],
			'sql'                     => "varchar(64) NOT NULL default ''"
		],
		'letterspacing' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['letterspacing'],
			'inputType'               => 'inputUnit',
			'options'                 => ['px', '%', 'em', 'rem', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'],
			'eval'                    => ['includeBlankOption'=>true, 'rgxp'=>'digit_normal_inherit', 'maxlength' => 20, 'tl_class'=>'w50'],
			'sql'                     => "varchar(64) NOT NULL default ''"
		],
		'wordspacing' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['wordspacing'],
			'inputType'               => 'inputUnit',
			'options'                 => ['px', '%', 'em', 'rem', 'ex', 'pt', 'pc', 'in', 'cm', 'mm'],
			'eval'                    => ['includeBlankOption'=>true, 'rgxp'=>'digit_normal_inherit', 'maxlength' => 20, 'tl_class'=>'w50'],
			'sql'                     => "varchar(64) NOT NULL default ''"
		],
		'list' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['list'],
			'inputType'               => 'checkbox',
			'eval'                    => ['submitOnChange'=>true],
			'sql'                     => "char(1) NOT NULL default ''"
		],
		'liststyletype' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['liststyletype'],
			'inputType'               => 'select',
			'options'                 => ['disc', 'circle', 'square', 'decimal', 'upper-roman', 'lower-roman', 'upper-alpha', 'lower-alpha', 'none'],
			'reference'               => &$GLOBALS['TL_LANG']['tl_style'],
			'eval'                    => ['includeBlankOption'=>true, 'tl_class'=>'w50'],
			'sql'                     => "varchar(32) NOT NULL default ''"
		],
		'liststyleimage' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['liststyleimage'],
			'inputType'               => 'text',
			'eval'                    => ['filesOnly'=>true, 'extensions'=>Config::get('validImageTypes'), 'fieldType'=>'radio', 'tl_class'=>'w50 wizard'],
			'wizard' =>
			[
				['tl_style', 'filePicker']
			],
			'sql'                     => "varchar(255) NOT NULL default ''"
		],
		'own' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['own'],
			'search'                  => true,
			'inputType'               => 'textarea',
			'eval'                    => ['preserveTags'=>true, 'style'=>'height:120px'],
			'sql'                     => "text NULL"
		],
		'invisible' =>
		[
			'label'                   => &$GLOBALS['TL_LANG']['tl_style']['invisible'],
			'filter'                  => true,
			'inputType'               => 'checkbox',
			'sql'                     => "char(1) NOT NULL default ''"
		]
	]
];


/**
 * Class tl_style
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Leo Feyer 2005-2014
 * @author     Leo Feyer <https://contao.org>
 * @package    Core
 */
class tl_style extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}


	/**
	 * Check permissions to edit the table
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		if (!$this->User->hasAccess('css', 'themes'))
		{
			$this->log('Not enough permissions to access the style sheets module', __METHOD__, TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}
	}


	/**
	 * Automatically set the category if not set
	 * @param mixed
	 * @return string
	 */
	public function checkCategory($varValue)
	{
		// Do not change the value if it has been set already
		if (strlen($varValue) || Input::post('FORM_SUBMIT') == 'tl_style')
		{
			return $varValue;
		}

		$key = 'tl_style_' . CURRENT_ID;
		$filter = $this->Session->get('filter');

		// Return the current category
		if (strlen($filter[$key]['category']))
		{
			return $filter[$key]['category'];
		}

		return '';
	}


	/**
	 * Return the file picker wizard
	 * @param Contao\DataContainer
	 * @return string
	 */
	public function filePicker(Contao\DataContainer $dc)
	{
		return ' <a href="contao/file.php?do='.Input::get('do').'&amp;table='.$dc->table.'&amp;field='.$dc->field.'&amp;value='.$dc->value.'" title="'.specialchars(str_replace("'", "\\'", $GLOBALS['TL_LANG']['MSC']['filepicker'])).'" onclick="Backend.getScrollOffset();Backend.openModalSelector({\'width\':768,\'title\':\''.specialchars($GLOBALS['TL_LANG']['MOD']['files'][0]).'\',\'url\':this.href,\'id\':\''.$dc->field.'\',\'tag\':\'ctrl_'.$dc->field . ((Input::get('act') == 'editAll') ? '_' . $dc->id : '').'\',\'self\':this});return false">' . Image::getHtml('pickfile.gif', $GLOBALS['TL_LANG']['MSC']['filepicker'], 'style="vertical-align:top;cursor:pointer"') . '</a>';
	}


	/**
	 * Check for modified style sheets and update them if necessary
	 */
	public function updateStyleSheet()
	{
		$session = $this->Session->get('style_sheet_updater');

		if (!is_array($session) || empty($session))
		{
			return;
		}

		$this->import('StyleSheets');

		foreach ($session as $id)
		{
			$this->StyleSheets->updateStyleSheet($id);
		}

		$this->Session->set('style_sheet_updater', null);
	}


	/**
	 * Schedule a style sheet update
	 *
	 * This method is triggered when a single style or multiple styles are
	 * modified (edit/editAll), duplicated (copy/copyAll), moved (cut/cutAll)
	 * or deleted (delete/deleteAll).
	 */
	public function scheduleUpdate()
	{
		// Return if there is no ID
		if (!CURRENT_ID || Input::get('act') == 'copy' || Environment::get('isAjaxRequest'))
		{
			return;
		}

		// Store the ID in the session
		$session = $this->Session->get('style_sheet_updater');
		$session[] = CURRENT_ID;
		$this->Session->set('style_sheet_updater', array_unique($session));
	}


	/**
	 * Update a style sheet after a version has been restored
	 * @param integer
	 * @param string
	 * @param array
	 */
	public function updateAfterRestore($id, $table, $data)
	{
		if ($table != 'tl_style')
		{
			return;
		}

		// Update the timestamp of the style sheet
		$this->Database->prepare("UPDATE tl_style_sheet SET tstamp=? WHERE id=?")
					   ->execute(time(), $data['pid']);

		// Update the CSS file
		$this->import('StyleSheets');
		$this->StyleSheets->updateStyleSheet($data['pid']);
	}


	/**
	 * Return the "toggle visibility" button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen(Input::get('tid')))
		{
			$this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1));
			$this->redirect($this->getReferer());
		}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.$row['invisible'];

		if ($row['invisible'])
		{
			$icon = 'invisible.gif';
		}

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.Image::getHtml($icon, $label).'</a> ';
	}


	/**
	 * Toggle the visibility of a format definition
	 * @param integer
	 * @param boolean
	 */
	public function toggleVisibility($intId, $blnVisible)
	{
		$objVersions = new Versions('tl_style', $intId);
		$objVersions->initialize();

		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_style']['fields']['invisible']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_style']['fields']['invisible']['save_callback'] as $callback)
			{
				if (is_array($callback))
				{
					$this->import($callback[0]);
					$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
				}
				elseif (is_callable($callback))
				{
					$blnVisible = $callback($blnVisible, $this);
				}
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_style SET tstamp=". time() .", invisible='" . ($blnVisible ? '' : 1) . "' WHERE id=?")
					   ->execute($intId);

		$objVersions->create();
		$this->log('A new version of record "tl_style.id='.$intId.'" has been created'.$this->getParentEntries('tl_style', $intId), __METHOD__, TL_GENERAL);

		// Recreate the style sheet
		$objStylesheet = $this->Database->prepare("SELECT pid FROM tl_style WHERE id=?")
									    ->limit(1)
									    ->execute($intId);

		if ($objStylesheet->numRows)
		{
			$this->import('StyleSheets');
			$this->StyleSheets->updateStyleSheet($objStylesheet->pid);
		}
	}
}