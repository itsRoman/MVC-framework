<?php

/**
 * Base class for all Controllers
 * Actually empty till now, but exists since there may be some need for base functionality in future
 *
 * @author Roman Wilhelm <nospam@romanwilhelm.de>
 */
abstract class Controller implements IController
{
    public function __construct()
    {
        
    }
}

/**
 * Checks authentication and feeds the Request with the result
 *
 * @author Roman Wilhelm <nospam@romanwilhelm.de>
 */
class CheckAuthenticationDecorator implements IController
{
    protected $controller;
    
    public function __construct(IController $controller)
    {
        $this->decorated = $controller;    
    }
    
    public function execute(Context $context)
    {
        $session = $context->getSession();
        if($username = $session->username) {
            $session->authenticated = true;
        } else {
            $session->authenticated = false;
        }
        $this->decorated->execute($context);
        // post processing?
    }
}

class FrontController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Main entry point for the application
     * Routes the request to the according controller/view
     * And delegates to the requested renderer
     *
     * @author Roman Wilhelm <nospam@romanwilhelm.de>
     * @return void
     * @throws InvalidArgumentException if the route configuration is malformed
     * @throws LogicException if a controller returns anything but void or an instance of View
     */
    public function execute(Context $context)
    {
        $module = ucfirst($context->getRequest()->get->module);
        $action = ucfirst($context->getRequest()->get->action);
        $root = new RootView();
        $root->attach(new NavigationView());
        $root->attach(new NewsBoxView());
        if($context->getRequest()->isRead()) {
            $view = $module . $action . "View";
            if(!class_exists($view)) throw new InvalidArgumentException("Invalid request: View {$view} not defined");
            $view = new $view();
        } else {
            $controller = $module . $action . "Controller";
            if(empty($module) || empty($action) || !class_exists($controller)) throw new InvalidArgumentException("Invalid request: Controller {$controller} not defined");
            $controller = new $controller();
            $view = $controller->execute($context);
        }
        if(isset($view)) {
            if(!($view instanceof View)) throw new LogicException("Controller must return instance of View");
            else $root->attach($view, "content");
        }
	if($context->getRequest()->isXmlHttpRequest()) {
            $renderer = new AjaxRenderer($context);
        } else {
            $renderer = new HtmlRenderer($context);
        }
        $context->getResponse()->setContent($renderer->render($root));
    }
    
}

?>