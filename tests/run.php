<?php
define('TEST_TYPE', 'phpunit');

require_once dirname(__FILE__) . '/unit/' . TEST_TYPE .'/bootstrap.php';

run_tests();
