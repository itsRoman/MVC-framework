<?php

class Template extends Dataspace
{
    protected $template;
    
    public function __construct($tplFile)
    {
        parent::__construct();
        $path = "./app/templates/";
        $filename = $tplFile;
        if(preg_match_all("#[A-Z]{1}[a-z_]*#", $tplFile, $matches)) {
            $filename = strtolower(array_pop($matches[0]));
            foreach($matches[0] as $dir) {
                $path .= (strtolower($dir) . "/");
            }
        }
        $path .= $filename . TPL_EXT;
        if(!file_exists($path)) throw new InvalidArgumentException("Invalid template specified: Does not exist ({$path})");
        $this->template = $path;
    }
    
    public function render()
    {
        extract($this->export(), EXTR_OVERWRITE);
        ob_start();
        include($this->template); // @ to oppress errors, use in production !
        return ob_get_clean();
    }
}

?>