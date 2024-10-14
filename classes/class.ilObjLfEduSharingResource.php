<?php

/* Copyright (c) 2012 Leifos GmbH, GPL */

#include_once("./Services/Repository/classes/class.ilObjectPlugin.php");
#require_once("./Services/Tracking/interfaces/interface.ilLPStatusPlugin.php");

/**
 * Application class for edusharing resource repository object.
 *
 * @author Alex Killing <alex.killing@gmx.de>
 * @author Uwe Kohnle <kohnle@internetlehrer-gmbh.de>
 *
 * $Id$
 */
class ilObjLfEduSharingResource extends ilObjectPlugin //implements ilLPStatusPluginInterface
{

	public $window_width = 200;
	public $window_height = 100;
	public $object_version = 0;
	protected $object_version_use_exact = 1;

	protected bool $online = false;

	/**
	 * Constructor
	 * @access    public
	 */
	function __construct($a_ref_id = 0)
	{
		parent::__construct($a_ref_id);
	}

	/**
	 * Get type.
	 */
	final function initType()
	{
		$this->setType("xesr");
	}

	/**
	 * Get resource_id
	 * used in lib
	 * @return string uri
	 */
	public function getResId()
	{
		return $this->getId();
	}

	/**
	 * Set URI
	 * @param string $a_val uri
	 */
	public function setUri($a_val)
	{
		$this->uri = $a_val;
	}

	/**
	 * Get URI
	 * @return string uri
	 */
	public function getUri()
	{
		return $this->uri;
	}

	public function setObjectVersion($a_val)
	{
		$this->object_version = $a_val;
	}

	public function getObjectVersion()
	{
		return $this->object_version;
	}

	public function setObjectVersionUseExact($a_val)
	{
		$this->object_version_use_exact = $a_val;
	}

	public function getObjectVersionUseExact()
	{
		return $this->object_version_use_exact;
	}

	public function getObjectVersionForUse()
	{
		if ($this->object_version_use_exact == 0) {
			return 0;
		} else {
			return $this->object_version;
		}
	}

	public function setOnline($a_val)
	{
		$this->online = (bool) $a_val;
	}
	public function getOnline()
	{
		return (bool) $this->online;
	}

	/**
	 * Create object
	 */
	protected function doCreate()
	{
		global $DIC;
		$db = $DIC->database();
		$db->insert('rep_robj_xesr_usage',
			array(
				'id' => array('integer', $this->getId()),
				'edus_uri' => array('text', $this->getUri()), //""
				'parent_obj_id' => array('integer', $this->getId()),
				'is_online' => array('integer', $this->getOnline()),
				'object_version' => array('integer', $this->getObjectVersion()),
				'object_version_use_exact' => array('integer', $this->getObjectVersionUseExact()),
				'timecreated' => array('timestamp', date('Y-m-d H:i:s')),
				'timemodified' => array('timestamp', date('Y-m-d H:i:s'))
			)
		);
	}

	// function afterCreateSetParentObj() {
	// global $DIC;
	// $db = $DIC->database();
	// $db->update('rep_robj_xesr_usage',
	// array(
	// 'parent_obj_id'	=> array('integer', $this->getUpperCourse())
	// ),
	// array(
	// 'id' => array('integer', $this->getId()),
	// 'parent_obj_id' => array('integer', $this->getId())
	// )
	// );

	// }

	/**
	 * Read data from db
	 */
	function doRead()
	{
		global $DIC;
		$check_parent_obj_id = 0;

		$db = $DIC->database();
		$query = "SELECT * FROM rep_robj_xesr_usage WHERE id = " . $db->quote($this->getId(), 'integer') .
			" AND parent_obj_id = " . $db->quote($this->getUpperCourse(), "integer");
		$result = $db->query($query);
		while (($row = $result->fetchAssoc()) !== false) {
			$this->setUri($row['edus_uri']);
			$this->setOnline($row["is_online"]);
			$this->setObjectVersion($row['object_version']);
			$this->setObjectVersionUseExact($row['object_version_use_exact']);
			// $this->set($row['timecreated']);
			// $this->set($row['timemodified']);
			$check_parent_obj_id = $row['parent_obj_id'];
		}

		if ($check_parent_obj_id != $this->getUpperCourse()) { //after creation or cloning
			$db->update('rep_robj_xesr_usage',
				array(
					'parent_obj_id' => array('integer', $this->getUpperCourse())
				),
				array(
					'id' => array('integer', $this->getId())//,
					// 'parent_obj_id' => array('integer', $check_parent_obj_id)
					// 'parent_obj_id' => array('integer', $this->getId())
				)
			);
			if ($this->getUri() != "") { //after cloning or moving
//				$this->plugin->includeClass('../lib/class.lib.php');
//				edusharing_add_instance($this);
				$service = new EduSharingService();
				$usageResult = $service->addInstance($this);
				//die('usageResult = '.$usageResult);
				//ToDo catch
			}
		}
	}

