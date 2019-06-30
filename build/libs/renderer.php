<?php

class HtmlRenderer implements IRenderer
{
    protected $context;
    
    public function __construct(Context $context)
    {
        $this->context = $context;
    }
    
    /**
     * Renders a view and its whole tree
     *
     * @author Roman Wilhelm <nospam@romanwilhelm.de>
     * @return String the rendered view
     * @todo check the interface in the if-clause, maybe IView or View?
     */
    public function render(IView $view)
    {
        $view->execute($this->context);
        if($view instanceof IComposite) {
            foreach($view->getChildren() as $id => $child) {
                $c = $this->render($child);
                $view->set($id, $c);
            }
        }
        return $view->build();
    }
}

class AjaxRenderer implements IRenderer
{
    protected $context;
    
    public function __construct(Context $context)
    {
        $this->context = $context;
    }
    
    /**
     * Searches for a specific view and renders it and its children
     *
     * @author Roman Wilhelm <nospam@romanwilhelm.de>
     * @todo Clean up code, really ugly
     * @return string[] JSON encoded view
     * @throws LogicException if the requested view could not be found
     */
    public function render(IView $view)
    {
        $requested = $this->context->getRequest()->post->update;
        $requested = "content";
        if($this->context->getResponse()->isRedirect()) { // OMG, most ugly code I've ever seen :D recode this asap
            $redirect = $this->context->getResponse()->getRedirect();
            $this->context->getResponse()->unsetRedirect();
            $this->context->getResponse()->addHeader("Content-Type", "application/json");
            return json_encode(array("redirect", "", $redirect));
        }
        $view->execute($this->context);
        if($view instanceof View) {
            foreach($view->getChildren() as $id => $child) {
                if($id == $requested) {
                    // is no-cache really necessary for post-requests? ..
                    $this->context->getResponse()->addHeader("Cache-Control", "no-cache, must-revalidate");
                    $this->context->getResponse()->addHeader("Expires", "Mon, 26 Jul 1997 05:00:00 GMT");
                    $this->context->getResponse()->addHeader("Last-Modified", gmdate("D, d M Y H:i:s") . "GMT");
                    $this->context->getResponse()->addHeader("Pragma", "no-cache");
                    $this->context->getResponse()->addHeader("Content-Type", "application/json");
                    $built = $this->renderSubset($child);
                    return json_encode(array("update", $child->getID(), $built));
                }
            }
        }
	throw new LogicException("Invalid XmlHttpRequest: View {$requested} could not be found");
    }
    
    /**
     * Renders the subset of a view
     *
     * @author Roman Wilhelm <nospam@romanwilhelm.de>
     * @return String the rendered view
     * @todo check the interface in the if-clause, maybe IComposite or IView?
     */
    protected function renderSubset(IView $view)
    {
        $view->execute($this->context);
        if($view instanceof View) {
            foreach($view->getChildren() as $id => $child) {
                $c = $this->renderSubset($child);
                $view->set($id, $c);
            }
        } 
        return $view->build();
    }
}

?>
