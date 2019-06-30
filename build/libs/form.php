<?php

abstract class Form extends View
{
    protected $action;
    protected $method;
    protected $executed;
    protected $errors;
    
    public function __construct($id, $action)
    {
        parent::__construct($id);
        $this->action = url($action);
        $this->method = "post";
        $this->executed = false;
        $this->errors = array();
        $this->describe();
    }
    
    /**
     * Attaches a form element to the form
     *
     * @return void
     */
    public function attach(IIdentifiable $element)
    {
        $this->children[$element->getID()] = $element;
    }

    /**
     * Executes the form
     * If we have a post/write request each element gets the related value
     *
     * @author Roman Wilhelm <nospam@romanwilhelm.de>
     * @todo check the possible double execution, I dont like this
     * @return void
     */
    public function execute(Context $context)
    {
        $this->set("id", $this->id);
        $this->set("action", $this->action);
        $this->set("method", $this->method);
        $this->set("elements", array_keys($this->children));
        $this->set("errors", $this->errors);
        if(!$this->executed)
        {
            if($context->getRequest()->isWrite()) {
                $this->fill($context->getRequest()->post->export());
            }
            $this->executed = true;
        }
    }
    
    /**
     * Fills the attached elements of a form with associated content from the post request
     * (in the $input array)
     *
     * @return void
     */
    public function fill(array $input)
    {
        foreach($this->children as $element) {
            if(array_key_exists($element->getID(), $input) && ($value = $input[$element->getID()])) {
                if($element instanceof Checkbox) $element->checked = "checked";
                $element->value = $value;
            } else {
                $element->value = (string)"";
            }
        }
    }
    
    /**
     * Renders a form
     *
     * @author Roman Wilhelm <nospam@romanwilhelm.de>
     * @todo maybe log in any way if we use the generic template ? (dont think so but think about it once again)
     * @return String the rendered form
     */
    public function build()
    {
        try {
            $template = new Template($this->getTemplatePath());
            $template->import($this);
            return $template->render();
        } catch(InvalidArgumentException $e) {
            $template = new Template("form/form");
            $template->import($this);
            return $template->render();
        }
    }
    
    protected function getTemplatePath()
    {
        if(!empty($this->template)) $path = $this->template;
        else {
            $view = substr(get_class($this), 0, -4);
            $path = (string)"";
            if(preg_match_all("#[A-Z]{1}[a-z_]*#", $view, $matches)) {
                $filename = strtolower(array_pop($matches[0]));
                foreach($matches[0] as $dir) {
                    $path .= (strtolower($dir) . "/");
                }
            }
            $path .= $filename;
        }
        return "forms/{$path}";
    }
    
    /**
     * Validates a request based on the elements rules
     * Errors are directly injected into the form elements
     *
     * @return boolean valid
     */
    public function validate($context)
    {
        $this->execute($context);
        $valid = true;
        foreach($this->children as $element) {
            foreach($element->getRules() as $rule) {
                if(!$rule->isSatisfiedBy($element->value)) {
                    $element->setError($rule->getErrorMessage());
                    $valid = false;
                }
            }
            // All passwords should always be resetted after form submission
            if($element instanceof Input && $element->type == "password") $element->value = (string)"";
        }
        return $valid;
    }
    
    /**
     * Sets global form errors that do not relate to a specific
     * form element
     *
     * @return void
     */
    public function setError($error)
    {
        $this->errors[] = $error;
    }
    
    abstract protected function describe();
}

class FormElement extends Dataspace implements IView
{
    protected $id;
    protected $template;
    protected $events;
    protected $errors;
    protected $rules;
    
    public function __construct($id)
    {
        parent::__construct();
        $id = trim((string)$id);
        if(!preg_match("#^[A-Za-z_]+$#", $id)) throw new UnexpectedValueException("Invalid ID ({$id})");
        $this->id = strtolower($id);
        $this->events = array();
        $this->errors = array();
        $this->rules = array();
    }
    
    public function getID()
    {
        return $this->id;
    }
    
    public function addRule(IRule $rule)
    {
        array_push($this->rules, $rule);
    }
    
    public function getRules()
    {
        return $this->rules;
    }
    
    /**
     * Adds validation error message to a form element
     *
     * @author Roman Wilhelm <nospam@romanwilhelm.de>
     * @return void
     */
    public function setError($error)
    {
        $this->errors[] = $error;
    }
    
    public function execute(Context $context)
    {
        $this->set("id", $this->id);
        $this->set("events", $this->events);
        $this->set("errors", $this->errors);
    }
    
    /**
     * Renders a form element
     *
     * @author Roman Wilhelm <nospam@romanwilhelm.de>
     * @todo some if-clause if template wasnt set ?
     * @returns String the rendered form element
     */
    public function build()
    {
        try {
            $template = new Template($this->template);
            $template->import($this);
            return $template->render();
        } catch(InvalidArgumentException $e) {
            return (string)"";
        }
    }
    
    /**
     * Registers an event to a form element
     *
     * @author Roman Wilhelm <nospam@romanwilhelm.de>
     * @returns void
     */
    public function register(IEvent $event)
    {
        $this->events[$event->getName()] = $event;
    }
}

class Input extends FormElement
{
    public function __construct($id)
    {
        parent::__construct($id);
        $this->template = "form/input";
    }
}

class Checkbox extends Input
{
    public function __construct($id)
    {
        parent::__construct($id);
        $this->type = "checkbox";
    }
}

class Submit extends Input
{
    public function __construct()
    {
        parent::__construct("submit");
        $this->template = "form/submit";
    }
}

class Textarea extends FormElement
{
    public function __construct($id)
    {
        parent::__construct($id);
        $this->template = "form/textarea";
    }
}

?>