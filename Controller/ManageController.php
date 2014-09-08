<?php

/**
 * ManageController
 *
 * Default controller of ContentManagementBundle
 *
 * @vendor          BiberLtd
 * @package         ContentManagementMBundle
 * @subpackage      Controller
 * @name	    ManageController
 *
 * @author          Can Berkol
 *
 * @copyright       Biber Ltd. (www.biberltd.com)
 *
 * @version         1.0.0
 * @date            01.08.2013
 *
 */

namespace BiberLtd\Bundle\ContentManagementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpKernel\Exception,
    Symfony\Component\HttpFoundation\Response;

class ManageController extends Controller {

    /**
     * @name 		pageListAction()
     * List pages 
     *
     * @since		1.0.6
     * @version         1.0.6
     * @author          Said İmamoğlu
     *
     * @use             $this->doesProductCategoryExist()
     * @use             $this->createException()
     *
     * @param           array           $collection      Collection of Product entities or array of entity details.
     * @param           array           $by              entity, post
     *
     * @return          array           $response
     */
    public function pageListAction() {
        
    }
    /**
     * @name 		pageEditAction()
     * List pages 
     *
     * @since		1.0.6
     * @version         1.0.6
     * @author          Said İmamoğlu
     *
     * @use             $this->doesProductCategoryExist()
     * @use             $this->createException()
     *
     * @param           array           $collection      Collection of Product entities or array of entity details.
     * @param           array           $by              entity, post
     *
     * @return          array           $response
     */
    public function pageEditAction() {
        
    }
    /**
     * @name 		pageNewAction()
     * List pages 
     *
     * @since		1.0.6
     * @version         1.0.6
     * @author          Said İmamoğlu
     *
     * @use             $this->doesProductCategoryExist()
     * @use             $this->createException()
     *
     * @param           array           $collection      Collection of Product entities or array of entity details.
     * @param           array           $by              entity, post
     *
     * @return          array           $response
     */
    public function pageNewAction() {
        
    }
    /**
     * @name 		pageDeleteAction()
     * List pages 
     *
     * @since		1.0.6
     * @version         1.0.6
     * @author          Said İmamoğlu
     *
     * @use             $this->doesProductCategoryExist()
     * @use             $this->createException()
     *
     * @param           array           $collection      Collection of Product entities or array of entity details.
     * @param           array           $by              entity, post
     *
     * @return          array           $response
     */
    public function pageDeleteAction() {
        
    }

}

/**
 * Change Log:
 * **************************************
 * v1.0.0                     Said İmamoğlu
 * 27.11.2013
 * **************************************
 * A pageListAction()
 * A pageEditAction()
 * A pageNewAction()
 * A pageDeleteAction()
 * **************************************
 * v1.0.0                      Can Berkol
 * 01.08.2013
 * **************************************
 * A
 *
 */