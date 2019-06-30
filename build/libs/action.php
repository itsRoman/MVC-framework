<?php

interface IAction
{
    public function __toString();
}

class AjaxRequestAction implements IAction
{
    protected $action;
    protected $method;
    protected $parameters;
    
    public function __construct($action, $method = "post", $parameters = null)
    {
        $this->action = $action;
        $this->method = in_array($method, array("post", "get")) ? $method : "post";
        $this->parameters = $this->formatParameters($parameters);
    }
    
    /**
     * How this action should be rendered
     *
     * @author Roman Wilhelm <nospam@romanwilhelm.de>
     * @todo Remove this html code, put in some template file or sth
     */
    public function __toString()
    {
        $action = "javascript:new Ajax.Request('{$this->action}', {method: '{$this->method}', ";
        if(!empty($this->parameters)) {
            $action .= "parameters: {$this->parameters}, ";
        }
        $action .= "onSuccess: function(response){execResponse(response);}, onFailure: function(){ alert('ERROR IN SCRIPT.');}});return false;";
        return $action;
    }
    
    protected function formatParameters($parameters = null)
    {
        if(!isset($parameters)) return (string)"";
        if(!is_array($parameters)) $parameters = array($parameters);
        $str = (string)"";
        foreach($parameters as $parameter) {
            if(is_scalar($parameter)) $str .= $parameter;
            elseif($parameter instanceof Form) {
                $str .= "\$('{$parameter->getID()}').serialize(true)";
            }
        }
        return $str;
    }
}

class PromptAjaxRequestAction extends AjaxRequestAction
{
    public function __toString()
    {
        $action = "javascript:prmt('{$this->action}', '{$this->method}');return false;";
        return $action;
    }
}

?>