	/**
	 * Update data
	 */
	protected function doUpdate()
	{
		// die URI setzen
		$old_uri = self::lookupUri($this->getId(), $this->getUpperCourse());
		$new_uri = $this->getUri();

		// change of uri not allowed
		if ($old_uri != $new_uri && $old_uri != "") {
			$this->plugin->includeClass("../exceptions/class.ilLfEduSharingResourceException.php");
			throw new ilLfEduSharingResourceException("Update: Change of URI not supported.");
		}

		// if ($old_uri != $new_uri && $old_uri == "" && $new_uri != "")
		// {
//		$this->plugin->includeClass('../lib/class.lib.php');
//		edusharing_add_instance($this);
		$service = new EduSharingService();
		$usageResult = $service->addInstance($this);
		if ($usageResult == false) {
			//delete
			die('usageResult = false');
		}
//		$this->setUsage();
		// }
		//new $service->addInstance and ToDo Check
//		$course_id = $this->getUpperCourse();
//		if ($course_id == 0) {
//			ilLoggerFactory::getLogger('xesr')->warning('set usage: no upper object ref id given.');
//			ilUtil::sendFailure('set usage: no upper object ref id given.');
//		}
//		$usageData = new stdClass();
//		$usageData->containerId = $course_id;
//		$usageData->resourceId = $this->getId();//$id;
//		$usageData->nodeId = $service->utils->getObjectIdFromUrl($this->getUri());  //$this->utils->getObjectIdFromUrl($eduSharing->object_url);
//		$usageData->nodeVersion = $this->getObjectVersion();//$eduSharing->object_version;
		global $DIC;
		$db = $DIC->database();
		$db->update('rep_robj_xesr_usage',
			array(
				'edus_uri' => array('text', $this->getUri()),
				'is_online' => array('integer', $this->getOnline()),
				'object_version' => array('text', $this->getObjectVersion()),
				'object_version_use_exact' => array('integer', $this->getObjectVersionUseExact()),
				'timemodified' => array('timestamp', date('Y-m-d H:i:s'))
			),
			array(
				'id' => array('integer', $this->getId()),
				'parent_obj_id' => array('integer', $this->getUpperCourse())
			)
		);

		return true;
	}

	protected function doClone($new_obj, $a_target_id, $a_copy_id) : void
	{

		global $tree;
		$parent_ref_id = $tree->getParentId($new_obj->getRefId());
		$course_id = ilObject::_lookupObjId($parent_ref_id);
		if (empty($new_obj->course)) {
			$new_obj->course = $course_id;
		}
		$new_obj->setUri($this->getUri());
		$new_obj->setObjectVersion($this->getObjectVersion());
		$new_obj->setObjectVersionUseExact($this->getObjectVersionUseExact());
		$new_obj->setOnline($this->getOnline());

		global $DIC;
		$db = $DIC->database();
		$db->update('rep_robj_xesr_usage',
			array(
				'parent_obj_id' => array('integer', $course_id),
				'edus_uri' => array('text', $this->getUri()),
				'object_version' => array('text', $this->getObjectVersion()),
				'object_version_use_exact' => array('integer', $this->getObjectVersionUseExact()),
				'is_online' => array('integer', $this->getOnline())//,
				//				'timecreated' => array('timestamp', date('Y-m-d H:i:s')),
				//				'timemodified' => array('timestamp', date('Y-m-d H:i:s'))
			),
			array(
				'id' => array('integer', $new_obj->getId()),
				'parent_obj_id' => array('integer', $new_obj->getId())
			)
		);
		$service = new EduSharingService();
		$checkUsage = $service->addInstance($new_obj);
		if (!$checkUsage) {
			die('usage not successful');
		}
	}

