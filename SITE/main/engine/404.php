<?php
use FFW\HTML\View;
use FFW\PHP\Location;

$loc = new Location();
$loc->setHeader("HTTP/1.0 404 Not Found");

$view = new View();
return $view->get404Error();
?>