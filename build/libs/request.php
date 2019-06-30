<?php

class HttpRequest extends Dataspace implements IRequest, IFilterable
{
    protected $filters;
    
    public function __construct()
    {
        $this->filters = array();
        $this->set("get", new Parameters($_GET));
        $this->set("post", new Parameters($_POST));
    }
    
    public function getUri()
    {
        return $_SERVER["REQUEST_URI"];
    }
    
    public function getQuery()
    {
        return isset($_GET["query"]) ? $_GET["query"] : "";
        
    }
    public function getPort()
    {
        return $_SERVER["HTTP_PORT"];
    }
        
    public function getHost()
    {
        return $_SERVER["HTTP_HOST"];
    }
        
    public function getRequestMethod()
    {
        return (strcasecmp($_SERVER["REQUEST_METHOD"], "POST") == (int)0) ? "POST" : "GET";
    }

    public function isRead()
    {
        return $this->getRequestMethod() == "GET";
    }

    public function isWrite()
    {
        return $this->getRequestMethod() == "POST";
    }
	
    // TODO: add implementation
    public function isXmlHttpRequest()
    {
	if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"] == "XMLHttpRequest") {
            return true;
        } else return false;
    }
    
    public function addFilter(IFilter $filter)
    {
        array_push($this->filters, $filter);
    }
    
    public function process()
    {
        foreach($this->filters as $filter) {
            $filter->process($this);
        }
    }
}

?>