<?php

class Context
{
    protected $env;
    protected $request;
    protected $response;
    protected $session;
    protected $db;
    protected $config;
    
    public function __construct(Environment $env = null)
    {
        $this->env = $env;
    }
    
    public function setRequest(IRequest $request)
    {
        $this->request = $request;
    }
    
    public function setResponse(IResponse $response)
    {
        $this->response = $response;
    }
    
    public function setSession(ISession $session)
    {
        $this->session = $session;
    }
    
    public function setConnection(PDO $db)
    {
        $this->db = $db;
    }
    
    public function setConfiguration(IConfiguration $config)
    {
        $this->config = $config;
    }
    
    public function getRequest()
    {
        return $this->request;
    }
    
    public function getResponse()
    {
        return $this->response;
    }
    
    public function getSession()
    {
        return $this->session;
    }
    
    public function getConnection()
    {
        return $this->db;
    }
    
    public function getConfiguration()
    {
        return $this->config;
    }

    /**
     * Autoloader
     * Trys to load classes according to a simple naming convention
     *
     * @author Roman Wilhelm <nospam@romanwilhelm.de>
     * @todo maybe make autoloader depending on the environment?
     * @return boolean whether the class was found
     */
    public static function autoload($className)
    {
        $path = (string)"";
        if(preg_match_all("#[A-Z]{1}[a-z_]*#", $className, $matches))  {
            $family = array_pop($matches[0]);
            switch($family) {
                case "View":
                    $path = "./app/views/";
                    break;
                case "Controller":
                    $path = "./app/controllers/";
                    break;
                case "Mapper":
                    $path = "./app/models/";
                    break;
                case "Statement":
                    $path = "./app/models/statements/";
                    break;
                case "Form":
                    $path = "./app/forms/";
                    break;
                default:
                    return false;
            }
            $filename = strtolower(array_pop($matches[0]));
            foreach($matches[0] as $dir) {
                $path .= (strtolower($dir) . "/");
            }
            $path .= $filename . PHP_EXT;
        } else return false;
        if(!file_exists($path)) return false;
        require($path);
        return true;
    }
}

?>