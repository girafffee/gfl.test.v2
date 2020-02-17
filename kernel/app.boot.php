<?php

require_once $mode.'app/config.php';
require_once $mode.'app/base/BaseController.php';
require_once $mode.'app/base/BaseModel.php';
require_once $mode.'kernel/Router.php';


$page = "<!DOCTYPE html>\n<html>";
$page .= "\n<head>\n";
$page .= getHead();
$page .= "\n</head>\n";
$page .= "\n<body data-spy=\"scroll\">\n";
$page .= getHeader();
$page .= callController();
$page .= getFooter();
$page .= "\n</body></html>";
