<?php

// Sicherstellen, dass die globale Datenbankinstanz verfügbar ist
global $ilDB;

// <#1> Erstellung der Tabelle 'rep_robj_xesr_data' mit Primärschlüssel 'id'
if (!$ilDB->tableExists('rep_robj_xesr_data')) {
    $fields = array(
        'id' => array(
            'type' => 'integer',
            'length' => 4,
            'notnull' => true
        ),
        'edus_uri' => array(
            'type' => 'text',
            'length' => 1000,
            'fixed' => false,
            'notnull' => false
        )
    );

    $ilDB->createTable("rep_robj_xesr_data", $fields);
    $ilDB->addPrimaryKey("rep_robj_xesr_data", array("id"));
}

// <#2> Hinzufügen der Spalte 'is_online' zu 'rep_robj_xesr_data'
if (!$ilDB->tableColumnExists('rep_robj_xesr_data', 'is_online')) {
    $ilDB->addTableColumn("rep_robj_xesr_data", "is_online", array(
        'type' => 'integer',
        'length' => 4,
        'notnull' => true
    ));
}

// <#3> Erstellung der Tabelle 'rep_robj_xesr_usage' mit Index auf 'id'
if (!$ilDB->tableExists('rep_robj_xesr_usage')) {
    $fields = array(
        'id' => array(
            'type' => 'integer',
            'length' => 4,
            'notnull' => true
        ),
        'edus_uri' => array(
            'type' => 'text',
            'length' => 1000,
            'fixed' => false,
            'notnull' => false
        ),
        'crs_ref_id' => array(
            'type' => 'integer',
            'length' => 4,
            'notnull' => true
        )
    );

    $ilDB->createTable("rep_robj_xesr_usage", $fields);
    $ilDB->addIndex("rep_robj_xesr_usage", array("id"), "i1");
}

// <#4> Erstellung der Tabelle 'rep_robj_xesp_usage'
if (!$ilDB->tableExists('rep_robj_xesp_usage')) {
    $fields = array(
        'id' => array(
            'type' => 'integer',
            'length' => 4,
            'notnull' => true
        ),
        'edus_uri' => array(
            'type' => 'text',
            'length' => 1000,
            'fixed' => false,
            'notnull' => false
        ),
        'obj_id' => array(
            'type' => 'integer',
            'length' => 4,
            'notnull' => true
        )
    );

    $ilDB->createTable("rep_robj_xesp_usage", $fields);
}

// <#5> Hinzufügen des Primärschlüssels zu 'rep_robj_xesp_usage'
try {
    // ILIAS ilDB bietet keine direkte Methode zur Überprüfung von Primärschlüsseln,
    // daher versuchen wir einfach, den Primärschlüssel hinzuzufügen und fangen Fehler ab, falls er bereits existiert
    $ilDB->addPrimaryKey("rep_robj_xesp_usage", array("id", "parent_obj_id"));
} catch (Exception $e) {
    // Primärschlüssel existiert bereits oder ein anderer Fehler ist aufgetreten
    // Hier kannst du optional Logging hinzufügen
}

// <#6> Erstellung der Sequenz 'rep_robj_xesp_usage'
if (!$ilDB->sequenceExists("rep_robj_xesp_usage")) {
    $ilDB->createSequence("rep_robj_xesp_usage");
}

// <#7> Hinzufügen mehrerer Spalten zu 'rep_robj_xesp_usage'
$additionalColumns = array(
    'active' => array(
        'type' => 'integer',
        'length' => 1,
        'notnull' => true,
        'default' => 1
    ),
    'mimetype' => array(
        'type' => 'text',
        'length' => 100,
        'notnull' => false
    ),
    'object_version' => array(
        'type' => 'text',
        'length' => 32,
        'notnull' => false
    ),
    'object_version_use_exact' => array(
        'type' => 'integer',
        'length' => 1,
        'notnull' => true,
        'default' => 1
    ),
    'window_float' => array(
        'type' => 'text',
        'length' => 6,
        'notnull' => false
    ),
    'window_width_org' => array(
        'type' => 'integer',
        'length' => 2,
        'notnull' => false
    ),
    'window_height_org' => array(
        'type' => 'integer',
        'length' => 2,
        'notnull' => false
    ),
    'window_width' => array(
        'type' => 'integer',
        'length' => 2,
        'notnull' => false
    ),
    'window_height' => array(
        'type' => 'integer',
        'length' => 2,
        'notnull' => false
    ),
    'timecreated' => array(
        'type' => 'timestamp',
        'notnull' => true
    ),
    'timemodified' => array(
        'type' => 'timestamp',
        'notnull' => true
    )
);

