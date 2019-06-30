<?php

class HttpResponse extends Dataspace implements IResponse, IFilterable
{
    protected $headers;
    protected $redirect;
    protected $protocol;
    protected $contentType;
    protected $encoding;
    protected $filters;
    
    public function __construct()
    {
        $this->headers = array();
        $this->redirect = false;
        $this->protocol = empty($_SERVER["SERVER_PROTOCOL"]) ? "HTTP/1.1" :  $_SERVER["SERVER_PROTOCOL"];
        $this->contentType = "text/html";
        $this->encoding = "UTF-8";
        $this->filters = array();
    }
    
    public function setContent($content)
    {
        $this->set("content", (string)$content);
    }
    
    public function addHeader($key, $value)
    {
        if($key == "Content-Type") {
            if(preg_match("#^(.*);\w*charset\w*=\w*(.*)#", $value, $matches)) {
                $this->contentType = $matches[1];
                $this->encoding = $matches[2];
            } else $this->contentType = $value;
        } else $this->headers[$key] = $value;
    }
    
    public function deleteHeader($key)
    {
        if(array_key_exists($key, $this->headers)) {
            unset($this->headers[$key]);
            return true;
        } else return false;
    }
    
    public function setRedirect($url)
    {
        $this->addHeader("Status", 302);
        $this->addHeader("Location", $url);
        $this->redirect = $url;
    }
    
    public function unsetRedirect()
    {
        $this->deleteHeader("Status");
        $this->deleteHeader("Location");
        $this->redirect = false;
    }
    
    public function getRedirect()
    {
        return $this->redirect;
    }
    
    public function isRedirect()
    {
        return $this->redirect !== false;
    }
    
    public function send()
    {
        if(session_id()) session_write_close();
        $this->process();
        $this->sendHeaders();
        echo($this->getContent());
    }
    
    protected function getContent()
    {
        return $this->get("content");
    }
    
    protected function sendHeaders()
    {
        if(isset($this->contentType)) {
            if(isset($this->encoding)) {
                header("Content-Type: {$this->contentType}; charset={$this->encoding}");
            } else {
                header("Content-Type: {$this->contentType}");
            }
        }
        foreach($this->headers as $key => $value) {
            header("{$key}: {$value}");
        }
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