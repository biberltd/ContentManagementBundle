<?php
/**
 * ContentManagementModel Class
 *
 * @vendor      BiberLtd
 * @package     BiberLtd\Bundle\ContentManagementBundle
 * @subpackage  Services
 * @name        ContentManagementBundle
 *
 * @author      Can Berkol
 *
 * @copyright   Biber Ltd. (www.biberltd.com)
 *
 * @version     1.2.7
 * @date        26.06.2015
 *
 */

namespace BiberLtd\Bundle\ContentManagementBundle\Services;

/** Extends CoreModel */
use BiberLtd\Bundle\CoreBundle\CoreModel;
/** Entities to be used */
use BiberLtd\Bundle\ContentManagementBundle\Entity as BundleEntity;
use BiberLtd\Bundle\CoreBundle\Responses\ModelResponse;
use BiberLtd\Bundle\MultiLanguageSupportBundle\Entity as MLSEntity;
use BiberLtd\Bundle\FileManagementBundle\Entity as FileBundleEntity;
/** Helper Models */
use BiberLtd\Bundle\SiteManagementBundle\Services as SMMService;
use BiberLtd\Bundle\MultiLanguageSupportBundle\Services as MLSService;
use BiberLtd\Bundle\FileManagementBundle\Services as FileService;
/** Core Service */
use BiberLtd\Bundle\CoreBundle\Services as CoreServices;
use BiberLtd\Bundle\CoreBundle\Exceptions as CoreExceptions;

class ContentManagementModel extends CoreModel{

    /**
     * @name            __construct()
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.2.1
     *
     * @param           object $kernel
     * @param           string $dbConnection Database connection key as set in app/config.yml
     * @param           string $orm ORM that is used.
     */
    public function __construct($kernel, $dbConnection = 'default', $orm = 'doctrine')
    {
        parent::__construct($kernel, $dbConnection, $orm);

        /**
         * Register entity names for easy reference.
         */
        $this->entity = array(
            'f' => array('name' => 'FileManagemetBundle:File', 'alias' => 'f'),
            'fop' => array('name' => 'ContentManagementBundle:FilesOfPage', 'alias' => 'fop'),
            'l' => array('name' => 'ContentManagementBundle:Layout', 'alias' => 'l'),
            'll' => array('name' => 'ContentManagementBundle:LayoutLocalization', 'alias' => 'll'),
            'm' => array('name' => 'ContentManagementBundle:Module', 'alias' => 'm'),
            'ml' => array('name' => 'ContentManagementBundle:ModuleLocalization', 'alias' => 'ml'),
            'mol' => array('name' => 'ContentManagementBundle:ModulesOfLayout', 'alias' => 'mol'),
            'n' => array('name' => 'ContentManagementBundle:Navigation', 'alias' => 'n'),
            'ni' => array('name' => 'ContentManagementBundle:NavigationItem', 'alias' => 'ni'),
            'nil' => array('name' => 'ContentManagementBundle:NavigationItemLocalization', 'alias' => 'nil'),
            'nl' => array('name' => 'ContentManagementBundle:NavigationLocalization', 'alias' => 'ml'),
            'p' => array('name' => 'ContentManagementBundle:Page', 'alias' => 'p'),
            'pl' => array('name' => 'ContentManagementBundle:PageLocalization', 'alias' => 'pl'),
			'pr' => array('name' => 'ContentManagementBundle:PageRevision', 'alias' => 'pr'),
			't' => array('name' => 'ContentManagementBundle:Theme', 'alias' => 't'),
            'tl' => array('name' => 'ContentManagementBundle:ThemeLocalization', 'alias' => 'tl'),
        );
    }

    /**
     * @name            __destruct()
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.0.0
     *
     */
    public function __destruct()
    {
        foreach ($this as $property => $value) {
            $this->$property = null;
        }
    }
    /**
     * @name           addFilesToProduct()
     *                 Associates files with a given product by creating new row in files_of_product_table.
     *
     * @since           1.1.7
     * @version         1.2.1
     * @author          Can Berkol
     *
     * @use             $this->createException()

     * @param           array       	$files
     * @param           mixed       	$page
     *
     * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
     */
	public function addFilesToPage($files, $page) {
		$timeStamp = time();
		$response = $this->getPage($page);
		if($response->error->exist){
			return $response;
		}
		$page = $response->result->set;
		if (!is_array($files)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. $groups parameter must be an array collection', 'E:S:001');
		}
		$toAdd = array();
		$fModel = $this->kernel->getContainer()->get('filemanagement.model');
		foreach ($files as $file) {
			$response = $fModel->getFile($file);
			if($response->error->exist){
				break;
			}
			$file = $response->result->set;
			if (!$this->isFileAssociatedWithPage($file, $page, true)) {
				$toAdd[] = $file;
			}
		}
		$now = new \DateTime('now', new \DateTimezone($this->kernel->getContainer()->getParameter('app_timezone')));
		$insertedItems = array();
		foreach ($toAdd as $file) {
			$entity = new BundleEntity\FilesOfPage();
			$entity->setFile($file)->setPage($page)->setDateAdded($now);
			$this->em->persist($entity);
			$insertedItems[] = $entity;
		}
		$countInserts = count($toAdd);
		if($countInserts > 0){
			$this->em->flush();
			return new ModelResponse($insertedItems, $countInserts, 0, null, false, 'S:D:003', 'Selected entries have been successfully inserted into database.', $timeStamp, time());
		}
		return new ModelResponse(null, 0, 0, null, true, 'E:D:003', 'One or more entities cannot be inserted into database.', $timeStamp, time());
	}
    /**
     * @name            deleteLayout ()
     *
     * @since           1.0.1
     * @version         1.2.1
     * @author          Can Berkol
     *
     * @use             $this->deleteLayouts()
     *
     * @param           mixed 			$layout
	 *
     * @return         BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
     */
    public function deleteLayout($layout)    {
        return $this->deleteLayouts(array($layout));
    }

    /**
     * @name            deleteLayouts()
     *
     * @since           1.0.1
     * @version         1.2.1
     * @author          Can Berkol
     *
     * @use             $this->createException
     *
     * @param           array 			$collection
     *
     * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
     */
	public function deleteLayouts($collection) {
		$timeStamp = time();
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countDeleted = 0;
		foreach($collection as $entry){
			if($entry instanceof BundleEntity\Layout){
				$this->em->remove($entry);
				$countDeleted++;
			}
			else{
				$response = $this->getLayout($entry);
				if(!$response->error->exists){
					$entry = $response->result->set;
					$this->em->remove($entry);
					$countDeleted++;
				}
			}
		}
		if($countDeleted < 0){
			return new ModelResponse(null, 0, 0, null, true, 'E:E:001', 'Unable to delete all or some of the selected entries.', $timeStamp, time());
		}
		$this->em->flush();

		return new ModelResponse(null, 0, 0, null, false, 'S:D:001', 'Selected entries have been successfully removed from database.', $timeStamp, time());
	}

    /**
     * @name            deleteModule()
     *
     * @since           1.0.0
     * @version         1.2.1
     * @author          Can Berkol
     *
     * @use             $this->deleteModules()
     *
     * @param           mixed 			$module
     *
     * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
     */
    public function deleteModule($module)    {
        return $this->deleteModules(array($module));
    }

    /**
     * @name            deleteModules()
     *
     * @since           1.0.1
     * @version         1.2.1
     * @author          Can Berkol
     *
     * @use             $this->doesModuleExist()
     * @use             $this->createException()
     *
     * @param           array 			$collection
     *
     * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
     */
	public function deleteModules($collection) {
		$timeStamp = time();
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countDeleted = 0;
		foreach($collection as $entry){
			if($entry instanceof BundleEntity\Module){
				$this->em->remove($entry);
				$countDeleted++;
			}
			else{
				$response = $this->getModule($entry);
				if(!$response->error->exists){
					$entry = $response->result->set;
					$this->em->remove($entry);
					$countDeleted++;
				}
			}
		}
		if($countDeleted < 0){
			return new ModelResponse(null, 0, 0, null, true, 'E:E:001', 'Unable to delete all or some of the selected entries.', $timeStamp, time());
		}
		$this->em->flush();

		return new ModelResponse(null, 0, 0, null, false, 'S:D:001', 'Selected entries have been successfully removed from database.', $timeStamp, time());
	}

    /**
     * @name            deleteNavigation()
     *
     * @since           1.0.1
     * @version         1.2.1
     * @author          Can Berkol
     *
     * @use             $this->deleteNavigations()
     *
     * @param           mixed 			$navigation
     *
     * @return          mixed           $response
     */
    public function deleteNavigation($navigation){
        return $this->deleteNavigations(array($navigation));
    }

	/**
	 * @name            deleteNavigations()
	 *
	 * @since           1.0.1
	 * @version         1.2.1
	 * @author          Can Berkol
	 *
	 * @use             $this->doesModuleExist()
	 * @use             $this->createException()
	 *
	 * @param           array 			$collection
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function deleteNavigations($collection) {
		$timeStamp = time();
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countDeleted = 0;
		foreach($collection as $entry){
			if($entry instanceof BundleEntity\Navigation){
				$this->em->remove($entry);
				$countDeleted++;
			}
			else{
				$response = $this->getNavigation($entry);
				if(!$response->error->exists){
					$entry = $response->result->set;
					$this->em->remove($entry);
					$countDeleted++;
				}
			}
		}
		if($countDeleted < 0){
			return new ModelResponse(null, 0, 0, null, true, 'E:E:001', 'Unable to delete all or some of the selected entries.', $timeStamp, time());
		}
		$this->em->flush();

		return new ModelResponse(null, 0, 0, null, false, 'S:D:001', 'Selected entries have been successfully removed from database.', $timeStamp, time());
	}

    /**
     * @name            deleteNavigationItem ()
     *
     * @since           1.0.1
     * @version         1.2.1
     * @author          Can Berkol
     *
     * @use             $this->deleteNavigationItems()
     *
     * @param           mixed 			$item
     *
     * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
     */
    public function deleteNavigationItem($item) {
        return $this->deleteNavigationItems(array($item));
    }

	/**
	 * @name            deleteNavigationItems()
	 *
	 * @since           1.0.1
	 * @version         1.2.1
	 * @author          Can Berkol
	 *
	 * @use             $this->doesModuleExist()
	 * @use             $this->createException()
	 *
	 * @param           array 			$collection
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function deleteNavigationItems($collection) {
		$timeStamp = time();
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countDeleted = 0;
		foreach($collection as $entry){
			if($entry instanceof BundleEntity\NavigationItem){
				$this->em->remove($entry);
				$countDeleted++;
			}
			else{
				$response = $this->getNavigationItem($entry);
				if(!$response->error->exists){
					$entry = $response->result->set;
					$this->em->remove($entry);
					$countDeleted++;
				}
			}
		}
		if($countDeleted < 0){
			return new ModelResponse(null, 0, 0, null, true, 'E:E:001', 'Unable to delete all or some of the selected entries.', $timeStamp, time());
		}
		$this->em->flush();

		return new ModelResponse(null, 0, 0, null, false, 'S:D:001', 'Selected entries have been successfully removed from database.', $timeStamp, time());
	}

    /**
     * @name            deletePage()
     *
     * @since           1.0.0
     * @version         1.2.1
     * @author          Can Berkol
     *
     * @use             $this->deletePages()
     *
     * @param           mixed 			$page
     * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
     */
    public function deletePage($page){
        return $this->deletePages(array($page));
    }

	/**
	 * @name            deletePageRevision()
	 *
	 * @since           1.1.9
	 * @version         1.2.1
	 * @author          Can Berkol
	 *
	 * @use             $this->deletePageRevisions()
	 *
	 * @param           mixed 			$revision
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function deletePageRevision($revision){
		return $this->deletePageRevisions(array($revision));
	}

	/**
	 * @name            deletePageRevisions()
	 *
	 * @since           1.1.9
	 * @version         1.2.1
	 * @author          Can Berkol
	 *
	 * @use             $this->createException()
	 *
	 * @param           array           $collection             Collection consists one of the following: PageRevision
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function deletePageRevisions($collection){
		$timeStamp = time();
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countDeleted = 0;
		foreach($collection as $entry){
			if($entry instanceof BundleEntity\PageRevision){
				$this->em->remove($entry);
				$countDeleted++;
			}
		}
		if($countDeleted < 0){
			return new ModelResponse(null, 0, 0, null, true, 'E:E:001', 'Unable to delete all or some of the selected entries.', $timeStamp, time());
		}
		$this->em->flush();

		return new ModelResponse(null, 0, 0, null, false, 'S:D:001', 'Selected entries have been successfully removed from database.', $timeStamp, time());
	}

    /**
     * @name            deletePages()
     *
     * @since           1.0.0
     * @version         1.2.1
     *
     * @use             $this->createException()
     *
     * @param           array 			$collection
     *
     * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
     */
    public function deletePages($collection){
		$timeStamp = time();
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countDeleted = 0;
		foreach($collection as $entry){
			if($entry instanceof BundleEntity\Page){
				$this->em->remove($entry);
				$countDeleted++;
			}
			else{
				$response = $this->getPage($entry);
				if(!$response->error->exists){
					$this->em->remove($response->result->set);
					$countDeleted++;
				}
			}
		}
		if($countDeleted < 0){
			return new ModelResponse(null, 0, 0, null, true, 'E:E:001', 'Unable to delete all or some of the selected entries.', $timeStamp, time());
		}
		$this->em->flush();

		return new ModelResponse(null, 0, 0, null, false, 'S:D:001', 'Selected entries have been successfully removed from database.', $timeStamp, time());
    }

    /**
     * @name            deleteTheme ()
     *
     * @since           1.0.1
     * @version         1.2.1
     * @author          Can Berkol
     *
     * @use             $this->deleteThemes()
     *
     * @param           mixed 			$theme
     * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
     */
    public function deleteTheme($theme) {
        return $this->deleteThemes(array($theme));
    }

