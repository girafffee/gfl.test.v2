<?php

define('SITE_URL', 'http://gfl.test.v2');

define('PATH_TO_TEMPLATES', $mode.'public/templates/');
define('PATH_TO_TEMPLATES_ERRORS', PATH_TO_TEMPLATES.'errors/');

define('PATH_TO_CONTROLLERS', $mode.'app/controllers/');
define('PATH_TO_LAY_CONTROLLERS', $mode.'app/layouts/');

define('PATH_TO_MODELS', $mode.'app/models/');

define('DEFAULT_ACTION', 'index');

define('ACTION_PREFIX', 'action');

define('BOOK_STATUS_DELETED', 'deleted');
define('BOOK_STATUS_ACTIVE', 'active');

define('ADMIN_LOGIN', 'admin');

/**
 * <DATABASE>
 */
define('DB_HOST', 'localhost');
define('DB_USER', 'gfl.test');
define('DB_NAME', 'gfl.test');
define('DB_PASS', '*yBC$Pb89wm!aEY');
/**
 * </DATABASE>
 */

define('PATH_TO_MAILER', $mode.'vendor/phpmailer/phpmailer');

/** MAILER */
define('ADDRESS_FROM', 'noreply.girafffee@gmail.com');
define('ADDRESS_PASS','SaNkO20001221');
define('ADDRESS_ADMIN', 'sanko200065@gmail.com');
/** /MAILER */
