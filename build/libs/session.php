<?php

/**
 * Basic wrapper for a normal HTTP Session
 *
 * @author Roman Wilhelm <nospam@romanwilhelm.de>
 */
class HttpSession implements ISession
{
    public function start()
    {
        session_start();
    }
    
    public function __set($name, $value)
    {
        $_SESSION[$name] = $value;
    }
    
    public function __get($name)
    {
        if(array_key_exists($name, $_SESSION)) return $_SESSION[$name];
        else return null;
    }
    
    public function __isset($name)
    {
        return isset($_SESSION[$name]);
    }
    
    public function __unset($name)
    {
        if(isset($this->$name)) unset($_SESSION[$name]);
    }
    
    public function destroy()
    {
        $_SESSION = array();
        session_destroy();
    }
}

?>