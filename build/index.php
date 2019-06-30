<?php

function debug($var, $echo = true)
{
    $s = "<pre style=\"margin-top:0.5em;margin-bottom:0.5em;background-color:#ff0;font-size:1.2em;font-weight:bold;color:#000;line-height:1.8em;\">";
    $s .= var_export($var, true);
    $s .= "</pre>";
    if($echo) echo $s;
    else return $s;
}

ob_start();

require("./build/init/import.php");

$context = new Context();

spl_autoload_register(array($context, "autoload"));

$request = new HttpRequest();
$request->addFilter(new GPCFilter());
$request->process();

$response = new HttpResponse();

$session = new HttpSession();
$session->start();

$router = new Router("./app/configs/routes.php", true);
$router->parse($request);

$db = new DBConnection("./app/configs/pdo.php");

$context->setRequest($request);
$context->setResponse($response);
$context->setSession($session);
$context->setConnection($db);

$frontController = new FrontController();
$checkAuthenticationDecorator = new CheckAuthenticationDecorator($frontController);
try {
    $checkAuthenticationDecorator->execute($context);
    $response->send();
} catch(Exception $e) {
    debug($e, true);
}

echo ob_get_clean();

/*$buffer = ob_get_clean();
if(preg_match("#^(.+)<!DOCTYPE#s", $buffer, $matches)) {
    $buffer = preg_replace("#^(.+)<!DOCTYPE#s", "<!DOCTYPE", $buffer);
    echo preg_replace("#<div id=\"content\">(.*)</div>#sU", "<div id=\"content\">{$matches[1]}$1</div>", $buffer);
} else echo $buffer;*/

//echo count(get_required_files()); // debug
?>
