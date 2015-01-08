<?php

/**
 * Insert data from XMLs to database
 */

$import = new DatabaseImport;
$import->import();
$import->removeFile();
