<?php

class NotEmptyRule implements IRule
{
    public function isSatisfiedBy($value)
    {
        return (isset($value) && (trim($value) != ""));
    }
    
    public function getErrorMessage()
    {
        return "Dieses Feld darf nicht leer sein.";
    }
}

class IsNumericalRule implements IRule
{
    public function isSatisfiedBy($value)
    {
        return ctype_digit($value);
    }
    
    public function getErrorMessage()
    {
        return "Bitte nur Zahlen eingeben.";
    }
}

class IsAlphaRule implements IRule
{
    public function isSatisfiedBy($value)
    {
        return ctype_alpha($value);
    }
    
    public function getErrorMessage()
    {
        return "Bitte nur Buchstaben eingeben.";
    }
}

class IsAlphaNumericalRule implements IRule
{
    public function isSatisfiedBy($value)
    {
        return ctype_alnum($value);
    }
    
    public function getErrorMessage()
    {
        return "Bitte nur Zahlen und/oder Buchstaben eingeben.";
    }
}

class HasDefinedLengthRule implements IRule
{
    protected $min, $max;
    
    public function __construct($min, $max)
    {
        $this->min = (int)$min;
        $this->max = (int)$max;
    }
    
    public function isSatisfiedBy($value)
    {
        return ((strlen($value) >= $this->min) && (strlen($value) <= $this->max));
    }
    
    public function getErrorMessage()
    {
        return "Bitte mindestens {$this->min} Zeichen und maximal {$this->max} Zeichen eingeben.";
    }
}

class EqualsRule implements IRule
{
    protected $comparison;
    
    public function __construct(FormElement $comparison)
    {
        $this->comparison = $comparison;
    }
    
    public function isSatisfiedBy($value)
    {
        return ((string)$this->comparison->value == (string)$value);
    }
    
    public function getErrorMessage()
    {
        return "Bitte exakt das {$this->comparison->label} wiederholen.";
    }
}

class RegexRule implements IRule
{
    protected $regex;
    protected $errorMsg;
    
    public function __construct($regex, $errorMsg)
    {
        $this->regex = $regex;
        $this->errorMsg = $errorMsg;
    }
    
    public function isSatisfiedBy($value)
    {
        return preg_match($this->regex, $value);
    }
    
    public function getErrorMessage()
    {
        return $this->errorMsg;
    }
}

class IsEmailRule extends RegexRule
{
    public function __construct()
    {
        parent::__construct("#^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$#i", "Bitte eine Email im Format user@provider.tld eingeben");
    }
}

// TODO:
// Is this really a valid (syntactical) Validation, or is this essentially a semantic rule,
// thus belonging to the controller?
// this would imply, that the controller can inject error messages not only in the form but also
// in the various form elements.
class UniqueUsernameRule implements IRule
{
    protected $mapper;
    
    public function __construct(IMapper $mapper)
    {
        $this->mapper = $mapper;
    }
    
    public function isSatisfiedBy($value)
    {
        return !$this->mapper->retrieveByName($value);
    }
    
    public function getErrorMessage()
    {
        return "Der Username ist leider schon vergeben.";
    }
}

?>