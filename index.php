<?php

/**
 *
 * index.php, this is the global entry point
 * it just delegates the request to ./build/index.php,
 * which processes the request
 *
 */

// maybe include this in the environment ?
error_reporting(E_ALL | E_STRICT);

require("./build/index.php");

?>