	/**
	 * @name            deleteThemes()
	 *
	 * @since           1.0.0
	 * @version         1.2.1
	 *
	 * @use             $this->createException()
	 *
	 * @param           array 			$collection
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function deleteThemes($collection){
		$timeStamp = time();
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countDeleted = 0;
		foreach($collection as $entry){
			if($entry instanceof BundleEntity\Theme){
				$this->em->remove($entry);
				$countDeleted++;
			}
			else{
				$response = $this->getTheme($entry);
				if(!$response->error->exists){
					$this->em->remove($response->result->set);
					$countDeleted++;
				}
			}
		}
		if($countDeleted < 0){
			return new ModelResponse(null, 0, 0, null, true, 'E:E:001', 'Unable to delete all or some of the selected entries.', $timeStamp, time());
		}
		$this->em->flush();

		return new ModelResponse(null, 0, 0, null, false, 'S:D:001', 'Selected entries have been successfully removed from database.', $timeStamp, time());
	}

    /**
     * @name            doesLayoutExist()
     *
     * @since           1.0.1
     * @version         1.2.1
	 *
     * @author          Can Berkol
     *
     * @use             $this->getLayout()
     *
     * @param           mixed 			$layout
     * @param           bool 			$bypass 			If set to true does not return response but only the result.
     *
     * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
     */
	public function doesLayoutExist($layout, $bypass = false) {
		$timeStamp = time();
		$exist = false;

		$response = $this->getLayout($layout);

		if ($response->error->exists) {
			if($bypass){
				return $exist;
			}
			$response->result->set = false;
			return $response;
		}
		$exist = true;
		if ($bypass) {
			return $exist;
		}

		return new ModelResponse($exist, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
	}

	/**
	 * @name            doesModuleExist()
	 *
	 * @since           1.0.1
	 * @version         1.2.1
	 *
	 * @author          Can Berkol
	 *
	 * @use             $this->getModule()
	 *
	 * @param           mixed 			$module
	 * @param           bool 			$bypass 			If set to true does not return response but only the result.
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function doesModuleExist($module, $bypass = false) {
		$timeStamp = time();
		$exist = false;

		$response = $this->getModule($module);

		if ($response->error->exists) {
			if($bypass){
				return $exist;
			}
			$response->result->set = false;
			return $response;
		}
		$exist = true;
		if ($bypass) {
			return $exist;
		}

		return new ModelResponse($exist, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
	}

	/**
	 * @name            doesModuleLayoutEntryExist()
	 *
	 * @since           1.0.1
	 * @version         1.2.1
	 *
	 * @author          Can Berkol
	 *
	 * @use             $this->getModuleLayoutEntry()
	 *
	 * @param           mixed 			$entry
	 * @param           bool 			$bypass 			If set to true does not return response but only the result.
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function doesModuleLayoutEntryExist($entry, $bypass = false) {
		$timeStamp = time();
		$exist = false;

		$response = $this->getModuleLayoutEntry($entry);

		if ($response->error->exists) {
			if($bypass){
				return $exist;
			}
			$response->result->set = false;
			return $response;
		}
		$exist = true;
		if ($bypass) {
			return $exist;
		}

		return new ModelResponse($exist, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
	}

	/**
	 * @name            doesNavigationExist()
	 *
	 * @since           1.0.1
	 * @version         1.2.1
	 *
	 * @author          Can Berkol
	 *
	 * @use             $this->getNavigation()
	 *
	 * @param           mixed 			$navigation
	 * @param           bool 			$bypass 			If set to true does not return response but only the result.
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function doesNavigationExist($navigation, $bypass = false) {
		$timeStamp = time();
		$exist = false;

		$response = $this->getNavigation($navigation);

		if ($response->error->exists) {
			if($bypass){
				return $exist;
			}
			$response->result->set = false;
			return $response;
		}
		$exist = true;
		if ($bypass) {
			return $exist;
		}

		return new ModelResponse($exist, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
	}

	/**
	 * @name            doesNavigationItemExist()
	 *
	 * @since           1.0.1
	 * @version         1.2.1
	 *
	 * @author          Can Berkol
	 *
	 * @use             $this->getNavigationItem()
	 *
	 * @param           mixed 			$item
	 * @param           bool 			$bypass 			If set to true does not return response but only the result.
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function doesNavigationItemExist($item, $bypass = false) {
		$timeStamp = time();
		$exist = false;

		$response = $this->getNavigationItem($item);

		if ($response->error->exists) {
			if($bypass){
				return $exist;
			}
			$response->result->set = false;
			return $response;
		}
		$exist = true;
		if ($bypass) {
			return $exist;
		}

		return new ModelResponse($exist, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
	}

	/**
	 * @name            doesPageExist()
	 *
	 * @since           1.0.1
	 * @version         1.2.1
	 *
	 * @author          Can Berkol
	 *
	 * @use             $this->getPage()
	 *
	 * @param           mixed 			$page
	 * @param           bool 			$bypass 			If set to true does not return response but only the result.
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function doesPageExist($page, $bypass = false) {
		$timeStamp = time();
		$exist = false;

		$response = $this->getPage($page);

		if ($response->error->exists) {
			if($bypass){
				return $exist;
			}
			$response->result->set = false;
			return $response;
		}
		$exist = true;
		if ($bypass) {
			return $exist;
		}

		return new ModelResponse($exist, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
	}

	/**
	 * @name            doesThemeExist()
	 *
	 * @since           1.0.1
	 * @version         1.2.1
	 *
	 * @author          Can Berkol
	 *
	 * @use             $this->getTheme()
	 *
	 * @param           mixed 			$theme
	 * @param           bool 			$bypass 			If set to true does not return response but only the result.
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function doesThemeExist($theme, $bypass = false) {
		$timeStamp = time();
		$exist = false;

		$response = $this->getTheme($theme);

		if ($response->error->exists) {
			if($bypass){
				return $exist;
			}
			$response->result->set = false;
			return $response;
		}
		$exist = true;
		if ($bypass) {
			return $exist;
		}

		return new ModelResponse($exist, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
	}

	/**
	 * @name            getLastRevisionOfPage()
	 *
	 * @since           1.1.9
	 * @version         1.2.1
	 *
	 * @author          Can Berkol
	 *
	 * @page			mixed			$page
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function getLastRevisionOfPage($page){
		$timeStamp = time();
		$response = $this->getPage($page);
		if($response->error->exist){
			return $response;
		}
		$page = $response->result->set;

		$filter[] = array(
			'glue' => 'and',
			'condition' => array(
				array(
					'glue' => 'and',
					'condition' => array('column' =>$this->entity['pr']['alias']. '.page', 'comparison' => '=', 'value' => $page->getId()),
				)
			)
		);
		$response = $this->listPageRevisions($filter, array('date_added' => 'desc'), array('start' => 0, 'count' => 1));

		$response->stats->execution->start = $timeStamp;
		$response->stats->execution->end = time();

		return $response;
	}

	/**
	 * @name 			getLayout()
	 *
	 * @since			1.0.0
	 * @version         1.2.1
	 * @author          Can Berkol
	 *
	 * @param           mixed           $layout
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function getLayout($layout) {
		$timeStamp = time();
		if($layout instanceof BundleEntity\Layout){
			return new ModelResponse($layout, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
		}
		$result = null;
		switch($layout){
			case is_numeric($layout):
				$result = $this->em->getRepository($this->entity['l']['name'])->findOneBy(array('id' => $layout));
				break;
			case is_string($layout):
				$result = $this->em->getRepository($this->entity['l']['name'])->findOneBy(array('code' => $layout));
				if(is_null($result)){
					$response = $this->getLayoutByUrlKey($module);
					if(!$response->error->exist){
						$result = $response->result->set;
					}
					unset($response);
				}
				break;
		}
		if(is_null($result)){
			return new ModelResponse($result, 0, 0, null, true, 'E:D:002', 'Unable to find request entry in database.', $timeStamp, time());
		}

		return new ModelResponse($result, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
	}
	/**
	 * @name            getLayoutByUrlKey()
	 *
	 * @since           1.2.1
	 * @version         1.2.1
	 * @author          Can Berkol
	 *
	 * @use             $this->listLayouts()
	 * @use             $this->createException()
	 *
	 * @param           mixed 			$urlKey
	 * @param			mixed			$language
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function getLayoutByUrlKey($urlKey, $language = null){
		$timeStamp = time();
		if(!is_string($urlKey)){
			return $this->createException('InvalidParameterValueException', '$urlKey must be a string.', 'E:S:007');
		}
		$filter[] = array(
			'glue' => 'and',
			'condition' => array(
				array(
					'glue' => 'and',
					'condition' => array('column' => $this->entity['l']['alias'].'.url_key', 'comparison' => '=', 'value' => $urlKey),
				)
			)
		);
		if(!is_null($language)){
			$mModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
			$response = $mModel->getLanguage($language);
			if(!$response->error->exists){
				$filter[] = array(
					'glue' => 'and',
					'condition' => array(
						array(
							'glue' => 'and',
							'condition' => array('column' => $this->entity['l']['alias'].'.language', 'comparison' => '=', 'value' => $response->result->set->getId()),
						)
					)
				);
			}
		}
		$response = $this->listLayouts($filter, null, array('start' => 0, 'count' => 1));

		$response->stats->execution->start = $timeStamp;
		$response->stats->execution->end = time();

		return $response;
	}
    /**
     * @name            getMaxSortOrderOfFilesOfPage ()
	 *
     * @since           1.1.7
     * @version         1.2.1
     * @author          Can Berkol
     *
     *
     * @param           mixed   			$page
     * @param           bool    			$bypass     if set to true return bool instead of response
     *
     * @return          mixed           bool | $response
     */
    public function getMaxSortOrderOfFilesOfPage($page, $bypass = false){
        $timeStamp = time();
        $response = $this->getPage($page);
		if($response->error->exist){
			return $response;
		}
        $qStr = 'SELECT MAX('.$this->entity['fop']['alias'].'.sort_order) FROM '.$this->entity['fop']['name'].' '.$this->entity['fop']['alias']
            		.' WHERE '.$this->entity['fop']['alias'].'.page = '.$page->getId();

        $q = $this->em->createQuery($qStr);
        $result = $q->getSingleScalarResult();

        if ($bypass) {
            return $result;
        }
		return new ModelResponse($result, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
    }
	/**
	 * @name 			getModule()
	 *
	 * @since			1.0.0
	 * @version         1.2.1
	 * @author          Can Berkol
	 *
	 * @param           mixed           $module
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function getModule($module) {
		$timeStamp = time();
		if($module instanceof BundleEntity\Module){
			return new ModelResponse($module, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
		}
		$result = null;
		switch($module){
			case is_numeric($module):
				$result = $this->em->getRepository($this->entity['m']['name'])->findOneBy(array('id' => $module));
				break;
			case is_string(module):
				$result = $this->em->getRepository($this->entity['m']['name'])->findOneBy(array('code' => $module));
				if(is_null($result)){
					$response = $this->getModuleByUrlKey($module);
					if(!$response->error->exist){
						$result = $response->result->set;
					}
					unset($response);
				}
				break;
		}
		if(is_null($result)){
			return new ModelResponse($result, 0, 0, null, true, 'E:D:002', 'Unable to find request entry in database.', $timeStamp, time());
		}

		return new ModelResponse($result, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
	}
	/**
	 * @name            getModuleByUrlKey()
	 *
	 * @since           1.2.1
	 * @version         1.2.1
	 * @author          Can Berkol
	 *
	 * @use             $this->listModules()
	 * @use             $this->createException()
	 *
	 * @param           mixed 			$urlKey
	 * @param			mixed			$language
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function getModuleByUrlKey($urlKey, $language = null){
		$timeStamp = time();
		if(!is_string($urlKey)){
			return $this->createException('InvalidParameterValueException', '$urlKey must be a string.', 'E:S:007');
		}
		$filter[] = array(
			'glue' => 'and',
			'condition' => array(
				array(
					'glue' => 'and',
					'condition' => array('column' => $this->entity['ml']['alias'].'.url_key', 'comparison' => '=', 'value' => $urlKey),
				)
			)
		);
		if(!is_null($language)){
			$mModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
			$response = $mModel->getLanguage($language);
			if(!$response->error->exists){
				$filter[] = array(
					'glue' => 'and',
					'condition' => array(
						array(
							'glue' => 'and',
							'condition' => array('column' => $this->entity['ml']['alias'].'.language', 'comparison' => '=', 'value' => $response->result->set->getId()),
						)
					)
				);
			}
		}
		$response = $this->listModules($filter, null, array('start' => 0, 'count' => 1));

		$response->stats->execution->start = $timeStamp;
		$response->stats->execution->end = time();

		return $response;
	}
	/**
	 * @name 			getModuleLayoutEntry()
	 *
	 * @since			1.0.0
	 * @version         1.2.1
	 * @author          Can Berkol
	 *
	 * @param           mixed           $entry
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function getModuleLayoutEntry($entry) {
		$timeStamp = time();
		if($entry instanceof BundleEntity\ModulesOfLayout){
			return new ModelResponse($entry, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
		}
		$result = null;
		switch($entry){
			case is_numeric($entry):
				$result = $this->em->getRepository($this->entity['mol']['name'])->findOneBy(array('id' => $entry));
				break;
		}
		if(is_null($result)){
			return new ModelResponse($result, 0, 0, null, true, 'E:D:002', 'Unable to find request entry in database.', $timeStamp, time());
		}

		return new ModelResponse($result, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
	}

	/**
	 * @name 			getNavigation()
	 *
	 * @since			1.0.0
	 * @version         1.2.1
	 * @author          Can Berkol
	 *
	 * @param           mixed           $navigation
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function getNavigation($navigation) {
		$timeStamp = time();
		if($navigation instanceof BundleEntity\Navigation){
			return new ModelResponse($navigation, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
		}
		$result = null;
		switch($navigation){
			case is_numeric($navigation):
				$result = $this->em->getRepository($this->entity['n']['name'])->findOneBy(array('id' => $navigation));
				break;
			case is_string($navigation):
				$result = $this->em->getRepository($this->entity['n']['name'])->findOneBy(array('code' => $navigation));
				break;
		}
		if(is_null($result)){
			return new ModelResponse($result, 0, 0, null, true, 'E:D:002', 'Unable to find request entry in database.', $timeStamp, time());
		}

		return new ModelResponse($result, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
	}

	/**
	 * @name 			getNavigationItem()
	 *
	 * @since			1.0.0
	 * @version         1.2.1
	 * @author          Can Berkol
	 *
	 * @param           mixed           $item
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function getNavigationItem($item) {
		$timeStamp = time();
		if($item instanceof BundleEntity\NavigationItem){
			return new ModelResponse($item, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
		}
		$result = null;
		switch($item){
			case is_numeric($item):
				$result = $this->em->getRepository($this->entity['ni']['name'])->findOneBy(array('id' => $item));
				break;
			case is_string($item):
				$response = $this->getNavigationItemByUrlKey($item);
				if(!$response->error->exist){
					$result = $response->result->set;
				}
				unset($response);
				break;
		}
		if(is_null($result)){
			return new ModelResponse($result, 0, 0, null, true, 'E:D:002', 'Unable to find request entry in database.', $timeStamp, time());
		}

		return new ModelResponse($result, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
	}
	/**
	 * @name            getNavigationItemByUrlKey()
	 *
	 * @since           1.2.1
	 * @version         1.2.1
	 * @author          Can Berkol
	 *
	 * @use             $this->listNavigationItems()
	 * @use             $this->createException()
	 *
	 * @param           mixed 			$urlKey
	 * @param			mixed			$language
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function getNavigationItemByUrlKey($urlKey, $language = null){
		$timeStamp = time();
		if(!is_string($urlKey)){
			return $this->createException('InvalidParameterValueException', '$urlKey must be a string.', 'E:S:007');
		}
		$filter[] = array(
			'glue' => 'and',
			'condition' => array(
				array(
					'glue' => 'and',
					'condition' => array('column' => $this->entity['nil']['alias'].'.url_key', 'comparison' => '=', 'value' => $urlKey),
				)
			)
		);
		if(!is_null($language)){
			$mModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
			$response = $mModel->getLanguage($language);
			if(!$response->error->exists){
				$filter[] = array(
					'glue' => 'and',
					'condition' => array(
						array(
							'glue' => 'and',
							'condition' => array('column' => $this->entity['nil']['alias'].'.language', 'comparison' => '=', 'value' => $response->result->set->getId()),
						)
					)
				);
			}
		}
		$response = $this->listNavigationItems($filter, null, array('start' => 0, 'count' => 1));

		$response->stats->execution->start = $timeStamp;
		$response->stats->execution->end = time();

		return $response;
	}
	/**
	 * @name 			getPage()
	 *
	 * @since			1.0.0
	 * @version         1.2.1
	 * @author          Can Berkol
	 *
	 * @param           mixed           $page
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function getPage($page) {
		$timeStamp = time();
		if($page instanceof BundleEntity\Page){
			return new ModelResponse($page, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
		}
		$result = null;
		switch($page){
			case is_numeric($page):
				$result = $this->em->getRepository($this->entity['p']['name'])->findOneBy(array('id' => $page));
				break;
			case is_string($page):
				$result = $this->em->getRepository($this->entity['p']['name'])->findOneBy(array('code' => $page));
				if(is_null($result)){
					$response = $this->getPageByUrlKey($page);
					if(!$response->error->exist){
						$result = $response->result->set;
					}
					unset($response);
				}
				break;
		}
		if(is_null($result)){
			return new ModelResponse($result, 0, 0, null, true, 'E:D:002', 'Unable to find request entry in database.', $timeStamp, time());
		}

		return new ModelResponse($result, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
	}
    /**
     * @name            getPageByUrlKey ()
     *
     * @since           1.2.1
     * @version         1.2.1
     * @author          Can Berkol
     *
     * @use             $this->listPages()
     * @use             $this->createException()
     *
     * @param           mixed 			$urlKey
	 * @param			mixed			$language
     *
     * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
     */
    public function getPageByUrlKey($urlKey, $language = null){
        $timeStamp = time();
		if(!is_string($urlKey)){
			return $this->createException('InvalidParameterValueException', '$urlKey must be a string.', 'E:S:007');
		}
        $filter[] = array(
            'glue' => 'and',
            'condition' => array(
                array(
                    'glue' => 'and',
                    'condition' => array('column' => $this->entity['pl']['alias'].'.url_key', 'comparison' => '=', 'value' => $urlKey),
                )
            )
        );
		if(!is_null($language)){
			$mModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
			$response = $mModel->getLanguage($language);
			if(!$response->error->exists){
				$filter[] = array(
					'glue' => 'and',
					'condition' => array(
						array(
							'glue' => 'and',
							'condition' => array('column' => $this->entity['pl']['alias'].'.language', 'comparison' => '=', 'value' => $response->result->set->getId()),
						)
					)
				);
			}
		}
        $response = $this->listPages($filter, null, array('start' => 0, 'count' => 1));

		$response->stats->execution->start = $timeStamp;
		$response->stats->execution->end = time();

		return $response;
    }

	/**
	 * @name            getPageRevision()
	 *
	 * @since           1.1.9
	 * @version         1.2.4
	 *
	 * @author          Can Berkol
	 *
	 * @use             $this->createException()
	 * @use             $this->listPageRevisions()
	 *
	 * @param           mixed           $page
	 * @param			mixed			$language
	 * @param			integer			$revisionNumber
	 *
	 * @return          mixed           $response
	 */
	public function getPageRevision($page, $language, $revisionNumber){
		$timeStamp = time();

		$response = $this->getPage($page);
		if($response->error->exist){
			return $response;
		}
		$page = $response->result->set;

		$mlsModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
		$response = $mlsModel->getLanguage($language);
		if($response->error->exist){
			return $response;
		}
		$language = $response->result->set;

		$qStr = 'SELECT '.$this->entity['pr']['alias']
				.' FROM '.$this->entity['pr']['name'].' '.$this->entity['pr']['alias']
				.' WHERE '.$this->entity['pr']['alias'].'.page = '.$page->getId()
				.' AND '.$this->entity['pr']['alias'].'.language = '.$language->getId()
				.' AND '.$this->entity['pr']['alias'].'.revision_number = '.$revisionNumber;

		$q = $this->em->createQuery($qStr);

		$result = $q->getResult();

		if(is_null($result)){
			return new ModelResponse($result, 0, 0, null, true, 'E:D:002', 'Unable to find request entry in database.', $timeStamp, time());
		}

		return new ModelResponse($result, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
	}
	/**
	 * @name 			getTheme()
	 *
	 * @since			1.2.1
	 * @version         1.2.1
	 * @author          Can Berkol
	 *
	 * @param           mixed           $theme
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function getTheme($theme) {
		$timeStamp = time();
		if($theme instanceof BundleEntity\Theme){
			return new ModelResponse($theme, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
		}
		$result = null;
		switch($theme){
			case is_numeric($theme):
				$result = $this->em->getRepository($this->entity['t']['name'])->findOneBy(array('id' => $theme));
				break;
		}
		if(is_null($result)){
			return new ModelResponse($result, 0, 0, null, true, 'E:D:002', 'Unable to find request entry in database.', $timeStamp, time());
		}

		return new ModelResponse($result, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
	}

    /**
     * @name            insertNavigation()
     *
     * @since           1.0.1
     * @version         1.2.1
     * @author          Can Berkol
     *
     * @use             $this->insertNavigation()
     *
     * @param           array 			$navigation
	 *
     * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
     */
    public function insertNavigation($navigation)    {
        return $this->insertNavigations(array($navigation));
    }

    /**
     * @name            insertNavigationLocalizations ()
     *
     * @since           1.1.6
     * @version         1.2.6
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           array 			$collection
     *
     * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
     */
	public function insertNavigationLocalizations($collection) {
		$timeStamp = time();
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countInserts = 0;
		$insertedItems = array();
		foreach($collection as $data){
			if($data instanceof BundleEntity\NavigationLocalization){
				$entity = $data;
				$this->em->persist($entity);
				$insertedItems[] = $entity;
				$countInserts++;
			}
			else{
				$nav = $data['entity'];
				foreach($data['localizations'] as $locale => $translation){
					$entity = new BundleEntity\NavigationLocalization();
					$lModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
					$response = $lModel->getLanguage($locale);
					if($response->error->exist){
						return $response;
					}
					$entity->setLanguage($response->result->set);
					unset($response);
					$entity->setNavigation($nav);
					foreach($translation as $column => $value){
						$set = 'set'.$this->translateColumnName($column);
						switch($column){
							default:
								if(is_object($value) || is_array($value)){
									$value = json_encode($value);
								}
								$entity->$set($value);
								break;
						}
					}
					$this->em->persist($entity);
					$insertedItems[] = $entity;
					$countInserts++;
				}
			}
		}
		if($countInserts > 0){
			$this->em->flush();
			return new ModelResponse($insertedItems, $countInserts, 0, null, false, 'S:D:003', 'Selected entries have been successfully inserted into database.', $timeStamp, time());
		}
		return new ModelResponse(null, 0, 0, null, true, 'E:D:003', 'One or more entities cannot be inserted into database.', $timeStamp, time());
	}

    /**
     * @name            insertNavigations()
     *
     * @since           1.0.1
     * @version         1.2.1
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           array 			$collection
     *
     * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
     */
	public function insertNavigations($collection)	{
		$timeStamp = time();
		/** Parameter must be an array */
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countInserts = 0;
		$countLocalizations = 0;
		$insertedItems = array();
		$localizations = array();
		foreach ($collection as $data) {
			if ($data instanceof BundleEntity\Navigation) {
				$entity = $data;
				$this->em->persist($entity);
				$insertedItems[] = $entity;
				$countInserts++;
			}
			else if (is_object($data)) {
				$entity = new BundleEntity\Navigation();
				$now = new \DateTime('now', new \DateTimeZone($this->kernel->getContainer()->getParameter('app_timezone')));
				if(!property_exists($data, 'date_added')){
					$data->date_added = $now;
				}
				if(!property_exists($data, 'date_updated')){
					$data->date_updated = $now;
				}
				if(!property_exists($data, 'site')){
					$data->site = 1;
				}
				foreach ($data as $column => $value) {
					$localeSet = false;
					$set = 'set' . $this->translateColumnName($column);
					switch ($column) {
						case 'local':
							$localizations[$countInserts]['localizations'] = $value;
							$localeSet = true;
							$countLocalizations++;
							break;
						case 'site':
							$sModel = $this->kernel->getContainer()->get('sitemanagement.model');
							$response = $sModel->getSite($value);
							if (!$response->error->exist) {
								$entity->$set($response->result->set);
							} else {
								return $this->createException('EntityDoesNotExist', 'The site with the id / key / domain "'.$value.'" does not exist in database.', 'E:D:002');
							}
							unset($response, $sModel);
							break;
						default:
							$entity->$set($value);
							break;
					}
					if ($localeSet) {
						$localizations[$countInserts]['entity'] = $entity;
					}
				}
				$this->em->persist($entity);
				$insertedItems[] = $entity;

				$countInserts++;
			}
		}
		if ($countInserts > 0) {
			$this->em->flush();
		}
		/** Now handle localizations */
		if ($countInserts > 0 && $countLocalizations > 0) {
			$response = $this->insertNavigationLocalizations($localizations);
		}
		if($countInserts > 0){
			$this->em->flush();
			return new ModelResponse($insertedItems, $countInserts, 0, null, false, 'S:D:003', 'Selected entries have been successfully inserted into database.', $timeStamp, time());
		}
		return new ModelResponse(null, 0, 0, null, true, 'E:D:003', 'One or more entities cannot be inserted into database.', $timeStamp, time());
	}

    /**
     * @name            insertNavigationItem ()
     *
     * @since           1.0.1
     * @version         1.2.1
	 *
     * @author          Can Berkol
     *
     * @use             $this->insertNavigationItems()
     *
     * @param           mixed			$item
	 *
     * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
     */
    public function insertNavigationItem($item){
        return $this->insertNavigationItems(array($item));
    }

	/**
	 * @name            insertNavigationItemLocalizations ()
	 *
	 * @since           1.1.6
	 * @version         1.2.6
	 * @author          Can Berkol
	 *
	 * @use             $this->createException()
	 *
	 * @param           array 			$collection
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function insertNavigationItemLocalizations($collection) {
		$timeStamp = time();
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countInserts = 0;
		$insertedItems = array();
		foreach($collection as $data){
			if($data instanceof BundleEntity\NavigationItemLocalization){
				$entity = $data;
				$this->em->persist($entity);
				$insertedItems[] = $entity;
				$countInserts++;
			}
			else{
				$navItem = $data['entity'];
				foreach($data['localizations'] as $locale => $translation){
					$entity = new BundleEntity\NavigationItemLocalization();
					$lModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
					$response = $lModel->getLanguage($locale);
					if($response->error->exist){
						return $response;
					}
					$entity->setLanguage($response->result->set);
					unset($response);
					$entity->setNavigationItem($navItem);
					foreach($translation as $column => $value){
						$set = 'set'.$this->translateColumnName($column);
						switch($column){
							default:
								if(is_object($value) || is_array($value)){
									$value = json_encode($value);
								}
								$entity->$set($value);
								break;
						}
					}
					$this->em->persist($entity);
					$insertedItems[] = $entity;
					$countInserts++;
				}
			}
		}
		if($countInserts > 0){
			$this->em->flush();
			return new ModelResponse($insertedItems, $countInserts, 0, null, false, 'S:D:003', 'Selected entries have been successfully inserted into database.', $timeStamp, time());
		}
		return new ModelResponse(null, 0, 0, null, true, 'E:D:003', 'One or more entities cannot be inserted into database.', $timeStamp, time());
	}

	/**
	 * @name            insertNavigationItems ()
	 *
	 * @since           1.0.1
	 * @version         1.2.1
	 * @author          Can Berkol
	 *
	 * @use             $this->createException()
	 *
	 * @param           array 			$collection
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function insertNavigationItems($collection)	{
		$timeStamp = time();
		/** Parameter must be an array */
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countInserts = 0;
		$countLocalizations = 0;
		$insertedItems = array();
		$localizations = array();
		foreach ($collection as $data) {
			if ($data instanceof BundleEntity\NavigationItem) {
				$entity = $data;
				$this->em->persist($entity);
				$insertedItems[] = $entity;
				$countInserts++;
			}
			else if (is_object($data)) {
				$entity = new BundleEntity\NavigationItem();
				$now = new \DateTime('now', new \DateTimeZone($this->kernel->getContainer()->getParameter('app_timezone')));
				if(!property_exists($data, 'date_added')){
					$data->date_added = $now;
				}
				if(!property_exists($data, 'date_updated')){
					$data->date_updated = $now;
				}
				if(!property_exists($data, 'site')){
					$data->site = 1;
				}
				foreach ($data as $column => $value) {
					$localeSet = false;
					$set = 'set' . $this->translateColumnName($column);
					switch ($column) {
						case 'local':
							$localizations[$countInserts]['localizations'] = $value;
							$localeSet = true;
							$countLocalizations++;
							break;
						case 'page':
							$response = $this->getPage($value);
							if (!$response->error->exist) {
								$entity->$set($response->result->set);
							}
							unset($response);
							break;
						case 'navigation':
							$response = $this->getNavigation($value);
							if (!$response->error->exist) {
								$entity->$set($response->result->set);
							}
							unset($response);
							break;
						case 'parent':
							$response = $this->getNavigationItem($value);
							if (!$response->error->exist) {
								$entity->$set($response->result->set);
							}
							unset($response);
							break;
						default:
							$entity->$set($value);
							break;
					}
					if ($localeSet) {
						$localizations[$countInserts]['entity'] = $entity;
					}
				}
				$this->em->persist($entity);
				$insertedItems[] = $entity;

				$countInserts++;
			}
		}
		if ($countInserts > 0) {
			$this->em->flush();
		}
		/** Now handle localizations */
		if ($countInserts > 0 && $countLocalizations > 0) {
			$response = $this->insertNavigationLocalizations($localizations);
		}
		if($countInserts > 0){
			$this->em->flush();
			return new ModelResponse($insertedItems, $countInserts, 0, null, false, 'S:D:003', 'Selected entries have been successfully inserted into database.', $timeStamp, time());
		}
		return new ModelResponse(null, 0, 0, null, true, 'E:D:003', 'One or more entities cannot be inserted into database.', $timeStamp, time());
	}

	/**
	 * @name            insertPage ()
	 *
	 * @since           1.0.1
	 * @version         1.2.1
	 *
	 * @author          Can Berkol
	 *
	 * @use             $this->insertPages()
	 *
	 * @param           mixed			$page
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function insertPage($page){
		return $this->insertPages(array($page));
	}

	/**
	 * @name            insertPageLocalizations()
	 *
	 * @since           1.1.6
	 * @version         1.2.6
	 * @author          Can Berkol
	 *
	 * @use             $this->createException()
	 *
	 * @param           array 			$collection
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function insertPageLocalizations($collection) {
		$timeStamp = time();
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countInserts = 0;
		$insertedItems = array();
		foreach($collection as $data){
			if($data instanceof BundleEntity\PageLocalization){
				$entity = $data;
				$this->em->persist($entity);
				$insertedItems[] = $entity;
				$countInserts++;
			}
			else{
				$page = $data['entity'];
				foreach($data['localizations'] as $locale => $translation){
					$entity = new BundleEntity\PageLocalization();
					$lModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
					$response = $lModel->getLanguage($locale);
					if($response->error->exist){
						return $response;
					}
					$entity->setLanguage($response->result->set);
					unset($response);
					$entity->setPage($page);
					foreach($translation as $column => $value){
						$set = 'set'.$this->translateColumnName($column);
						switch($column){
							default:
								if(is_object($value) || is_array($value)){
									$value = json_encode($value);
								}
								$entity->$set($value);
								break;
						}
					}
					$this->em->persist($entity);
					$insertedItems[] = $entity;
					$countInserts++;
				}
			}
		}
		if($countInserts > 0){
			$this->em->flush();
			return new ModelResponse($insertedItems, $countInserts, 0, null, false, 'S:D:003', 'Selected entries have been successfully inserted into database.', $timeStamp, time());
		}
		return new ModelResponse(null, 0, 0, null, true, 'E:D:003', 'One or more entities cannot be inserted into database.', $timeStamp, time());
	}

	/**
	 * @name            insertPageRevision()
	 *
	 * @since           1.1.9
	 * @version         1.1.9
	 * @author          Can Berkol
	 *
	 * @use             $this->insertPageRevisions()
	 *
	 * @param           mixed			$revision
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function insertPageRevision($revision) {
		return $this->insertPageRevisions(array($revision));
	}

	/**
	 * @name            insertPageRevisions()
	 *
	 * @since           1.1.9
	 * @version         1.1.9
	 * @author          Can Berkol
	 *
	 * @use             $this->createException()
	 *
	 * @param           array 			$collection
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function insertPageRevisions($collection) {
		$timeStamp = time();
		/** Parameter must be an array */
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countInserts = 0;
		$insertedItems = array();
		foreach ($collection as $data) {
			if ($data instanceof BundleEntity\PageRevision) {
				$entity = $data;
				$this->em->persist($entity);
				$insertedItems[] = $entity;
				$countInserts++;
			}
			else if (is_object($data)) {
				$entity = new BundleEntity\PageRevision();
				foreach ($data as $column => $value) {
					$set = 'set' . $this->translateColumnName($column);
					switch ($column) {
						case 'language':
							$lModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
							$response = $lModel->getLanguage($value);
							if (!$response->error->exists) {
								$entity->$set($response->result->set);
							}
							unset($response, $lModel);
							break;
						case 'page':
							$response = $this->getPage($value);
							if (!$response->error->exist) {
								$entity->$set($response->result->Set);
							}
							unset($response);
							break;
						default:
							$entity->$set($value);
							break;
					}
				}
				$this->em->persist($entity);
				$insertedItems[] = $entity;

				$countInserts++;
			} else {
				new CoreExceptions\InvalidDataException($this->kernel);
			}
		}
		if($countInserts > 0){
			$this->em->flush();
			return new ModelResponse($insertedItems, $countInserts, 0, null, false, 'S:D:003', 'Selected entries have been successfully inserted into database.', $timeStamp, time());
		}
		return new ModelResponse(null, 0, 0, null, true, 'E:D:003', 'One or more entities cannot be inserted into database.', $timeStamp, time());
	}

	/**
	 * @name            insertPages()
	 *
	 * @since           1.0.1
	 * @version         1.2.1
	 * @author          Can Berkol
	 *
	 * @use             $this->createException()
	 *
	 * @param           array 			$collection
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function insertPages($collection)	{
		$timeStamp = time();
		/** Parameter must be an array */
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countInserts = 0;
		$countLocalizations = 0;
		$insertedItems = array();
		$localizations = array();
		foreach ($collection as $data) {
			if ($data instanceof BundleEntity\Page) {
				$entity = $data;
				$this->em->persist($entity);
				$insertedItems[] = $entity;
				$countInserts++;
			}
			else if (is_object($data)) {
				$entity = new BundleEntity\Page();
				$now = new \DateTime('now', new \DateTimeZone($this->kernel->getContainer()->getParameter('app_timezone')));
				if(!property_exists($data, 'site')){
					$data->site = 1;
				}
				foreach ($data as $column => $value) {
					$localeSet = false;
					$set = 'set' . $this->translateColumnName($column);
					switch ($column) {
						case 'local':
							$localizations[$countInserts]['localizations'] = $value;
							$localeSet = true;
							$countLocalizations++;
							break;
						case 'layout':
							$response = $this->getLayout($value);
							if (!$response->error->exist) {
								$entity->$set($response->result->set);
							}
							unset($response);
							break;
						case 'site':
							$sModel = $this->kernel->getContainer()->get('sitemanagement.model');
							$response = $sModel->getSite($value);
							if (!$response->error->exist) {
								$entity->$set($response->result->set);
							}
							else {
								return $this->createException('EntityDoesNotExist', 'The site with the id / key / domain "'.$value.'" does not exist in database.', 'E:D:002');
							}
							unset($response, $sModel);
							break;
						default:
							$entity->$set($value);
							break;
					}
					if ($localeSet) {
						$localizations[$countInserts]['entity'] = $entity;
					}
				}
				$this->em->persist($entity);
				$insertedItems[] = $entity;

				$countInserts++;
			}
		}
		if ($countInserts > 0) {
			$this->em->flush();
		}
		/** Now handle localizations */
		if ($countInserts > 0 && $countLocalizations > 0) {
			$response = $this->insertPageLocalizations($localizations);
		}
		if($countInserts > 0){
			$this->em->flush();
			return new ModelResponse($insertedItems, $countInserts, 0, null, false, 'S:D:003', 'Selected entries have been successfully inserted into database.', $timeStamp, time());
		}
		return new ModelResponse(null, 0, 0, null, true, 'E:D:003', 'One or more entities cannot be inserted into database.', $timeStamp, time());
	}

