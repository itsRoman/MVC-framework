<?php

/**
 * Router, thus routing requests to the according module/action
 * Can handle both normal requests and rewritten (mod_rewrite)
 *
 * @author Roman Wilhelm <nospam@romanwilhelm.de>
 */
class Router implements IRouter
{
    protected $routes;
    protected $rewritten;
    
    public function __construct($config, $rewritten)
    {
        if(!file_exists($config)) throw new InvalidArgumentException("Config {$config} does not exist.");
        $routes = require($config);
        if(empty($routes) || !is_array($routes)) throw new LogicException("Config {$config} must return array.");
        $this->routes = $routes;
        $this->rewritten = (bool)$rewritten;
    }
    
    public function parse(IRequest $request)
    {
        if(!$this->rewritten) {
            $module = $request->get->module;
            $action = $request->get->action;
            if(empty($module)) {
                $module = DEFAULT_MODULE;
                $action = DEFAULT_ACTION;
            }
            $request->get->module = $module;
            $request->get->action = $action;
        } else {
            $query = $request->getQuery();
            $query = trim($query, "/") . "/";
            $hit = false;
            foreach($this->routes as $route => $attributes) {
                if($this->match($query, $route, $attributes)) {
                    $request->get->import(new Parameters($attributes));
                    $hit = true;
                    break;
                }
            }
            if(!$hit) {
                $request->get->module = DEFAULT_MODULE;
                $request->get->action = DEFAULT_ACTION;
            }
        }
    }
    
    /**
     * Returns whether the query matches a specific route
     * If true, the placeholders in the route (:placeholder) are
     * injected in the route attributes
     *
     * @return boolean does the query match the route
     */
    protected function match($query, $route, &$attributes)
    {
        $route = trim($route, "/") . "/";
        $transformed = preg_replace("#:\w+/#", "([A-Za-z0-9_\-+?]+)/", $route);
        if(is_null($transformed)) $transformed = $route;
        if(preg_match("#^" . $transformed . "$#", $query, $queryParts) && (preg_match_all("#:(\w+)/#", $route, $routeParts) || true)) {
            array_shift($queryParts);
            $routeParts = $routeParts[1];
            for($i = 0;$i < count($routeParts);$i++) {
                $attributes[$routeParts[$i]] = $queryParts[$i];    
            }
            return true;
        }
        return false;
    }
}

?>
