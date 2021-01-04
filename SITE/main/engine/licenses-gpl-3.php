<?php
use FFW\HTML\View;


$t = "The GNU General Public License v3.0";
$d = "Project Overground Open Source PHP Software.";
$k = "";
$arrMainView['metaTitle'] = $t;
$arrMainView['metaDescription'] = $d;
$arrMainView['metaKeywords'] = $k;

$view = new View();
$view->setSinglePage();
$view->addArrayMain($arrMainView);
return $view->getMedContent(array(),'licenses-gpl-3');
?>