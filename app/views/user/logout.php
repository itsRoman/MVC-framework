<?php

/**
 * Logout View
 * This is only for compatibility issues for browsers without j-s support
 * I acutally dont like this since logging out should be a post/write request thus invoking a view
 *
 * @author Roman Wilhelm <nospam@romanwilhelm.de>
 * @todo really necessary?
 */
class UserLogoutView extends View
{
    public function __construct()
    {
        parent::__construct("logout");
    }
    
    public function execute(Context $context)
    {
        $controller = new UserLogoutController();
        $controller->execute($context);
    }
}

?>