foreach ($additionalColumns as $column => $definition) {
    if (!$ilDB->tableColumnExists('rep_robj_xesp_usage', $column)) {
        $ilDB->addTableColumn('rep_robj_xesp_usage', $column, $definition);
    }
}

// <#8> Hinzufügen der Spalte 'object_version' zu 'rep_robj_xesr_usage'
if (!$ilDB->tableColumnExists('rep_robj_xesr_usage', 'object_version')) {
    $ilDB->addTableColumn('rep_robj_xesr_usage', 'object_version', array(
        'type' => 'text',
        'length' => 32,
        'notnull' => false
    ));
}

// <#9> Hinzufügen der Spalte 'object_version_use_exact' zu 'rep_robj_xesr_usage'
if (!$ilDB->tableColumnExists('rep_robj_xesr_usage', 'object_version_use_exact')) {
    $ilDB->addTableColumn('rep_robj_xesr_usage', 'object_version_use_exact', array(
        'type' => 'integer',
        'length' => 1,
        'notnull' => true,
        'default' => 1
    ));
}

// <#10> Entfernen und Hinzufügen von Indizes und Spalten zu 'rep_robj_xesr_usage'
if ($ilDB->indexExistsByFields('rep_robj_xesr_usage', array('id'))) {
    $ilDB->dropIndexByFields('rep_robj_xesr_usage', array('id'));
}

$columnsToAdd = array(
    'parent_obj_id' => array(
        'type' => 'integer',
        'length' => 4,
        'notnull' => true,
        'default' => 0
    ),
    'is_online' => array(
        'type' => 'integer',
        'length' => 1,
        'notnull' => true,
        'default' => 1
    )
);

foreach ($columnsToAdd as $column => $definition) {
    if (!$ilDB->tableColumnExists('rep_robj_xesr_usage', $column)) {
        $ilDB->addTableColumn('rep_robj_xesr_usage', $column, $definition);
    }
}

// <#11> Hinzufügen von Primärschlüsseln und Löschen von Daten zu 'rep_robj_xesr_usage'
if ($ilDB->tableColumnExists('rep_robj_xesr_usage', 'parent_obj_id')) {
    // Löschen von Datensätzen mit id=0, falls vorhanden
    $ilDB->manipulate("DELETE FROM rep_robj_xesr_usage WHERE id=0");

    // Hinzufügen des Primärschlüssels, falls noch nicht vorhanden
    try {
        $ilDB->addPrimaryKey("rep_robj_xesr_usage", array("id", "parent_obj_id"));
    } catch (Exception $e) {
        // Primärschlüssel existiert bereits oder ein anderer Fehler ist aufgetreten
        // Optional: Logging hinzufügen
    }
}

// <#12> Hinzufügen von Zeitstempeln zu 'rep_robj_xesr_usage'
$timestampColumns = array(
    'timecreated' => array(
        'type' => 'timestamp',
        'notnull' => true
    ),
    'timemodified' => array(
        'type' => 'timestamp',
        'notnull' => true
    )
);

foreach ($timestampColumns as $column => $definition) {
    if (!$ilDB->tableColumnExists('rep_robj_xesr_usage', $column)) {
        $ilDB->addTableColumn('rep_robj_xesr_usage', $column, $definition);
    }
}

// <#13> Erstellung der Tabelle 'rep_robj_xesr_users' mit Primärschlüssel 'usr_id'
if (!$ilDB->tableExists('rep_robj_xesr_users')) {
    $fields = array(
        'usr_id' => array(
            'type' => 'integer',
            'length' => 4,
            'notnull' => true,
            'default' => 0
        ),
        'usr_ident' => array(
            'type' => 'text',
            'length' => 255,
            'notnull' => false
        )
    );

    $ilDB->createTable('rep_robj_xesr_users', $fields);
    $ilDB->addPrimaryKey('rep_robj_xesr_users', array('usr_id'));
}

?>
