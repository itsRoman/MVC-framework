<?php

/**
 * Basic view, all views extend from this
 * Implements the composite pattern, thus the site is actually a tree of views
 *
 * @author Roman Wilhelm
 */
abstract class View extends Dataspace implements IView, IComposite
{
    protected $id;
    protected $delegate;
    protected $children;
    protected $template;
    
    public function __construct($id)
    {
        $this->setID($id);
        $this->delegate = null;
        $this->children = array();
        $this->template = null;
        parent::__construct();
    }
    
    /**
     * Attaches a view to the tree
     *
     * @todo clean up the interface mess, IView, IIdentifiable, View etc ..
     * @return void
     */
    public function attach(IIdentifiable $child, $alias = null)
    {
        if(isset($alias) && preg_match("#^[A-Za-z_]+$#", $alias)) $id = $alias;
        else $id = $child->getID();
        $this->children[$id] = $child;
    }
    
    public function setID($id)
    {
        $id = trim((string)$id);
        if(!preg_match("#^[A-Za-z-_]+$#", $id)) throw new UnexpectedValueException("Invalid ID ({$id})"); 
        $this->id = strtolower($id);
    }
    
    public function getID()
    {
        return $this->id;
    }
    
    public function getChildren()
    {
        return $this->children;
    }
    
    public function hasChildren()
    {
        return (count($this->children) > 0);
    }
    
    /**
     * Renders the view
     *
     * @throws UnexpectedArgumentException if view is delegated to a non-view
     * @return string the rendered view
     */
    public function build()
    {
        if(isset($this->delegate)) {
	    if(!($this->delegate instanceof View)) throw new UnexpectedArgumentException("Delegate must be instance of View");
	    else return $this->delegate->build();
	} else {
            try {
                $template = new Template($this->getTemplatePath());
                $template->import($this);
                return $template->render();
            } catch(InvalidArgumentException $e) {
                return (string)"";
            }
        }
    }
    
    /**
     * Delegates the rendering to an other view
     *
     * @throws InvalidArgumentException if the associated view file cant be found
     * @throws LogicException if the passed view name is malformed
     * @return boolean success
     */
    protected function delegate($view, Context $context)
    {
        $view = ucfirst($view);
        $path = (string)"";
        $filename = $view;
        if(preg_match_all("#[A-Z]{1}[a-z_]*#", $view, $matches)) {
            $path .= "./app/views/";
            $filename = strtolower(array_pop($matches[0]));
            foreach($matches[0] as $dir) {
                $path .= (strtolower($dir) . "/");
            }
        }
        $path .= $filename . PHP_EXT;
        $name = ucfirst($view) . "View";
        if(!file_exists($path)) throw new InvalidArgumentException("Invalid View specified: Does not exist ({$name})");
        require($path);
        if(!class_exists($name)) throw new LogicException("Internal error: Malformed View name ({$name})");
        $instance = new $name();
        $instance->execute($context);
        $this->delegate = $instance;
        return true;
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
        return $path;
    }
}

?>