	/**
	 * Delete data from db
	 */
	public function doDelete()
	{
		global $DIC;
		//check Verknüpfungen; ToDo simplify
		// deleteAllUsages()
		$query = "SELECT edus_uri, parent_obj_id FROM rep_robj_xesr_usage " .
			" WHERE id = " . $DIC->database()->quote($this->getId(), "integer");
		$result = $DIC->database()->query($query);
		while (($rec = $result->fetchAssoc()) !== false) {
//			edusharing_delete_instance($this->getId(), $rec['edus_uri'], $rec['parent_obj_id']);
			$service = new EduSharingService();
			$service->deleteInstance((string) $rec['edus_uri'], $this->getId(), $rec['parent_obj_id']);
//			$this->deleteUsage($this->getId(), $rec['edus_uri'], $rec['parent_obj_id']);
		}
		$DIC->database()->manipulate("DELETE FROM rep_robj_xesr_usage WHERE " .
			" id = " . $DIC->database()->quote($this->getId(), "integer")
		);
	}

	/**
	 * Lookup uri
	 * @param
	 * @return
	 */
	static function lookupUri($a_id, $a_parent_obj_id)
	{
		global $DIC;
		$set = $DIC->database()->query("SELECT edus_uri FROM rep_robj_xesr_usage " .
			" WHERE id = " . $DIC->database()->quote($a_id, "integer") .
			" AND parent_obj_id = " . $DIC->database()->quote($a_parent_obj_id, "integer")
		);
		$rec = $DIC->database()->fetchAssoc($set);
		return $rec["edus_uri"];
	}

	/**
	 * Do Cloning
	 */
	public function doCloneObject($new_obj, $a_target_id, $a_copy_id = null)
	{
		$this->doClone($new_obj, $a_target_id, $a_copy_id);
	}

	/**
	 * Get ticket
	 * @param
	 * @return
	 */
	public function getTicket() : string
	{
		$eduSharingService = new EduSharingService();
		return $eduSharingService->getTicket();
	}

	// /**
	// * Delete usage
	// *
	// * @param
	// * @return
	// */
	// function deleteAllUsages()
	// {
	// // get edu sharing soap client and a ticket
	// // $this->plugin->includeClass("../lib/class.sigSoapClient.php");
	// $this->plugin->includeClass('../lib/class.lib.php');
	// $this->plugin->includeClass("../lib/class.lfEduUsage.php");

	// $usages = lfEduUsage::getUsagesOfObject($this->getId());
	// foreach ($usages as $u)
	// {
	// if ($u["edus_uri"] != "" && $u["crs_ref_id"] > 0)
	// {
	// edusharing_delete_instance($this);
	// }
	// }
	// }

	/**
	 * Set usage
	 * @param
	 * @return
	 */
	public function setUsage()
	{
//		$this->plugin->includeClass('../lib/class.lib.php');
//		edusharing_add_instance($this);
		$service = new EduSharingService();
		$id = $service->addInstance($this);
		// return true;
	}

	/**
	 * Get upper object
	 * @param
	 * @return
	 */
	public function getUpperCourse()
	{
		global $tree;
		$parent_ref_id = $tree->getParentId($this->getRefId());
		$parent_id = ilObject::_lookupObjId($parent_ref_id);
		return $parent_id;
	}

	/**
	 * Check registered usage
	 * @param
	 * @return
	 */
	function checkRegisteredUsage()
	{
		if ($this->getUri() == "") {
			return false;
		}
		global $DIC;
		$db = $DIC->database();
		$query = "SELECT parent_obj_id FROM rep_robj_xesr_usage WHERE id = " . $db->quote($this->getId(), 'integer');
		$result = $db->query($query);
		while (($row = $result->fetchAssoc()) !== false) {
			if ($row['parent_obj_id'] == $this->getUpperCourse()) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Delete usage
	 */
	public static function deleteUsage($a_obj_id, $a_uri, $a_parent_obj_id) : void
	{
		global $DIC;
		$DIC->database()->manipulate("DELETE FROM rep_robj_xesr_usage " .
			" WHERE id = " . $DIC->database()->quote($a_obj_id, "integer") .
			" AND edus_uri = " . $DIC->database()->quote($a_uri, "text") .
			" AND parent_obj_id = " . $DIC->database()->quote($a_parent_obj_id, "integer")
		);
	}

}
?>
