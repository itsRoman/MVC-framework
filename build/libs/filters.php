<?php

class GPCFilter implements IFilter
{
    public function __construct()
    {
        
    }
    
    public function process(IDataspace $dataspace)
    {
        $dataspace->import(new Parameters($this->cleanup($dataspace->export())));
    }
    
    protected function cleanup($parameters)
    {
        $temp = array();
        foreach($parameters as $key => $value) { 
            if(get_magic_quotes_gpc()) { 
                if(is_array($value)) {
                    $value = $this->cleanup($value);
                } else {
                    if(is_string($value)) {
                        $value = stripslashes($value);
                    }
                } 
            }
            $temp[$key] = $value;
        }
        return $temp;
    }
}

?>