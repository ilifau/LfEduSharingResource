<?php

/* Copyright (c) 2012 Leifos GmbH, GPL */


/**
* Edusharing resource repository object plugin
*
* @author Alex Killing <alex.killing@gmx.de>
* @version $Id$
*
*/
class ilLfEduSharingResourcePlugin extends ilRepositoryObjectPlugin
{
	const ID = "xesr";
	protected static $instance = NULL;
	
	function getPluginName() {
		return "LfEduSharingResource";
	}
	
	public static function getInstance() {
		if (self::$instance === NULL) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	
	protected function uninstallCustom() {
		// TODO: delete database
	}
}
?>