    /**
     * @name            isFileAssociatedWithPage()
     *
     * @since           1.1.7
     * @version         1.2.1
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           mixed       $file
     * @param           mixed       $page
     * @param           bool        $bypass     true or false
     *
     * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
     */
    public function isFileAssociatedWithPage($file, $page, $bypass = false){
        $timeStamp = time();
        $fModel = new FileService\FileManagementModel($this->kernel, $this->dbConnection, $this->orm);

		$response = $fModel->getFile($file);
		if($response->error->exist){
			return $response;
		}
		$file = $response->result->set;

        $response = $this->getPage($page);

		if($response->error->exist){
			return $response;
		}
		$page = $response->result->set;

        $found = false;

        $qStr = 'SELECT COUNT(' . $this->entity['fop']['alias'] . ')'
            . ' FROM ' . $this->entity['fop']['name'] . ' ' . $this->entity['fop']['alias']
            . ' WHERE ' . $this->entity['fop']['alias'] . '.file = ' . $file->getId()
            . ' AND ' . $this->entity['fop']['alias'] . '.page = ' . $page->getId();
        $query = $this->em->createQuery($qStr);

        $result = $query->getSingleScalarResult();

        /** flush all into database */
        if ($result > 0) {
            $found = true;
        }
        if ($bypass) {
            return $found;
        }

		return new ModelResponse($found, 1, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
    }

    /**
     * @name            listFilesOfPage ()
     *
     * @since           1.0.1
     * @version         1.2.1
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           array 				$filter
     * @param           array				$sortOrder
	 * @param           array 				$limit
     *
     * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
     */
    public function listFilesOfPage($page, $filter = null, $sortOrder = null, $limit = null){
        $timeStamp = time();
		if(!is_array($sortOrder) && !is_null($sortOrder)){
			return $this->createException('InvalidSortOrderException', '$sortOrder must be an array with key => value pairs where value can only be "asc" or "desc".', 'E:S:002');
		}

		$response = $this->getPage($page);
		if($response->error->exist){
			return $response;
		}
		$page = $response->result->set;
        $oStr = $wStr = $gStr = '';

        $qStr = 'SELECT '.$this->entity['fop']['alias']
            		.' FROM '.$this->entity['fop']['name'].' '.$this->entity['fop']['alias'];
        /**
         * Prepare ORDER BY part of query.
         */
        if ($sortOrder != null) {
            foreach ($sortOrder as $column => $direction) {
                switch ($column) {
                    default:
                        $oStr .= ' '.$this->entity['fop']['alias'].'.'.$column.' '.$direction.', ';
                        break;
                }
            }
			$oStr = rtrim($oStr, ', ');
			$oStr = ' ORDER BY ' . $oStr . ' ';
        }
		$filter[] = array(
			'glue' => 'and',
			'condition' => array(
				array(
					'glue' => 'and',
					'condition' => array('column' => $this->entity['fop']['alias'].'.id', 'comparison' => '=', 'value' => $page->getId()),
				)
			)
		);
		if(!is_null($filter)){
			$fStr = $this->prepareWhere($filter);
			$wStr .= ' WHERE '.$fStr;
		}

        $qStr .= $wStr.$gStr.$oStr;
        $q = $this->em->createQuery($qStr);
		$q = $this->addLimit($q, $limit);

        $result = $q->getResult();

		$totalRows = count($result);
		if ($totalRows < 1) {
			return new ModelResponse(null, 0, 0, null, true, 'E:D:002', 'No entries found in database that matches to your criterion.', $timeStamp, time());
		}
		return new ModelResponse($result, $totalRows, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
	}

    /**
     * @name            listItemsOfNavigation()
     *
     * @since           1.0.1
     * @version         1.2.1
	 *
     * @author          Can Berkol
     *
     * @use             $this->listNavigationItemsOfNavigation()
     *
     * @param           mixed 		$navigation
     * @param           mixed 		$level
     * @param           array 		$sortOrder
     * @param           array 		$limit
     *
     * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
     */
    public function listItemsOfNavigation($navigation, $level = null, $sortOrder = null, $limit = null){
        return $this->listNavigationItemsOfNavigation($navigation, $level, $sortOrder, $limit);
    }

    /**
     * @name            listLayouts ()
     *
     * @since           1.0.1
     * @version         1.2.1
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           array 		$filter
     * @param           array 		$sortOrder
     * @param           array 		$limit
     *
     * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
     */
	public function listLayouts($filter = null, $sortOrder = null, $limit = null){
		$timeStamp = time();
		if(!is_array($sortOrder) && !is_null($sortOrder)){
			return $this->createException('InvalidSortOrderException', '$sortOrder must be an array with key => value pairs where value can only be "asc" or "desc".', 'E:S:002');
		}
		$oStr = $wStr = $gStr = $fStr = '';

		$qStr = 'SELECT '.$this->entity['l']['alias'].', '.$this->entity['l']['alias']
			.' FROM '.$this->entity['ll']['name'].' '.$this->entity['ll']['alias']
			.' JOIN '.$this->entity['ll']['alias'].'.layout '.$this->entity['l']['alias'];

		if(!is_null($sortOrder)){
			foreach($sortOrder as $column => $direction){
				switch($column){
					case 'id':
					case 'code':
					case 'bundle_name':
					case 'site':
					case 'theme':
						$column = $this->entity['l']['alias'].'.'.$column;
						break;
					case 'name':
					case 'url_key':
						$column = $this->entity['ll']['alias'].'.'.$column;
						break;
				}
				$oStr .= ' '.$column.' '.strtoupper($direction).', ';
			}
			$oStr = rtrim($oStr, ', ');
			$oStr = ' ORDER BY '.$oStr.' ';
		}

		if(!is_null($filter)){
			$fStr = $this->prepareWhere($filter);
			$wStr .= ' WHERE '.$fStr;
		}

		$qStr .= $wStr.$gStr.$oStr;
		$q = $this->em->createQuery($qStr);
		$q = $this->addLimit($q, $limit);

		$result = $q->getResult();

		$entities = array();
		foreach($result as $entry){
			$id = $entry->getLayout()->getId();
			if(!isset($unique[$id])){
				$entities[] = $entry->getLayout();
			}
		}
		$totalRows = count($entities);
		if ($totalRows < 1) {
			return new ModelResponse(null, 0, 0, null, true, 'E:D:002', 'No entries found in database that matches to your criterion.', $timeStamp, time());
		}
		return new ModelResponse($entities, $totalRows, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
	}

    /**
     * @name            listLayoutsOfSite ()
     *
     * @since           1.0.1
     * @version         1.2.1
     * @author          Can Berkol
     *
     * @use             $this->listLayouts()
     *
     * @param           mixed 		$site
     * @param           array 		$filter
     * @param           array 		$sortOrder
     * @param           array 		$limit
     *
     * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
     */
    public function listLayoutsOfSite($site, $filter = null, $sortOrder = null, $limit = null)    {
        $timeStamp = time();
		$sModel = $this->kernel->getContainer()->get('sitemanagement.model');
        $response = $sModel->getSite($site);
		if($response->error->exist){
			return $response;
		}
		$site = $response->result->set;
		$filter[] = array(
			'glue' => 'and',
			'condition' => array(
				array(
					'glue' => 'and',
					'condition' => array('column' => $this->entity['l']['alias'].'.site', 'comparison' => '=', 'value' => $site->getId()),
				)
			)
		);
        $response = $this->listLayouts($filter, $sortOrder, $limit);
 		$response->stats->execution->start = $timeStamp;
 		$response->stats->execution->end = time();

		return $response;
    }

    /**
     * @name            listLayoutsOfTheme()
     *
     * @since           1.0.1
     * @version         1.2.1
     * @author          Can Berkol
     *
     * @use             $this->listLayouts()
     *
     * @param           mixed 		$theme
	 * @param			array		$filter
     * @param           array 		$sortOrder
     * @param           array 		$limit
     *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listLayoutsOfTheme($theme, $filter = null, $sortOrder = null, $limit = null)    {
		$timeStamp = time();
		$response = $this->getTheme($theme);
		if($response->error->exist){
			return $response;
		}
		$theme = $response->result->set;
		$filter[] = array(
			'glue' => 'and',
			'condition' => array(
				array(
					'glue' => 'and',
					'condition' => array('column' => $this->entity['l']['alias'].'.theme', 'comparison' => '=', 'value' => $theme->getId()),
				)
			)
		);
		$response = $this->listLayouts($filter, $sortOrder, $limit);
		$response->stats->execution->start = $timeStamp;
		$response->stats->execution->end = time();

		return $response;
	}

    /**
     * @name            listModules()
     *
     * @since           1.0.1
     * @version         1.2.1
     * @author          Can Berkol
     *
     * @use             $this->createException
     *
     * @param           array 		$filter
     * @param           array 		$sortOrder
     * @param           array 		$limit
     *
     * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
     */
	public function listModules($filter = null, $sortOrder = null, $limit = null){
		$timeStamp = time();
		if(!is_array($sortOrder) && !is_null($sortOrder)){
			return $this->createException('InvalidSortOrderException', '$sortOrder must be an array with key => value pairs where value can only be "asc" or "desc".', 'E:S:002');
		}
		$oStr = $wStr = $gStr = $fStr = '';

		$qStr = 'SELECT '.$this->entity['m']['alias'].', '.$this->entity['m']['alias']
			.' FROM '.$this->entity['ml']['name'].' '.$this->entity['ml']['alias']
			.' JOIN '.$this->entity['ml']['alias'].'.module '.$this->entity['m']['alias'];

		if(!is_null($sortOrder)){
			foreach($sortOrder as $column => $direction){
				switch($column){
					case 'id':
					case 'code':
					case 'bundle_name':
					case 'site':
					case 'theme':
						$column = $this->entity['m']['alias'].'.'.$column;
						break;
					case 'name':
					case 'url_key':
						$column = $this->entity['ml']['alias'].'.'.$column;
						break;
				}
				$oStr .= ' '.$column.' '.strtoupper($direction).', ';
			}
			$oStr = rtrim($oStr, ', ');
			$oStr = ' ORDER BY '.$oStr.' ';
		}

		if(!is_null($filter)){
			$fStr = $this->prepareWhere($filter);
			$wStr .= ' WHERE '.$fStr;
		}

		$qStr .= $wStr.$gStr.$oStr;
		$q = $this->em->createQuery($qStr);
		$q = $this->addLimit($q, $limit);

		$result = $q->getResult();

		$entities = array();
		foreach($result as $entry){
			$id = $entry->getModule()->getId();
			if(!isset($unique[$id])){
				$entities[] = $entry->getModule();
			}
		}
		$totalRows = count($entities);
		if ($totalRows < 1) {
			return new ModelResponse(null, 0, 0, null, true, 'E:D:002', 'No entries found in database that matches to your criterion.', $timeStamp, time());
		}
		return new ModelResponse($entities, $totalRows, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
	}

    /**
     * @name            listModulesOfPageLayouts()
     *
     * @since           1.0.1
     * @version         1.2.5
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           array 			$filter
     * @param           array 			$sortOrder
     * @param           array 			$limit
     *
     * @return          array           $response
     */
    public function listModulesOfPageLayouts($filter = null, $sortOrder = null, $limit = null){
		$timeStamp = time();
		if(!is_array($sortOrder) && !is_null($sortOrder)){
			return $this->createException('InvalidSortOrderException', '$sortOrder must be an array with key => value pairs where value can only be "asc" or "desc".', 'E:S:002');
		}
		$oStr = $wStr = $gStr = $fStr = '';

		$qStr = 'SELECT '.$this->entity['mol']['alias'].', '.$this->entity['p']['alias'] . ', '.$this->entity['l']['alias']
            			. ' FROM ' . $this->entity['mol']['name'] . ' ' . $this->entity['mol']['alias']
            			. ' JOIN ' . $this->entity['mol']['alias'] . '.page ' . $this->entity['p']['alias']
           				. ' JOIN ' . $this->entity['mol']['alias'] . '.layout ' . $this->entity['l']['alias'];

		if(!is_null($sortOrder)){
			foreach($sortOrder as $column => $direction){
				switch($column){
					case 'id':
					case 'layout':
					case 'module':
					case 'section':
					case 'sort_order':
					case 'page':
					case 'style':
						$column = $this->entity['mol']['alias'].'.'.$column;
						break;
				}
				$oStr .= ' '.$column.' '.strtoupper($direction).', ';
			}
			$oStr = rtrim($oStr, ', ');
			$oStr = ' ORDER BY '.$oStr.' ';
		}

		if(!is_null($filter)){
			$fStr = $this->prepareWhere($filter);
			$wStr .= ' WHERE '.$fStr;
		}

        $qStr .= $wStr.$gStr.$oStr;
        $q = $this->em->createQuery($qStr);
		$q = $this->addLimit($q, $limit);
        $result = $q->getResult();

		$totalRows = count($result);
		if ($totalRows < 1) {
			return new ModelResponse(null, 0, 0, null, true, 'E:D:002', 'No entries found in database that matches to your criterion.', $timeStamp, time());
		}
		return new ModelResponse($result, $totalRows, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
	}

    /**
     * @name            listModulesOfPageLayoutsGroupedBySection()
	 *
     * @since           1.0.1
     * @version         1.2.5
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           array 		$page
     * @param           array 		$sortOrder
     * @param           array 		$limit
     *
     * @return          array		$response
     */
    public function listModulesOfPageLayoutsGroupedBySection($page, $sortOrder = null, $limit = null){
        $timeStamp = time();
		$response = $this->getPage($page);
		if($response->error->exist){
			return $response;
		}
		$page = $response->result->set;
		$filter[] = array(
			'glue' => 'and',
			'condition' => array(
				array(
					'glue' => 'and',
					'condition' => array('column' => $this->entity['p']['alias'].'.id', 'comparison' => '=', 'value' => $page->getId()),
				)
			)
		);
        $response = $this->listModulesOfPageLayouts($filter, $sortOrder, $limit);
        if ($response->error->exist) {
            return $response;
        }
        $mops = $response->result->set;
        $modules = array();
        $count = 0;
        foreach ($mops as $mop) {
            $modules[$mop->getSection()][$count]['entity'] = $mop->getModule();
            $modules[$mop->getSection()][$count]['style'] = $mop->getStyle();
            $count++;
        }

		if ($count < 1) {
			return new ModelResponse(null, 0, 0, null, true, 'E:D:002', 'No entries found in database that matches to your criterion.', $timeStamp, time());
		}
		return new ModelResponse($modules, $count, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
	}

    /**
     * @name            listModulesOfSite ()
     *
     * @since           1.0.1
     * @version         1.2.1
     * @author          Can Berkol
     *
     * @use             $this->listThemes()
     *
     * @param           mixed 		$site
     * @param           array		$filter
     * @param           array 		$sortOrder
     * @param           array 		$limit
     *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listModulesOfSite($site, $filter = null, $sortOrder = null, $limit = null)    {
		$timeStamp = time();
		$sModel = $this->kernel->getContainer()->get('sitemanagement.model');
		$response = $sModel->getSite($site);
		if($response->error->exist){
			return $response;
		}
		$site = $response->result->set;
		$filter[] = array(
			'glue' => 'and',
			'condition' => array(
				array(
					'glue' => 'and',
					'condition' => array('column' => $this->entity['m']['alias'].'.site', 'comparison' => '=', 'value' => $site->getId()),
				)
			)
		);
		$response = $this->listModules($filter, $sortOrder, $limit);
		$response->stats->execution->start = $timeStamp;
		$response->stats->execution->end = time();

		return $response;
	}

	/**
	 * @name            listModulesOfTheme()
	 *
	 * @since           1.0.1
	 * @version         1.2.1
	 * @author          Can Berkol
	 *
	 * @use             $this->listLayouts()
	 *
	 * @param           mixed 		$theme
	 * @param			array		$filter
	 * @param           array 		$sortOrder
	 * @param           array 		$limit
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listModulesOfTheme($theme, $filter = null, $sortOrder = null, $limit = null)    {
		$timeStamp = time();
		$response = $this->getTheme($theme);
		if($response->error->exist){
			return $response;
		}
		$theme = $response->result->set;
		$filter[] = array(
			'glue' => 'and',
			'condition' => array(
				array(
					'glue' => 'and',
					'condition' => array('column' => $this->entity['m']['alias'].'.theme', 'comparison' => '=', 'value' => $theme->getId()),
				)
			)
		);
		$response = $this->listModules($filter, $sortOrder, $limit);
		$response->stats->execution->start = $timeStamp;
		$response->stats->execution->end = time();

		return $response;
	}

	/**
	 * @name            listNavigationItems()
	 *
	 * @since           1.0.1
	 * @version         1.2.7
	 * @author          Can Berkol
	 * @author          Said İmamoğlu
	 *
	 * @use             $this->createException
	 *
	 * @param           array 		$filter
	 * @param           array 		$sortOrder
	 * @param           array 		$limit
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listNavigationItems($filter = null, $sortOrder = null, $limit = null){
		$timeStamp = time();
		if(!is_array($sortOrder) && !is_null($sortOrder)){
			return $this->createException('InvalidSortOrderException', '$sortOrder must be an array with key => value pairs where value can only be "asc" or "desc".', 'E:S:002');
		}
		$oStr = $wStr = $gStr = $fStr = '';

		$qStr = 'SELECT '.$this->entity['ni']['alias'].', '.$this->entity['nil']['alias']
			.' FROM '.$this->entity['nil']['name'].' '.$this->entity['nil']['alias']
			.' JOIN '.$this->entity['nil']['alias'].'.navigation_item '.$this->entity['ni']['alias'];

		if(!is_null($sortOrder)){
			foreach($sortOrder as $column => $direction){
				switch($column){
					case 'id':
					case 'url':
					case 'target':
					case 'sort_order':
					case 'navigation':
					case 'page':
						$column = $this->entity['ni']['alias'].'.'.$column;
						break;
					case 'title':
					case 'url_key':
						$column = $this->entity['nil']['alias'].'.'.$column;
						break;
				}
				$oStr .= ' '.$column.' '.strtoupper($direction).', ';
			}
			$oStr = rtrim($oStr, ', ');
			$oStr = ' ORDER BY '.$oStr.' ';
		}

		if(!is_null($filter)){
			$fStr = $this->prepareWhere($filter);
			$wStr .= ' WHERE '.$fStr;
		}

		$qStr .= $wStr.$gStr.$oStr;
		$q = $this->em->createQuery($qStr);
		$q = $this->addLimit($q, $limit);

		$result = $q->getResult();

		$entities = array();
		foreach($result as $entry){
			$id = $entry->getNavigationItem()->getId();
			if(!isset($entities[$id])){
				$entities[] = $entry->getNavigationItem();
			}
		}
		$totalRows = count($entities);
		if ($totalRows < 1) {
			return new ModelResponse(null, 0, 0, null, true, 'E:D:002', 'No entries found in database that matches to your criterion.', $timeStamp, time());
		}
		return new ModelResponse($entities, $totalRows, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
	}

    /**
     * @name            listNavigationItemsOfNavigation ()
     *
     * @since           1.0.1
     * @version         1.2.1
     * @author          Can Berkol
     *
     * @use             $this->listNavigationItems()
     *
     * @param           mixed 		$navigation
     * @param           mixed 		$level
     * @param           array 		$sortOrder
     * @param           array 		$limit
     *
     * @return          \BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
     */
    public function listNavigationItemsOfNavigation($navigation, $level = 'top', $sortOrder = null, $limit = null){
        $timeStamp = time();
		$response = $this->getNavigation($navigation);
		if($response->error->exist){
			return $response;
		}
        $navigation = $response->result->set;
        $filter[] = array(
            'glue' => 'and',
            'condition' => array(
                array(
                    'glue' => 'and',
                    'condition' => array('column' => $this->entity['ni']['alias'].'.navigation', 'comparison' => '=', 'value' => $navigation->getId()),
                )
            )
        );
        $isChild = 'n';
        switch ($level) {
            case 'top':
                $isChild = 'n';
                break;
            case 'bottom':
                $isChild = 'y';
                break;
        }
        $filter[] = array(
            'glue' => 'and',
            'condition' => array(
                array(
                    'glue' => 'and',
                    'condition' => array('column' => $this->entity['ni']['alias'].'.is_child', 'comparison' => '=', 'value' => $isChild),
                )
            )
        );
        $response =  $this->listNavigationItems($filter, $sortOrder, $limit);
		$response->stats->execution->start = $timeStamp;
		$response->stats->execution->end = time();

		return $response;
    }

    /**
     * @name            listNavigationItemsOfParent()
     *
     * @since           1.0.1
     * @version         1.2.1
     * @author          Can Berkol
     *
     * @use             $this->listNavigationItems()
     *
     * @param           mixed 		$parent
     * @param           array 		$sortOrder
     * @param           array 		$limit
     *
     * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
     */
    public function listNavigationItemsOfParent($parent, $sortOrder = null, $limit = null){
        $timeStamp = time();
		$response = $this->getNavigationItem($parent);
		if($response->error->exist){
			return $response;
		}
		$parent = $response->result->set;
        $filter[] = array(
            'glue' => 'and',
            'condition' => array(
                array(
                    'glue' => 'and',
                    'condition' => array('column' => $this->entity['ni']['alias'].'.parent', 'comparison' => '=', 'value' => $parent->getId()),
                )
            )
        );
        $response = $this->listNavigationItems($filter, $sortOrder, $limit);
		$response->stats->execution->start = $timeStamp;
		$response->stats->execution->end = time();

		return $response;
    }

    /**
     * @name            listNavigations()
     *
     * @since           1.0.1
     * @version         1.2.1
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           array 			$filter
     * @param           array 			$sortOrder
     * @param           array 			$limit
     *
     * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
     */
	public function listNavigations($filter = null, $sortOrder = null, $limit = null){
		$timeStamp = time();
		if(!is_array($sortOrder) && !is_null($sortOrder)){
			return $this->createException('InvalidSortOrderException', '$sortOrder must be an array with key => value pairs where value can only be "asc" or "desc".', 'E:S:002');
		}
		$oStr = $wStr = $gStr = $fStr = '';

		$qStr = 'SELECT '.$this->entity['n']['alias'].', '.$this->entity['nl']['alias']
			.' FROM '.$this->entity['nl']['name'].' '.$this->entity['nl']['alias']
			.' JOIN '.$this->entity['nl']['alias'].'.navigation '.$this->entity['n']['alias'];

		if(!is_null($sortOrder)){
			foreach($sortOrder as $column => $direction){
				switch($column){
					case 'id':
					case 'code':
					case 'site':
					case 'date_added':
					case 'date_updated':
					case 'date_removed':
						$column = $this->entity['n']['alias'].'.'.$column;
						break;
					case 'name':
					case 'url_key':
						$column = $this->entity['nl']['alias'].'.'.$column;
						break;
				}
				$oStr .= ' '.$column.' '.strtoupper($direction).', ';
			}
			$oStr = rtrim($oStr, ', ');
			$oStr = ' ORDER BY '.$oStr.' ';
		}

		if(!is_null($filter)){
			$fStr = $this->prepareWhere($filter);
			$wStr .= ' WHERE '.$fStr;
		}

		$qStr .= $wStr.$gStr.$oStr;
		$q = $this->em->createQuery($qStr);
		$q = $this->addLimit($q, $limit);

		$result = $q->getResult();

		$entities = array();
		foreach($result as $entry){
			$id = $entry->getNavigation()->getId();
			if(!isset($unique[$id])){
				$entities[] = $entry->getNavigation();
			}
		}
		$totalRows = count($entities);
		if ($totalRows < 1) {
			return new ModelResponse(null, 0, 0, null, true, 'E:D:002', 'No entries found in database that matches to your criterion.', $timeStamp, time());
		}
		return new ModelResponse($entities, $totalRows, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
	}

	/**
	 * @name            listPageRevisions()
	 *
	 * @since           1.1.9
	 * @version         1.2.1
	 * @author          Can Berkol
	 *
	 * @use             $this->createException()
	 *
	 * @param           array 			$filter
	 * @param           array 			$sortOrder
	 * @param           array 			$limit
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listPageRevisions($filter = null, $sortOrder = null, $limit = null){
		$timeStamp = time();
		if(!is_array($sortOrder) && !is_null($sortOrder)){
			return $this->createException('InvalidSortOrderException', '$sortOrder must be an array with key => value pairs where value can only be "asc" or "desc".', 'E:S:002');
		}
		$oStr = $wStr = $gStr = $fStr = '';

		$qStr = 'SELECT '.$this->entity['pr']['alias'].', '.$this->entity['pr']['alias']
			.' FROM '.$this->entity['pr']['name'].' '.$this->entity['pr']['alias'];

		if(!is_null($sortOrder)){
			foreach($sortOrder as $column => $direction){
				switch($column){
					case 'page':
					case 'language':
					case 'title':
					case 'url_key':
					case 'meta_title':
					case 'revision_number':
					case 'date_added':
					case 'date_removed':
						$column = $this->entity['pr']['alias'].'.'.$column;
						break;
				}
				$oStr .= ' '.$column.' '.strtoupper($direction).', ';
			}
			$oStr = rtrim($oStr, ', ');
			$oStr = ' ORDER BY '.$oStr.' ';
		}

		if(!is_null($filter)){
			$fStr = $this->prepareWhere($filter);
			$wStr .= ' WHERE '.$fStr;
		}

		$qStr .= $wStr.$gStr.$oStr;
		$q = $this->em->createQuery($qStr);
		$q = $this->addLimit($q, $limit);

		$result = $q->getResult();

		$totalRows = count($result);
		if ($totalRows < 1) {
			return new ModelResponse(null, 0, 0, null, true, 'E:D:002', 'No entries found in database that matches to your criterion.', $timeStamp, time());
		}
		return new ModelResponse($result, $totalRows, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
	}
    /**
     * @name            listPages()
     *
     * @since           1.0.0
     * @version         1.2.4
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           array 			$filter
     * @param           array 			$sortOrder
     * @param           array 			$limit
     *
     * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
     */
	public function listPages($filter = null, $sortOrder = null, $limit = null){
		$timeStamp = time();
		if(!is_array($sortOrder) && !is_null($sortOrder)){
			return $this->createException('InvalidSortOrderException', '$sortOrder must be an array with key => value pairs where value can only be "asc" or "desc".', 'E:S:002');
		}
		$oStr = $wStr = $gStr = $fStr = '';

		$qStr = 'SELECT '.$this->entity['p']['alias'].', '.$this->entity['pl']['alias']
			.' FROM '.$this->entity['pl']['name'].' '.$this->entity['pl']['alias']
			.' JOIN '.$this->entity['pl']['alias'].'.page '.$this->entity['p']['alias'];

		if(!is_null($sortOrder)){
			foreach($sortOrder as $column => $direction){
				switch($column){
					case 'id':
					case 'code':
					case 'status':
					case 'bundle_name':
						$column = $this->entity['p']['alias'].'.'.$column;
						break;
					case 'name':
					case 'url_key':
						$column = $this->entity['pl']['alias'].'.'.$column;
						break;
				}
				$oStr .= ' '.$column.' '.strtoupper($direction).', ';
			}
			$oStr = rtrim($oStr, ', ');
			$oStr = ' ORDER BY '.$oStr.' ';
		}

		if(!is_null($filter)){
			$fStr = $this->prepareWhere($filter);
			$wStr .= ' WHERE '.$fStr;
		}

		$qStr .= $wStr.$gStr.$oStr;
		$q = $this->em->createQuery($qStr);
		$q = $this->addLimit($q, $limit);

		$result = $q->getResult();

		$entities = array();
		foreach($result as $entry){
			$id = $entry->getPage()->getId();
			if(!isset($unique[$id])){
				$entities[] = $entry->getPage();
			}
		}
		$totalRows = count($entities);
		if ($totalRows < 1) {
			return new ModelResponse(null, 0, 0, null, true, 'E:D:002', 'No entries found in database that matches to your criterion.', $timeStamp, time());
		}
		return new ModelResponse($entities, $totalRows, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
	}

    /**
     * @name            listPagesOfLayout()
     *
     * @since           1.0.1
     * @version         1.2.1
     * @author          Can Berkol
     *
     * @use             $this->listPages()

     *
     * @param           mixed 			$layout
	 * @param           array           $sortOrder
	 * @param 			array			$limit
	 *
     * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
     */
    public function listPagesOfLayout($layout, $sortOrder, $limit) {
        $timeStamp = time();
        $response = $this->getLayout($layout);
		if($response->error->exist){
			return $response;
		}
		$layout = $response->result->set;

		$filter[] = array(
			'glue' => 'and',
			'condition' => array(
				array(
					'glue' => 'and',
					'condition' => array('column' => $this->entity['p']['alias'].'.layout', 'comparison' => '=', 'value' => $layout->getId()),
				)
			)
		);

        $response = $this->listPages($filter, $sortOrder, $limit);
		$response->stats->execution->start = $timeStamp;
		$response->stats->execution->end = time();

		return $response;
    }

    /**
     * @name            listPagesOfSite ()
     *
     * @since           1.0.1
     * @version         1.1.2
     * @author          Can Berkol
     *
     * @use             $this->listPages()
     *
     * @param           mixed	 	$site
     * @param           array 		$sortOrder
     * @param           array 		$limit
     *
     * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
     */
    public function listPagesOfSite($site = 1, $sortOrder, $limit){
		$timeStamp = time();
		$sModel = $this->kernel->getContainer()->get('sitemanagement.model');
		$response = $sModel->getSite($site);
		if($response->error->exist){
			return $response;
		}
		$layout = $response->result->set;

		$filter[] = array(
			'glue' => 'and',
			'condition' => array(
				array(
					'glue' => 'and',
					'condition' => array('column' => $this->entity['p']['alias'].'.layout', 'comparison' => '=', 'value' => $layout->getId()),
				)
			)
		);

		$response = $this->listPages($filter, $sortOrder, $limit);
		$response->stats->execution->start = $timeStamp;
		$response->stats->execution->end = time();

		return $response;
    }

	/**
	 * @name            listThemes()
	 *
	 * @since           1.0.0
	 * @version         1.2.1
	 * @author          Can Berkol
	 *
	 * @use             $this->createException()
	 *
	 * @param           array 			$filter
	 * @param           array 			$sortOrder
	 * @param           array 			$limit
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listThemes($filter = null, $sortOrder = null, $limit = null){
		$timeStamp = time();
		if(!is_array($sortOrder) && !is_null($sortOrder)){
			return $this->createException('InvalidSortOrderException', '$sortOrder must be an array with key => value pairs where value can only be "asc" or "desc".', 'E:S:002');
		}
		$oStr = $wStr = $gStr = $fStr = '';

		$qStr = 'SELECT '.$this->entity['t']['alias'].', '.$this->entity['t']['alias']
			.' FROM '.$this->entity['tl']['name'].' '.$this->entity['tl']['alias']
			.' JOIN '.$this->entity['tl']['alias'].'.theme '.$this->entity['t']['alias'];

		if(!is_null($sortOrder)){
			foreach($sortOrder as $column => $direction){
				switch($column){
					case 'id':
					case 'folder':
					case 'type':
					case 'date_added':
					case 'date_updated':
					case 'date_removed':
					case 'count_modules':
					case 'count_layouts':
					case 'site':
						$column = $this->entity['t']['alias'].'.'.$column;
						break;
					case 'name':
						$column = $this->entity['tl']['alias'].'.'.$column;
						break;
				}
				$oStr .= ' '.$column.' '.strtoupper($direction).', ';
			}
			$oStr = rtrim($oStr, ', ');
			$oStr = ' ORDER BY '.$oStr.' ';
		}

		if(!is_null($filter)){
			$fStr = $this->prepareWhere($filter);
			$wStr .= ' WHERE '.$fStr;
		}

		$qStr .= $wStr.$gStr.$oStr;
		$q = $this->em->createQuery($qStr);
		$q = $this->addLimit($q, $limit);

		$result = $q->getResult();

		$entities = array();
		foreach($result as $entry){
			$id = $entry->getTheme()->getId();
			if(!isset($unique[$id])){
				$entities[] = $entry->getTheme();
			}
		}
		$totalRows = count($entities);
		if ($totalRows < 1) {
			return new ModelResponse(null, 0, 0, null, true, 'E:D:002', 'No entries found in database that matches to your criterion.', $timeStamp, time());
		}
		return new ModelResponse($entities, $totalRows, 0, null, false, 'S:D:002', 'Entries successfully fetched from database.', $timeStamp, time());
	}

	/**
	 * @name            listThemesOfSite()
	 *
	 * @since           1.0.1
	 * @version         1.1.2
	 * @author          Can Berkol
	 *
	 * @use             $this->listPages()
	 *
	 * @param           mixed	 	$site
	 * @param           array 		$sortOrder
	 * @param           array 		$limit
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function listThemesOfSite($site = 1, $sortOrder, $limit){
		$timeStamp = time();
		$sModel = $this->kernel->getContainer()->get('sitemanagement.model');
		$response = $sModel->getSite($site);
		if($response->error->exist){
			return $response;
		}
		$layout = $response->result->set;

		$filter[] = array(
			'glue' => 'and',
			'condition' => array(
				array(
					'glue' => 'and',
					'condition' => array('column' => $this->entity['p']['alias'].'.layout', 'comparison' => '=', 'value' => $layout->getId()),
				)
			)
		);

		$response = $this->listThemes($filter, $sortOrder, $limit);
		$response->stats->execution->start = $timeStamp;
		$response->stats->execution->end = time();

		return $response;
	}
	/**
	 * @name            markPagesAsDeleted()
	 *
	 * @since           1.2.6
	 * @version         1.2.6
	 *
	 * @author          Can Berkol
	 *
	 * @use             $this->createException()
	 *
	 * @param           array 			$collection
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function markPagesAsDeleted($collection){
		$timeStamp = time();
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$now = new \DateTime('now', new \DateTimeZone($this->kernel->getContainer()->getParameter('app_timezone')));
		$toUpdate = array();
		foreach ($collection as $page) {
			if(!$page instanceof BundleEntity\Page){
				$response = $this->getPage($page);
				if($response->error->exist){
					return $response;
				}
				$page = $response->result->set;
				unset($response);
			}
			$page->setStatus('d');
			$toUpdate[] = $page;
		}
		$response = $this->updatePages($toUpdate);
		$response->stats->execution->start = $timeStamp;
		$response->stats->execution->end = time();

		return $response;
	}
	/**
	 * @name            updateLayout ()
	 *
	 * @since           1.0.1
	 * @version         1.2.1
	 * @author          Can Berkol
	 *
	 * @use             $this->updateLayouts()
	 *
	 * @param           mixed 			$layout
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function updateLayout($layout){
		return $this->updateLayouts(array($layout));
	}

	/**
	 * @name            updateLayouts ()
	 *
	 * @since           1.0.1
	 * @version         1.2.4
	 * @author          Can Berkol
	 *
	 * @use             $this->createException()
	 *
	 * @param           array 			$collection
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function updateLayouts($collection){
		/** Parameter must be an array */
		if (!is_array($collection)) {
			return $this->createException('InvalidParameter', 'Array', 'err.invalid.parameter.collection');
		}
		$countUpdates = 0;
		$updatedItems = array();
		foreach ($collection as $data) {
			if ($data instanceof BundleEntity\Layout) {
				$entity = $data;
				$this->em->persist($entity);
				$updatedItems[] = $entity;
				$countUpdates++;
			}
			else if (is_object($data)) {
				if(!property_exists($data, 'id') || !is_numeric($data->id)){
					return $this->createException('InvalidParameterException', 'Parameter must be an object with the "id" property and id property ​must have an integer value.', 'E:S:003');
				}
				if (!property_exists($data, 'site')) {
					$data->site = 1;
				}
				if (!property_exists($data, 'theme')) {
					$data->theme = 1;
				}
				$response = $this->getLayout($data->id, 'id');
				if ($response->error->exist) {
					return $this->createException('EntityDoesNotExist', 'Layout with id ' . $data->id, 'err.invalid.entity');
				}
				$oldEntity = $response->result->set;
				foreach ($data as $column => $value) {
					$set = 'set' . $this->translateColumnName($column);
					switch ($column) {
						case 'local':
							$localizations = array();
							foreach ($value as $langCode => $translation) {
								$localization = $oldEntity->getLocalization($langCode, true);
								$newLocalization = false;
								if (!$localization) {
									$newLocalization = true;
									$localization = new BundleEntity\LayoutLocalization();
									$mlsModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
									$response = $mlsModel->getLanguage($langCode, 'iso_code');
									$localization->setLanguage($response->result->set);
									$localization->setLayout($oldEntity);
								}
								foreach ($translation as $transCol => $transVal) {
									$transSet = 'set' . $this->translateColumnName($transCol);
									$localization->$transSet($transVal);
								}
								if ($newLocalization) {
									$this->em->persist($localization);
								}
								$localizations[] = $localization;
							}
							$oldEntity->setLocalizations($localizations);
							break;
						case 'theme':
							$response = $this->getTheme($value);
							if (!$response->error->exist) {
								$oldEntity->$set($response->result->set);
							}
							else {
								return $this->createException('EntityDoesNotExist', 'Theme with id '.$value.' does not exist in database.', 'E:D:002');
							}
							unset($response, $fModel);
							break;
						case 'site':
							$sModel = $this->kernel->getContainer()->get('sitemanagement.model');
							$response = $sModel->getSite($value);
							if (!$response->error->exist) {
								$oldEntity->$set($response->result->set);
							} else {
								return $this->createException('EntityDoesNotExist', 'Site with id  / url_key'.$value.' does not exist in database.', 'E:D:002');
							}
							unset($response, $sModel);
							break;
						case 'id':
							break;
						default:
							$oldEntity->$set($value);
							break;
					}
					if ($oldEntity->isModified()) {
						$this->em->persist($oldEntity);
						$countUpdates++;
						$updatedItems[] = $oldEntity;
					}
				}
			}
		}
		if($countUpdates > 0){
			$this->em->flush();
			return new ModelResponse($updatedItems, $countUpdates, 0, null, false, 'S:D:004', 'Selected entries have been successfully updated within database.', $timeStamp, time());
		}
		return new ModelResponse(null, 0, 0, null, true, 'E:D:004', 'One or more entities cannot be updated within database.', $timeStamp, time());
	}
	/**
	 * @name            updateModule ()
	 *
	 * @since           1.0.2
	 * @version         1.2.1
	 * @author          Can Berkol
	 *
	 * @use             $this->updateModules()
	 *
	 * @param           mixed			$module
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function updateModule($module){
		return $this->updateModules(array($module));
	}
	/**
	 * @name            updateModules ()
	 *
	 * @since           1.0.2
	 * @version         1.2.1
	 * @author          Can Berkol
	 *
	 * @use             $this->createException
	 *
	 * @param           array			$collection
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function updateModules($collection){
		$timeStamp = time();
		/** Parameter must be an array */
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countUpdates = 0;
		$updatedItems = array();
		foreach ($collection as $data) {
			if ($data instanceof BundleEntity\Module) {
				$entity = $data;
				$this->em->persist($entity);
				$updatedItems[] = $entity;
				$countUpdates++;
			}
			else if (is_object($data)) {
				if(!property_exists($data, 'id') || !is_numeric($data->id)){
					return $this->createException('InvalidParameterException', 'Parameter must be an object with the "id" property and id property ​must have an integer value.', 'E:S:003');
				}
				if (!property_exists($data, 'site')) {
					$data->site = 1;
				}
				if (!property_exists($data, 'theme')) {
					$data->theme = 1;
				}
				$response = $this->getModule($data->id);
				if ($response->errpr->exist) {
					return $this->createException('EntityDoesNotExist', 'Module with id '.$data->id.' does not exist in database.', 'E:D:002');
				}
				$oldEntity = $response->result->set;
				foreach ($data as $column => $value) {
					$set = 'set' . $this->translateColumnName($column);
					switch ($column) {
						case 'local':
							$localizations = array();
							foreach ($value as $langCode => $translation) {
								$localization = $oldEntity->getLocalization($langCode, true);
								$newLocalization = false;
								if (!$localization) {
									$newLocalization = true;
									$localization = new BundleEntity\ModuleLocalization();
									$mlsModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
									$response = $mlsModel->getLanguage($langCode, 'iso_code');
									$localization->setLanguage($response->result->set);
									$localization->setModule($oldEntity);
								}
								foreach ($translation as $transCol => $transVal) {
									$transSet = 'set' . $this->translateColumnName($transCol);
									$localization->$transSet($transVal);
								}
								if ($newLocalization) {
									$this->em->persist($localization);
								}
								$localizations[] = $localization;
							}
							$oldEntity->setLocalizations($localizations);
							break;
						case 'theme':
							$response = $this->getTheme($value, 'id');
							if (!$response->error->exist) {
								$oldEntity->$set($response->result->set);
							}
							else {
								return $this->createException('EntityDoesNotExist', 'Theme with id / code '.$value.' does not exist in database.', 'E:D:002');
							}
							unset($response);
							break;
						case 'site':
							$sModel = $this->kernel->getContainer()->get('sitemanagement.model');
							$response = $sModel->getSite($value, 'id');
							if (!$response->error->exist) {
								$oldEntity->$set($response->result->set);
							}
							else {
								return $this->createException('EntityDoesNotExist', 'Site with id /url_key '.$value.' does not exist in database.', 'E:D:002');
							}
							unset($response, $sModel);
							break;
						case 'id':
							break;
						default:
							$oldEntity->$set($value);
							break;
					}
					if ($oldEntity->isModified()) {
						$this->em->persist($oldEntity);
						$countUpdates++;
						$updatedItems[] = $oldEntity;
					}
				}
			}
		}
		if($countUpdates > 0){
			$this->em->flush();
			return new ModelResponse($updatedItems, $countUpdates, 0, null, false, 'S:D:004', 'Selected entries have been successfully updated within database.', $timeStamp, time());
		}
		return new ModelResponse(null, 0, 0, null, true, 'E:D:004', 'One or more entities cannot be updated within database.', $timeStamp, time());
	}
	/**
	 * @name            updateModuleLayoutEntry ()
	 *
	 * @since           1.1.5
	 * @version         1.2.1
	 * @author          Can Berkol
	 *
	 * @use             $this->updateModuleLayoutEntries()
	 *
	 * @param           mixed 			$entry
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function updateModuleLayoutEntry($entry)    {
		return $this->updateModuleLayoutEntries(array($entry));
	}

	/**
	 * @name            updateModuleLayoutEntries()
	 *
	 * @since           1.1.5
	 * @version         1.2.1
	 * @author          Can Berkol
	 *
	 * @use             $this->createException()
	 *
	 * @param           array 			$collection
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function updateModuleLayoutEntries($collection){
		$timeStamp = time();
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countUpdates = 0;
		$updatedItems = array();
		foreach ($collection as $data) {
			if ($data instanceof BundleEntity\ModulesOfLayout) {
				$entity = $data;
				$this->em->persist($entity);
				$updatedItems[] = $entity;
				$countUpdates++;
			}
			else if (is_object($data)) {
				if(!property_exists($data, 'id') || !is_numeric($data->id)){
					return $this->createException('InvalidParameterException', 'Parameter must be an object with the "id" property and id property ​must have an integer value.', 'E:S:003');
				}
				if (!property_exists($data, 'sort_order')) {
					$data->sort_order = 1;
				}
				$response = $this->getModuleLayoutEntry($data->id);
				if ($response->error->exist) {
					return $this->createException('EntityDoesNotExist', 'ModulesOfLayout with id '.$data->id.' does not exist in database.', 'E:D:002');
				}
				$oldEntity = $response->result->set;
				foreach ($data as $column => $value) {
					$set = 'set' . $this->translateColumnName($column);
					switch ($column) {
						case 'page':
							$response = $this->getPage($value);
							if (!$response->error->exist){
								$oldEntity->$set($response->result->set);
							}
							else {
								return $this->createException('EntityDoesNotExist', 'Page with id / code '.$value.' does not exist in database.', 'E:D:002');
							}
							unset($response);
							break;
						case 'layout':
							$response = $this->getLayout($value);
							if (!$response->error->exist) {
								$oldEntity->$set($response->result->set);
							}
							else {
								return $this->createException('EntityDoesNotExist', 'Layout with id '.$value.' does not exist in database.', 'E:D:002');
							}
							unset($response, $fModel);
							break;
						case 'module':
							$response = $this->getModule($value);
							if (!$response->error->exist) {
								$oldEntity->$set($response->result->set);
							}
							else {
								return $this->createException('EntityDoesNotExist', 'Module with id '.$value.' does not exist in database.', 'E:D:002');
							}
							unset($response, $fModel);
							break;
						case 'id':
							break;
						default:
							$oldEntity->$set($value);
							break;
					}
					if ($oldEntity->isModified()) {
						$this->em->persist($oldEntity);
						$countUpdates++;
						$updatedItems[] = $oldEntity;
					}
				}
			}
		}
		if($countUpdates > 0){
			$this->em->flush();
			return new ModelResponse($updatedItems, $countUpdates, 0, null, false, 'S:D:004', 'Selected entries have been successfully updated within database.', $timeStamp, time());
		}
		return new ModelResponse(null, 0, 0, null, true, 'E:D:004', 'One or more entities cannot be updated within database.', $timeStamp, time());
	}
	/**
	 * @name            updateNavigation()
	 *
	 * @since           1.0.1
	 * @version         1.2.1
	 * @author          Can Berkol
	 *
	 * @use             $this->updateNavigations()
	 *
	 * @param           mixed 			$navigation
	 * @return			BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function updateNavigation($navigation){
		return $this->updateNavigations(array($navigation));
	}

	/**
	 * @name            updateNavigations ()
	 *
	 * @since           1.0.1
	 * @version         1.2.1
	 * @author          Can Berkol
	 *
	 * @use             $his->createException()
	 *
	 * @param           array 			$collection
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function updateNavigations($collection){
		$timeStamp = time();
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countUpdates = 0;
		$updatedItems = array();
		foreach ($collection as $data) {
			if ($data instanceof BundleEntity\Navigation) {
				$entity = $data;
				$this->em->persist($entity);
				$updatedItems[] = $entity;
				$countUpdates++;
			}
			else if (is_object($data)) {
				if(!property_exists($data, 'id') || !is_numeric($data->id)){
					return $this->createException('EntityDoesNotExist', 'Navigation with id '.$data->id.' does not exist in database.', 'E:D:002');
				}
				if (property_exists($data, 'date_added')) {
					unset($data->date_added);
				}
				if (!property_exists($data, 'site')) {
					$data->site = 1;
				}
				$response = $this->getNavigation($data->id);
				if ($response->error->exist) {
					return $this->createException('EntityDoesNotExist', 'Navigation with id '.$value.' does not exist in database.', 'E:D:002');
				}
				$oldEntity = $response->result->set;
				foreach ($data as $column => $value) {
					$set = 'set' . $this->translateColumnName($column);
					switch ($column) {
						case 'local':
							$localizations = array();
							foreach ($value as $langCode => $translation) {
								$localization = $oldEntity->getLocalization($langCode, true);
								$newLocalization = false;
								if (!$localization) {
									$newLocalization = true;
									$localization = new BundleEntity\NavigationLocalization();
									$mlsModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
									$response = $mlsModel->getLanguage($langCode);
									$localization->setLanguage($response->result->set);
									$localization->setNavigation($oldEntity);
								}
								foreach ($translation as $transCol => $transVal) {
									$transSet = 'set' . $this->translateColumnName($transCol);
									$localization->$transSet($transVal);
								}
								if ($newLocalization) {
									$this->em->persist($localization);
								}
								$localizations[] = $localization;
							}
							$oldEntity->setLocalizations($localizations);
							break;
						case 'site':
							$sModel = $this->kernel->getContainer()->get('sitemanagement.model');
							$response = $sModel->getSite($value);
							if (!$response->error->exist) {
								$oldEntity->$set($response->result->set);
							}
							else {
								return $this->createException('EntityDoesNotExist', 'Site with id / url_key '.$value.' does not exist in database.', 'E:D:002');
							}
							unset($response, $sModel);
							break;
						case 'id':
							break;
						default:
							$oldEntity->$set($value);
							break;
					}
					if ($oldEntity->isModified()) {
						$this->em->persist($oldEntity);
						$countUpdates++;
						$updatedItems[] = $oldEntity;
					}
				}
			}
		}
		if($countUpdates > 0){
			$this->em->flush();
			return new ModelResponse($updatedItems, $countUpdates, 0, null, false, 'S:D:004', 'Selected entries have been successfully updated within database.', $timeStamp, time());
		}
		return new ModelResponse(null, 0, 0, null, true, 'E:D:004', 'One or more entities cannot be updated within database.', $timeStamp, time());
	}
	/**
	 * @name            updateNavigationItem ()
	 *
	 * @since           1.0.1
	 * @version         1.2.1
	 * @author          Can Berkol
	 *
	 * @use             $this->update_themes()
	 *
	 * @param           mixed           $item
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function updateNavigationItem($item){
		return $this->updateNavigationItems(array($item));
	}

	/**
	 * @name            updateNavigationItems ()
	 *
	 * @since           1.0.1
	 * @version         1.2.1
	 * @author          Can Berkol
	 *
	 * @use             $this->createException()
	 *
	 * @param           array 			$collection
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function updateNavigationItems($collection){
		$timeStamp = time();
		/** Parameter must be an array */
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countUpdates = 0;
		$updatedItems = array();
		foreach ($collection as $data) {
			if ($data instanceof BundleEntity\NavigationItem) {
				$entity = $data;
				$this->em->persist($entity);
				$updatedItems[] = $entity;
				$countUpdates++;
			}
			else if (is_object($data)) {
				if(!property_exists($data, 'id') || !is_numeric($data->id)){
					return $this->createException('InvalidParameterException', 'Parameter must be an object with the "id" property and id property ​must have an integer value.', 'E:S:003');
				}
				$response = $this->getNavigationItem($data->id);
				if ($response->error->exist) {
					return $this->createException('EntityDoesNotExist', 'Navigation Item with id '.$data->id.' does not exist in database.', 'E:D:002');
				}
				$oldEntity = $response->result->set;
				foreach ($data as $column => $value) {
					$set = 'set' . $this->translateColumnName($column);
					switch ($column) {
						case 'local':
							$localizations = array();
							foreach ($value as $langCode => $translation) {
								$localization = $oldEntity->getLocalization($langCode, true);
								$newLocalization = false;
								if (!$localization) {
									$newLocalization = true;
									$localization = new BundleEntity\NavigationItemLocalization();
									$mlsModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
									$response = $mlsModel->getLanguage($langCode);
									$localization->setLanguage($response->result->set);
									$localization->setNavigationItem($oldEntity);
								}
								foreach ($translation as $transCol => $transVal) {
									$transSet = 'set' . $this->translateColumnName($transCol);
									$localization->$transSet($transVal);
								}
								if ($newLocalization) {
									$this->em->persist($localization);
								}
								$localizations[] = $localization;
							}
							$oldEntity->setLocalizations($localizations);
							break;
						case 'page':
							$response = $this->getPage($value);
							if (!$response->error->exist) {
								$oldEntity->$set($response->result->set);
							}
							else {
								return $this->createException('EntityDoesNotExist', 'Page with id / code '.$value.' does not exist in database.', 'E:D:002');
							}
							unset($response);
							break;
						case 'parent':
							$response = $this->getNavigationItem($value);
							if (!$response->error->exist) {
								$oldEntity->$set($response->result->set);
							}
							else {
								return $this->createException('EntityDoesNotExist', 'NavigationItem with id'.$value.' does not exist in database.', 'E:D:002');
							}
							unset($response, $fModel);
							break;
						case 'navigation':
							$response = $this->getNavigation($value);
							if (!$response->error->exist) {
								$oldEntity->$set($response->result->set);
							} else {
								return $this->createException('EntityDoesNotExist', 'Navigation with id '.$value.' does not exist in database.', 'E:D:002');
							}
							unset($response, $sModel);
							break;
						case 'id':
							break;
						default:
							$oldEntity->$set($value);
							break;
					}
					if ($oldEntity->isModified()) {
						$this->em->persist($oldEntity);
						$countUpdates++;
						$updatedItems[] = $oldEntity;
					}
				}
			}
		}
		if($countUpdates > 0){
			$this->em->flush();
			return new ModelResponse($updatedItems, $countUpdates, 0, null, false, 'S:D:004', 'Selected entries have been successfully updated within database.', $timeStamp, time());
		}
		return new ModelResponse(null, 0, 0, null, true, 'E:D:004', 'One or more entities cannot be updated within database.', $timeStamp, time());
	}
    /**
     * @name            updatePage ()
     *
     * @since           1.0.0
     * @version         1.2.1
     * @author          Can Berkol
     *
     * @use             $this->updatePages()
     *
     * @param           mixed 			$page
	 *
     * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
     */
    public function updatePage($page){
        return $this->updatePages(array($page));
    }
	/**
	 * @name            updatePageRevision()
	 *
	 * @since           1.1.9
	 * @version         1.2.1
	 * @author          Can Berkol
	 *
	 * @use             $this->updatePageRevisions()
	 *
	 * @param           mixed 			$revision
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function updatePageRevision($revision){
		return $this->updatePageRevisions(array($revision));
	}
	/**
	 * @name            updatePageRevisions()
	 *
	 * @since           1.1.9
	 * @version         1.2.2
	 * @author          Can Berkol
	 *
	 * @use             $this->createException()
	 *
	 * @param           array 			$collection
	 *
	 * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
	 */
	public function updatePageRevisions($collection) {
		$timeStamp = time();
		/** Parameter must be an array */
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
		$countUpdates = 0;
		$updatedItems = array();
		foreach ($collection as $data) {
			if ($data instanceof BundleEntity\PageRevision) {
				$entity = $data;
				$this->em->persist($entity);
				$updatedItems[] = $entity;
				$countUpdates++;
			}
			else if (is_object($data)) {
				if (!property_exists($data, 'date_updated')) {
					$data->date_updated = new \DateTime('now', new \DateTimeZone($this->kernel->getContainer()->getParameter('app_timezone')));
				}
				if (property_exists($data, 'date_added')) {
					unset($data->date_added);
				}
				$response = $this->getPageRevision($data->page, $data->language, $data->revision_number);
				if ($response->error->exist) {
					return $this->createException('EntityDoesNotExist', 'Page revision cannot be found in database.', 'E:D:002');
				}
				$oldEntity = $response->result->set;

				foreach ($data as $column => $value) {
					$set = 'set' . $this->translateColumnName($column);
					switch ($column) {
						case 'page':
							$response = $this->getPage($value);
							if (!$response->error->exist) {
								$oldEntity->$set($response->result->set);
							}
							else {
								return $this->createException('EntityDoesNotExist', 'Page with id / code '.$value.' does not exist in database.', 'E:D:002');
							}
							unset($response, $pModel);
							break;
						case 'language':
							$lModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
							$response = $lModel->getLanguage($value);
							if (!$response->error->exist) {
								$oldEntity->$set($response->result->set);
							}
							else {
								return $this->createException('EntityDoesNotExist', 'Language with id / url_key / iso_code '.$data->id.' does not exist in database.', 'E:D:002');
							}
							unset($response, $lModel);
							break;
						default:
							$oldEntity->$set($value);
							break;
					}
					if ($oldEntity->isModified()) {
						$this->em->persist($oldEntity);
						$countUpdates++;
						$updatedItems[] = $oldEntity;
					}
				}
			}
		}
		if($countUpdates > 0){
			$this->em->flush();
			return new ModelResponse($updatedItems, $countUpdates, 0, null, false, 'S:D:004', 'Selected entries have been successfully updated within database.', $timeStamp, time());
		}
		return new ModelResponse(null, 0, 0, null, true, 'E:D:004', 'One or more entities cannot be updated within database.', $timeStamp, time());
	}
    /**
     * @name            updatePages()
     *
     * @since           1.0.0
     * @version         1.2.1
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           array 			$collection
     *
     * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
     */
    public function updatePages($collection){
		$timeStamp = time();
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
        $countUpdates = 0;
        $updatedItems = array();
        foreach ($collection as $data) {
            if ($data instanceof BundleEntity\Page) {
                $entity = $data;
                $this->em->persist($entity);
                $updatedItems[] = $entity;
                $countUpdates++;
            }
			else if (is_object($data)) {
				if(!property_exists($data, 'id') || !is_numeric($data->id)){
					return $this->createException('InvalidParameterException', 'Parameter must be an object with the "id" property and id property ​must have an integer value.', 'E:S:003');
				}
                if (!property_exists($data, 'site')) {
                    $data->site = 1;
                }
                $response = $this->getPage($data->id, 'id');
                if ($response->error->exist) {
					return $this->createException('EntityDoesNotExist', 'Page with id / code '.$data->id.' does not exist in database.', 'E:D:002');
                }
                unset($data->id);

                $oldEntity = $response->result->set;
                foreach ($data as $column => $value) {
                    $set = 'set' . $this->translateColumnName($column);
                    switch ($column) {
                        case 'local':
                            $localizations = array();
                            foreach ($value as $langCode => $translation) {
                                $localization = $oldEntity->getLocalization($langCode, true);
                                $newLocalization = false;
                                if (!$localization) {
                                    $newLocalization = true;
                                    $localization = new BundleEntity\PageLocalization();
                                    $mlsModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
                                    $response = $mlsModel->getLanguage($langCode, 'iso_code');
                                    $localization->setLanguage($response->result->set);
                                    $localization->setPage($oldEntity);
                                }
                                foreach ($translation as $transCol => $transVal) {
                                    $transSet = 'set' . $this->translateColumnName($transCol);
                                    $localization->$transSet($transVal);
                                }
                                if ($newLocalization) {
                                    $this->em->persist($localization);
                                }
                                $localizations[] = $localization;
                            }
                            $oldEntity->setLocalizations($localizations);
                            break;
                        case 'site':
                            $sModel = $this->kernel->getContainer()->get('sitemanagement.model');
                            $response = $sModel->getSite($value, 'id');
                            if (!$response->error->exist) {
                                $oldEntity->$set($response->result->set);
                            } else {
								return $this->createException('EntityDoesNotExist', 'Site with id / url_key '.$value.' does not exist in database.', 'E:D:002');
                            }
                            unset($response, $sModel);
                            break;
                        case 'id':
                            break;
                        default:
                            $oldEntity->$set($value);
                            break;
                    }
                    if ($oldEntity->isModified()) {
                        $this->em->persist($oldEntity);
                        $countUpdates++;
                        $updatedItems[] = $oldEntity;
                    }
                }
            }
        }
		if($countUpdates > 0){
			$this->em->flush();
			return new ModelResponse($updatedItems, $countUpdates, 0, null, false, 'S:D:004', 'Selected entries have been successfully updated within database.', $timeStamp, time());
		}
		return new ModelResponse(null, 0, 0, null, true, 'E:D:004', 'One or more entities cannot be updated within database.', $timeStamp, time());
	}
    /**
     * @name            updateTheme()
     *
     * @since           1.0.1
     * @version         1.2.1
     * @author          Can Berkol
     *
     * @use             $this->updateThemes()
     *
     * @param           mixed			$theme
     * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
     */
    public function updateTheme($theme){
        return $this->updateThemes(array($theme));
    }

    /**
     * @name            updateThemes()
     *
     * @since           1.0.1
     * @version         1.2.1
     *
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           array 			$collection
     *
     * @return          BiberLtd\Bundle\CoreBundle\Responses\ModelResponse
     */
    public function updateThemes($collection){
        $timeStamp = time();
		if (!is_array($collection)) {
			return $this->createException('InvalidParameterValueException', 'Invalid parameter value. Parameter must be an array collection', 'E:S:001');
		}
        $countUpdates = 0;
        $updatedItems = array();
        foreach ($collection as $data) {
            if ($data instanceof BundleEntity\Theme) {
                $entity = $data;
                $this->em->persist($entity);
                $updatedItems[] = $entity;
                $countUpdates++;
            }
			else if (is_object($data)) {
				if(!property_exists($data, 'id') || !is_numeric($data->id)){
					return $this->createException('InvalidParameterException', 'Parameter must be an object with the "id" property and id property ​must have an integer value.', 'E:S:003');
				}
                if (!property_exists($data, 'date_updated')) {
                    $data->date_updated = new \DateTime('now', new \DateTimeZone($this->kernel->getContainer()->getParameter('app_timezone')));
                }
                if (property_exists($data, 'date_added')) {
                    unset($data->date_added);
                }
                if (!property_exists($data, 'site')) {
                    $data->site = 1;
                }
                if (!property_exists($data, 'count_modules')) {
                    $data->count_modules = 0;
                }
                if (!property_exists($data, 'count_layouts')) {
                    $data->count_layouts = 0;
                }
                $response = $this->getTheme($data->id);
                if ($response->error->exist) {
					return $this->createException('EntityDoesNotExist', 'Member with id / username / email '.$data->id.' does not exist in database.', 'E:D:002');
                }
                $oldEntity = $response->result->set;
                foreach ($data as $column => $value) {
                    $set = 'set' . $this->translateColumnName($column);
                    switch ($column) {
                        case 'local':
                            $localizations = array();
                            foreach ($value as $langCode => $translation) {
                                $localization = $oldEntity->getLocalization($langCode, true);
                                $newLocalization = false;
                                if (!$localization) {
                                    $newLocalization = true;
                                    $localization = new BundleEntity\ThemeLocalization();
                                    $mlsModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
                                    $response = $mlsModel->getLanguage($langCode);
                                    $localization->setLanguage($response->result->set);
                                    $localization->setTheme($oldEntity);
                                }
                                foreach ($translation as $transCol => $transVal) {
                                    $transSet = 'set' . $this->translateColumnName($transCol);
                                    $localization->$transSet($transVal);
                                }
                                if ($newLocalization) {
                                    $this->em->persist($localization);
                                }
                                $localizations[] = $localization;
                            }
                            $oldEntity->setLocalizations($localizations);
                            break;
						case 'site':
							$sModel = $this->kernel->getContainer()->get('sitemanagement.model');
							$response = $sModel->getSite($value, 'id');
							if (!$response->error->exist) {
								$oldEntity->$set($response->result->set);
							} else {
								return $this->createException('EntityDoesNotExist', 'Site with id / url_key '.$value.' does not exist in database.', 'E:D:002');
							}
							unset($response, $sModel);
							break;
                        case 'id':
                            break;
                        default:
                            $oldEntity->$set($value);
                            break;
                    }
                    if ($oldEntity->isModified()) {
                        $this->em->persist($oldEntity);
                        $countUpdates++;
                        $updatedItems[] = $oldEntity;
                    }
                }
            }
        }
		if($countUpdates > 0){
			$this->em->flush();
			return new ModelResponse($updatedItems, $countUpdates, 0, null, false, 'S:D:004', 'Selected entries have been successfully updated within database.', $timeStamp, time());
		}
		return new ModelResponse(null, 0, 0, null, true, 'E:D:004', 'One or more entities cannot be updated within database.', $timeStamp, time());
	}
}
/**
 * Change Log
 * **************************************
 * v1.2.7                      26.06.2015
 * Said İmamoğlu
 * **************************************
 * BF :: listNavigationItems() updated.
 * **************************************
 * v1.2.6                      12.06.2015
 * Can Berkol
 * **************************************
 * BF :: insertNavigationLovalizations() rewritten.
 * BF :: insertNavigationItemLovalizations() rewritten.
 * BF :: insertPageLocalizations() rewritten.
 * FR :: markPagesAsDeleted() implemented.
 *
 * **************************************
 * v1.2.5                      04.06.2015
 * Can Berkol
 * **************************************
 * BF :: listModulesOFLayout & listModulesOfPageLayoutsGroupedBySection methods have been fixed.
 *
 * **************************************
 * v1.2.4                      26.05.2015
 * Can Berkol
 * **************************************
 * BF :: Deprecated use of $this->resetResponse() is removed.
 * BF :: listPagesMethod() was trying to associate page_localization to navigation. Association now points to page.
 * BF :: Use header added for ModelResponse.
 * BF :: Entity definition access kets fixed.
 *
 * **************************************
 * v1.2.3                      25.05.2015
 * Can Berkol
 * **************************************
 * BF :: db_connection is replaced with dbConnection
 *
 * **************************************
 * v1.2.2                      10.05.2015
 * Can Berkol
 * **************************************
 * BF :: Old style method calls fixed.
 *
 * **************************************
 * v1.2.1                     03.05.2015
 * Can Berkol
 * **************************************
 * CR :: Made compatible with CoreBundle v3.3.
 *
 * **************************************
 * v1.1.9                      29.04.2015
 * TW #
 * Can Berkol
 * **************************************
 * U listModulesOfPageLayoutsGroupedBySection()
 *
 * **************************************
 * v1.1.9                      24.04.2015
 * TW #3568871
 * Can Berkol
 * **************************************
 * A deleteNvigations()
 * A deletePageRevision()
 * A deletePageRevisions()
 * A getLastRevisionOfPage()
 * A getPageRevision()
 * A insertPageRevision()
 * A insertPageRevisions()
 * A listPageRevisions()
 * A listRevisionsOfPage()
 * A updatePageRevision()
 * A updatePageRevisions()
 * D deletNavigations()
 *
 * **************************************
 * v1.1.8                      Can Berkol
 * 29.03.2014
 * **************************************
 * U updateNavigationItemLocalization()
 * **************************************
 * v1.1.7                      Can Berkol
 * 07.03.2014
 * **************************************
 * A addFilesToPage()
 * A getMaxSortOrderOfPageFile()
 * A isFileAssociatedWithPage()
 *
 * **************************************
 * v1.1.6                      Can Berkol
 * 24.02.2014
 * **************************************
 * A insertNavigationItemLocalizations()
 * A insertNavigationLocalizations()
 * A insertPageLocalizations()
 * U insertNavigation()
 * U insertNavigationItem()
 * U insertNavigationıtems()
 * U insertNavigations()
 * U insertPage()
 * U insertPages()
 * U updateLayouts()
 * U updateModuleLayoutEntries()
 * U updateModules()
 * U updateNavigationItems()
 * U updateNavigations()
 * U updatePages()
 * U update Themes()
 *
 * **************************************
 * v1.1.5                      Can Berkol
 * 24.01.2014
 * **************************************
 * A doesPageExist()
 * A getModuleLayoutEntry()
 * A updateModuleLayoutEntry()
 * A updateModuleLayoutEntries()
 * U updatePages()
 *
 * **************************************
 * v1.1.4                      Can Berkol
 * 06.01.2014
 * **************************************
 * B listItemsOfNavigation() bug fixes.
 * U listNavigationItemsOfNavigation() Now accepts string, integer or object for $navigation parameter.
 *
 */