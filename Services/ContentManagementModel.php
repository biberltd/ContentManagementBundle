<?php

/**
 * ContentManagementModel Class
 *
 * This class acts as a database proxy model for ContentManagementBundle functionalities.
 *
 * @vendor      BiberLtd
 * @package        Core\Bundles\ContentManagementBundle
 * @subpackage    Services
 * @name        ContentManagementBundle
 *
 * @author        Can Berkol
 *
 * @copyright   Biber Ltd. (www.biberltd.com)
 *
 * @version     1.1.8
 * @date        26.03.2014
 *
 * =============================================================================================================
 * !! INSTRUCTIONS ON IMPORTANT ASPECTS OF MODEL METHODS !!!
 *
 * Each model function must return a $response ARRAY.
 * The array must contain the following keys and corresponding values.
 *
 * $response = array(
 *              'result'    =>   An array that contains the following keys:
 *                               'set'         Actual result set returned from ORM or null
 *                               'total_rows'  0 or number of total rows
 *                               'last_insert_id' The id of the item that is added last (if insert action)
 *              'error'     =>   true if there is an error; false if there is none.
 *              'code'      =>   null or a semantic and short English string that defines the error concanated
 *                               with dots, prefixed with err and the initials of the name of model class.
 *                               EXAMPLE: err.amm.action.not.found success messages have a prefix called scc..
 *
 *                               NOTE: DO NOT FORGET TO ADD AN ENTRY FOR ERROR CODE IN BUNDLE'S
 *                               RESOURCES/TRANSLATIONS FOLDER FOR EACH LANGUAGE.
 * =============================================================================================================
 * TODOs:
 * Do not forget to implement SITE, ORDER, AND PAGINATION RELATED FUNCTIONALITY
 *
 * @todo v1.0.1     add_modules_to_page_layout()
 * @todo v1.0.1     delete_files_of_page()
 * @todo v1.0.1     delete_modules_of_layout()
 * @todo v1.0.2     delete_navigation_items_of_parent()                 => delete_navigation_items()
 * @todo v1.0.1     increment_view_count_of_file_of_page()              => update_files_of_page()
 * @todo v1.0.1     insert_file_of_page()                               => insert_files_of_page()
 * @todo v1.0.1     insert_files_of_page()
 * @todo v1.0.2     list_files_of_pages_viewed_between()                => list_files_of_page()
 * @todo v1.0.2     list_files_of_page_viewed_less_than()               => list_files_of_page()
 * @todo v1.0.2     list_files_of_page_viewed_more_than()               => list_files_of_page()
 * @todo v1.0.2     list_navigation_items_pointing_a_page()             => list_navigation_items()
 * @todo v1.0.1     list_navigations_of_site()                          => list_navigations()
 * @todo v1.0.2     list_themes_added_after()                           => list_themes()
 * @todo v1.0.2     list_themes_added_before()                          => list_themes()
 * @todo v1.0.2     list_themes_added_between()                         => list_themes()
 * @todo v1.0.2     list_themes_added_on()                              => list_themes()
 * @todo v1.0.1     move_navigation_item_to_navigation()                => move_navigation_items_to_navigation()
 * @todo v1.0.1     move_navigation_items_to_navigation()               => update_navigation_items()
 * @todo v1.0.1     remove_files_from_page()
 * @todo v1.0.1     remove_modules_from_page_layout()
 * @todo v1.0.1     update_file_of_page()                               => update_files_of_pages()
 * @todo v1.0.1     update_files_of_page()
 *
 */

namespace BiberLtd\Core\Bundles\ContentManagementBundle\Services;

/** Extends CoreModel */
use BiberLtd\Core\CoreModel;
/** Entities to be used */
use BiberLtd\Core\Bundles\ContentManagementBundle\Entity as BundleEntity;
use BiberLtd\Core\Bundles\MultiLanguageSupportBundle\Entity as MLSEntity;
use BiberLtd\Core\Bundles\FileManagementBundle\Entity as FileBundleEntity;
/** Helper Models */
use BiberLtd\Core\Bundles\SiteManagementBundle\Services as SMMService;
use BiberLtd\Core\Bundles\MultiLanguageSupportBundle\Services as MLSService;
use BiberLtd\Core\Bundles\FileManagementBundle\Services as FileService;
/** Core Service */
use BiberLtd\Core\Services as CoreServices;
use BiberLtd\Core\Exceptions as CoreExceptions;

class ContentManagementModel extends CoreModel
{

    /**
     * @name            __construct ()
     *                  Constructor.
     *
     * @author          Can Berkol
     *
     * @since           1.0.0
     * @version         1.1.7
     *
     * @param           object $kernel
     * @param           string $db_connection Database connection key as set in app/config.yml
     * @param           string $orm ORM that is used.
     */
    public function __construct($kernel, $db_connection = 'default', $orm = 'doctrine')
    {
        parent::__construct($kernel, $db_connection, $orm);

        /**
         * Register entity names for easy reference.
         */
        $this->entity = array(
            'file' => array('name' => 'FileManagemetBundle:File', 'alias' => 'f'),
            'files_of_page' => array('name' => 'ContentManagementBundle:FilesOfPage', 'alias' => 'fop'),
            'layout' => array('name' => 'ContentManagementBundle:Layout', 'alias' => 'l'),
            'layout_localization' => array('name' => 'ContentManagementBundle:LayoutLocalization', 'alias' => 'lloc'),
            'module' => array('name' => 'ContentManagementBundle:Module', 'alias' => 'm'),
            'module_localization' => array('name' => 'ContentManagementBundle:ModuleLocalization', 'alias' => 'mloc'),
            'modules_of_layout' => array('name' => 'ContentManagementBundle:ModulesOfLayout', 'alias' => 'mol'),
            'modules_of_layout_localization' => array('name' => 'ContentManagementBundle:ModulesOfLayoutLocalization', 'alias' => 'molloc'),
            'navigation' => array('name' => 'ContentManagementBundle:Navigation', 'alias' => 'n'),
            'navigation_item' => array('name' => 'ContentManagementBundle:NavigationItem', 'alias' => 'ni'),
            'navigation_item_localization' => array('name' => 'ContentManagementBundle:NavigationItemLocalization', 'alias' => 'niloc'),
            'navigation_localization' => array('name' => 'ContentManagementBundle:NavigationLocalization', 'alias' => 'nloc'),
            'page' => array('name' => 'ContentManagementBundle:Page', 'alias' => 'p'),
            'page_localization' => array('name' => 'ContentManagementBundle:PageLocalization', 'alias' => 'ploc'),
            'theme' => array('name' => 'ContentManagementBundle:Theme', 'alias' => 't'),
            'theme_localization' => array('name' => 'ContentManagementBundle:ThemeLocalization', 'alias' => 'tloc'),
        );
    }

    /**
     * @name            __destruct ()
     *                  Destructor.
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
     * @name           addFilesToProduct ()
     *                 Associates files with a given product by creating new row in files_of_product_table.
     *
     * @since           1.1.7
     * @version         1.1.7
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     *
     * @param           array       $files      Collection consists one of the following: 'entity' or entity 'id'
     *                                          Contains an array with two keys: file, and sortorder
     * @param           mixed       $page       'entity' or 'entity' id.
     *
     * @return          array           $response
     */
    public function addFilesToPage($files, $page){
        $this->resetResponse();
        /**
         * Validate Parameters
         */
        $count = 0;
        /** remove invalid file entries */
        foreach ($files as $file) {
            if (!is_numeric($file['file']) && !$file['file'] instanceof FileBundleEntity\File) {
                unset($files[$count]);
            }
            $count++;
        }
        /** issue an error only if there is no valid file entries */
        if (count($files) < 1) {
            return $this->createException('InvalidParameter', '$files', 'err.invalid.parameter.files');
        }
        unset($count);
        if (!is_numeric($page) && !$page instanceof BundleEntity\Page) {
            return $this->createException('InvalidParameter', '$page', 'err.invalid.parameter.page');
        }
        /** If no entity is provided as product we need to check if it does exist */
        if (is_numeric($page)) {
            $response = $this->getPage($page, 'id');
            if ($response['error']) {
                return $this->createException('EntityDoesNotExist', 'Page', 'err.db.page.notexist');
            }
            $page = $response['result']['set'];
        }
        $fmmodel = new FileService\FileManagementModel($this->kernel, $this->db_connection, $this->orm);

        $fop_collection = array();
        $count = 0;
        /** Start persisting files */
        foreach ($files as $file) {
            /** If no entity s provided as file we need to check if it does exist */
            if (is_numeric($file['file'])) {
                $response = $fmmodel->getFile($file['file'], 'id');
                if ($response['error']) {
                    return $this->createException('EntityDoesNotExist', 'File', 'err.db.file.notexist');
                }
                $file['file'] = $response['result']['set'];
            }
            /** Check if association exists */
            if ($this->isFileAssociatedWithPage($file['file'], $page, true)) {
                new CoreExceptions\DuplicateAssociationException($this->kernel, 'File => Product');
                $this->response['code'] = 'err.db.entry.notexist';
                /** If file association already exist move silently to next file */
                break;
            }
            /** prepare object */
            $fop = new BundleEntity\FilesOfPage();
            $now = new \DateTime('now', new \DateTimezone($this->kernel->getContainer()->getParameter('app_timezone')));
            $fop->setFile($file['file'])->setPage($page)->setDateAdded($now);
            if (!is_null($file['sort_order'])) {
                $fop->setSortOrder($file['sort_order']);
            } else {
                $fop->setSortOrder($this->getMaxSortOrderOfPageFile($page, true) + 1);
            }
            $fop->setCountView(0);
            /** persist entry */
            $this->em->persist($fop);
            $fop_collection[] = $fop;
            $count++;
        }
        /** flush all into database */
        if ($count > 0) {
            $this->em->flush();
        } else {
            $this->response['code'] = 'err.db.insert.failed';
        }

        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $fop_collection,
                'total_rows' => $count,
                'last_insert_id' => -1,
            ),
            'error' => false,
            'code' => 'scc.db.insert.done',
        );
        unset($count, $aplCollection);
        return $this->response;
    }
    /**
     * @name            deleteLayout ()
     *                Deletes an existing layout from database.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->deleteLayouts()
     *
     * @param           mixed $layout Layout entity, id, code or url key.
     * @param           string $by
     *
     * @return          mixed           $response
     */
    public function deleteLayout($layout, $by = 'entity')
    {
        return $this->deleteLayouts(array($layout), $by);
    }

    /**
     * @name            deleteLayouts ()
     *                Deletes provided layouts from database.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->does_layout_exist()
     * @use             $this->createException
     *
     * @param           array $collection Collection of Navigation entities, ids, or codes or url keys
     * @param           string $by Accepts the following options: entity, id, code, url_key
     *
     * @return          array           $response
     */
    public function deleteLayouts($collection, $by = 'entity')
    {
        $this->resetResponse();
        $by_opts = array('entity', 'id', 'code', 'url_key');
        if (!in_array($by, $by_opts)) {
            return $this->createException('InvalidParameterValueException', 'err.invalid.parameter.by', implode(',', $by_opts));
        }
        /** Parameter must be an array */
        if (!is_array($collection)) {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.collection', 'Array');
        }
        $entries = array();
        /** Loop through items and collect values. */
        $delete_count = 0;
        foreach ($collection as $item) {
            $value = '';
            if (is_object($item)) {
                if (!$item instanceof BundleEntity\Layout) {
                    return $this->createException('InvalidParameterException', 'err.invalid.parameter.collection', 'Array');
                }
                $this->em->remove($item);
                $delete_count++;
            } else if (is_numeric($item) || is_string($item)) {
                $value = $item;
            } else {
                /** If array values are not numeric nor object */
                return $this->createException('InvalidParameterException', 'err.invalid.parameter.collection', 'Array');
            }
            if (!empty($value) && $value != '') {
                $entries[] = $value;
            }
        }
        /**
         * Control if there is any entity ids in collection.
         */
        if (count($entries) < 1) {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.collection', 'Array');
        }
        $join_needed = false;
        /**
         * Prepare query string.
         */
        switch ($by) {
            case 'entity':
                /** Flush to delete all persisting objects */
                $this->em->flush();
                /**
                 * Prepare & Return Response
                 */
                $this->response = array(
                    'rowCount' => $this->response['rowCount'],
                    'result' => array(
                        'set' => null,
                        'total_rows' => $delete_count,
                        'last_insert_id' => null,
                    ),
                    'error' => false,
                    'code' => 'scc.db.delete.done',
                );
                return $this->response;
            case 'id':
                $values = implode(',', $entries);
                break;
            case 'code':
                $values = implode('\',\'', $entries);
                $values = '\'' . $values . '\'';
                break;
            /** Requires JOIN */
            case 'url_key':
                $join_needed = true;
                $values = implode('\',\'', $entries);
                $values = '\'' . $values . '\'';
                break;
        }
        if ($join_needed) {
            $q_str = 'DELETE '
                . ' FROM ' . $this->entity['layout_localization']['name'] . ' ' . $this->entity['layout_localization']['alias']
                . ' JOIN ' . $this->entity['layout_localization']['name'] . ' ' . $this->entity['layout_localization']['alias']
                . ' WHERE ' . $this->entity['layout_localization']['alias'] . '.' . $by . ' IN(:values)';
        } else {
            $q_str = 'DELETE '
                . ' FROM ' . $this->entity['layout']['name'] . ' ' . $this->entity['layout']['alias']
                . ' WHERE ' . $this->entity['layout']['alias'] . '.' . $by . ' IN(:values)';
        }
        /**
         * Create query object.
         */
        $query = $this->em->createQuery($q_str);
        $query->setParameter('values', $entries);
        /**
         * Free memory.
         */
        unset($values);
        /**
         * 6. Run query
         */
        $query->getResult();
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $entries,
                'total_rows' => count($entries),
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.delete.done',
        );
        return $this->response;
    }

    /**
     * @name            deleteModule ()
     *                Deletes an existing module from database.
     *
     * @since            1.0.0
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->deleteModules()
     *
     * @param           mixed $module Module entity, id, code or url key.
     * @param           string $by
     *
     * @return          mixed           $response
     */
    public function deleteModule($module, $by = 'entity')
    {
        return $this->deleteModules(array($module), $by);
    }

    /**
     * @name            deleteModules ()
     *                Deletes provided modules from database.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->doesModuleExist()
     * @use             $this->createException()
     *
     * @param           array $collection Collection of Module entities, ids, or codes or url keys
     * @param           string $by Accepts the following options: entity, id, code, url_key
     *
     * @return          array           $response
     */
    public function deleteModules($collection, $by = 'entity')
    {
        $this->resetResponse();
        $by_opts = array('entity', 'id', 'code', 'url_key');
        if (!in_array($by, $by_opts)) {
            return $this->createException('InvalidParameterValueException', 'err.invalid.parameter.collection', implode(',', $by_opts));
        }
        /** Parameter must be an array */
        if (!is_array($collection)) {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.collection', 'Array');
        }
        $entries = array();
        /** Loop through items and collect values. */
        $delete_count = 0;
        foreach ($collection as $item) {
            $value = '';
            if (is_object($item)) {
                if (!$item instanceof BundleEntity\Module) {
                    return $this->createException('InvalidParameterException', 'err.invalid.parameter.collection', 'BundleEntity\Module');
                }
                $this->em->remove($item);
                $delete_count++;
            } else if (is_numeric($item) || is_string($item)) {
                $value = $item;
            } else {
                /** If array values are not numeric nor object */
                return $this->createException('InvalidParameterException', 'err.invalid.parameter.collection', 'integer, string, or Module entity');
            }
            if (!empty($value) && $value != '') {
                $entries[] = $value;
            }
        }
        /**
         * Control if there is any entity ids in collection.
         */
        if (count($entries) < 1) {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.collection', 'Array');
        }
        $join_needed = false;
        /**
         * Prepare query string.
         */
        switch ($by) {
            case 'entity':
                /** Flush to delete all persisting objects */
                $this->em->flush();
                /**
                 * Prepare & Return Response
                 */
                $this->response = array(
                    'rowCount' => $this->response['rowCount'],
                    'result' => array(
                        'set' => null,
                        'total_rows' => $delete_count,
                        'last_insert_id' => null,
                    ),
                    'error' => false,
                    'code' => 'scc.db.delete.done',
                );
                return $this->response;
            case 'id':
                $values = implode(',', $entries);
                break;
            case 'code':
                $values = implode('\',\'', $entries);
                $values = '\'' . $values . '\'';
                break;
            /** Requires JOIN */
            case 'url_key':
                $join_needed = true;
                $values = implode('\',\'', $entries);
                $values = '\'' . $values . '\'';
                break;
        }
        if ($join_needed) {
            $q_str = 'DELETE '
                . ' FROM ' . $this->entity['module_localization']['name'] . ' ' . $this->entity['module_localization']['alias']
                . ' JOIN ' . $this->entity['module_localization']['name'] . ' ' . $this->entity['module_localization']['alias']
                . ' WHERE ' . $this->entity['module_localization']['alias'] . '.' . $by . ' IN(:values)';
        } else {
            $q_str = 'DELETE '
                . ' FROM ' . $this->entity['module']['name'] . ' ' . $this->entity['module']['alias']
                . ' WHERE ' . $this->entity['module']['alias'] . '.' . $by . ' IN(:values)';
        }
        /**
         * Create query object.
         */
        $query = $this->em->createQuery($q_str);
        $query->setParameter('values', $entries);
        /**
         * Free memory.
         */
        unset($values);
        /**
         * 6. Run query
         */
        $query->getResult();
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $entries,
                'total_rows' => count($entries),
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.delete.done',
        );
        return $this->response;
    }

    /**
     * @name            deleteNavigation ()
     *                Deletes an existing navigation from database.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->deleteNavigations()
     *
     * @param           mixed $navigation Navigation entity, id, code or url key.
     * @param           string $by
     *
     * @return          mixed           $response
     */
    public function deleteNavigation($navigation, $by = 'entity')
    {
        return $this->deleteNavigations(array($navigation), $by);
    }

    /**
     * @name            deleteNavigations ()
     *                Deletes provided navigations from database.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->doesNavigationExist()
     * @use             $this->deleteNavigation()
     *
     * @param           array $collection Collection of Navigation entities, ids, or codes or url keys
     * @param           string $by Accepts the following options: entity, id, code, url_key
     *
     * @return          array           $response
     */
    public function deletNavigations($collection, $by = 'entity')
    {
        $this->resetResponse();
        $by_opts = array('entity', 'id', 'code', 'url_key');
        if (!in_array($by, $by_opts)) {
            return $this->createException('InvalidParameterValueException', 'err.invalid.parameter.collection', implode(',', $by_opts));
        }
        /** Parameter must be an array */
        if (!is_array($collection)) {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.collection', 'Array');
        }
        $entries = array();
        /** Loop through items and collect values. */
        $delete_count = 0;
        foreach ($collection as $item) {
            $value = '';
            if (is_object($item)) {
                if (!$item instanceof BundleEntity\Navigation) {
                    return $this->createException('InvalidParameterException', 'err.invalid.parameter.collection', 'Array');
                }
                $this->em->remove($item);
                $delete_count++;
            } else if (is_numeric($item) || is_string($item)) {
                $value = $item;
            } else {
                return $this->createException('InvalidParameterException', 'err.invalid.parameter.collection', 'string, numeric value or BundleEntity\Navigation entity');
            }
            if (!empty($value) && $value != '') {
                $entries[] = $value;
            }
        }
        /**
         * Control if there is any entity ids in collection.
         */
        if (count($entries) < 1) {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.collection', 'Array');
        }
        $join_needed = false;
        /**
         * Prepare query string.
         */
        switch ($by) {
            case 'entity':
                /** Flush to delete all persisting objects */
                $this->em->flush();
                /**
                 * Prepare & Return Response
                 */
                $this->response = array(
                    'rowCount' => $this->response['rowCount'],
                    'result' => array(
                        'set' => null,
                        'total_rows' => $delete_count,
                        'last_insert_id' => null,
                    ),
                    'error' => false,
                    'code' => 'scc.db.delete.done',
                );
                return $this->response;
            case 'id':
                $values = implode(',', $entries);
                break;
            case 'code':
                $values = implode('\',\'', $entries);
                $values = '\'' . $values . '\'';
                break;
            /** Requires JOIN */
            case 'url_key':
                $join_needed = true;
                $values = implode('\',\'', $entries);
                $values = '\'' . $values . '\'';
                break;
        }
        if ($join_needed) {
            $q_str = 'DELETE '
                . ' FROM ' . $this->entity['navigation_localization']['name'] . ' ' . $this->entity['navigation_localization']['alias']
                . ' JOIN ' . $this->entity['navigation_localization']['name'] . ' ' . $this->entity['navigation_localization']['alias']
                . ' WHERE ' . $this->entity['navigation_localization']['alias'] . '.' . $by . ' IN(:values)';
        } else {
            $q_str = 'DELETE '
                . ' FROM ' . $this->entity['navigation']['name'] . ' ' . $this->entity['navigation']['alias']
                . ' WHERE ' . $this->entity['navigation']['alias'] . '.' . $by . ' IN(:values)';
        }
        /**
         * Create query object.
         */
        $query = $this->em->createQuery($q_str);
        $query->setParameter('values', $entries);
        /**
         * Free memory.
         */
        unset($values);
        /**
         * 6. Run query
         */
        $query->getResult();
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $entries,
                'total_rows' => count($entries),
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.delete.done',
        );
        return $this->response;
    }

    /**
     * @name            deleteNavigationItem ()
     *                Deletes an existing navigation item from database.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->deletenavigationItems()
     *
     * @param           mixed $item Navigation entity, id, code or url key.
     * @param           string $by
     *
     * @return          mixed           $response
     */
    public function deleteNavigationItem($item, $by = 'entity')
    {
        return $this->deleteNavigationItems(array($item), $by);
    }

    /**
     * @name            deleteNavigationItems ()
     *                Deletes provided navigation items from database.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->doesNavigationItemExist()
     * @use             $this->createException()
     *
     * @param           array $collection Collection of Navigation entities, ids, or codes or url keys
     * @param           string $by Accepts the following options: entity, id, code, url_key
     *
     * @return          array           $response
     */
    public function deleteNavigationItems($collection, $by = 'entity')
    {
        $this->resetResponse();
        $by_opts = array('entity', 'id', 'code', 'url_key');
        if (!in_array($by, $by_opts)) {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.collection', implode(',', $by_opts));
        }
        /** Parameter must be an array */
        if (!is_array($collection)) {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.collection', 'Array');
        }
        $entries = array();
        /** Loop through items and collect values. */
        $delete_count = 0;
        foreach ($collection as $item) {
            $value = '';
            if (is_object($item)) {
                if (!$item instanceof BundleEntity\NavigationItem) {
                    return $this->createException('InvalidParameterException', 'err.invalid.parameter.collection', 'Array');
                }
                $this->em->remove($item);
                $delete_count++;
            } else if (is_numeric($item) || is_string($item)) {
                $value = $item;
            } else {
                /** If array values are not numeric nor object */
                return $this->createException('InvalidParameterException', 'err.invalid.parameter.collection', 'integer, string BundleEntity\\NavigationItem entity');
            }
            if (!empty($value) && $value != '') {
                $entries[] = $value;
            }
        }
        /**
         * Control if there is any entity ids in collection.
         */
        if (count($entries) < 1) {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.collection', 'Array');
        }
        $join_needed = false;
        /**
         * Prepare query string.
         */
        switch ($by) {
            case 'entity':
                /** Flush to delete all persisting objects */
                $this->em->flush();
                /**
                 * Prepare & Return Response
                 */
                $this->response = array(
                    'rowCount' => $this->response['rowCount'],
                    'result' => array(
                        'set' => null,
                        'total_rows' => $delete_count,
                        'last_insert_id' => null,
                    ),
                    'error' => false,
                    'code' => 'scc.db.delete.done',
                );
                return $this->response;
            case 'id':
                $values = implode(',', $entries);
                break;
            case 'code':
                $values = implode('\',\'', $entries);
                $values = '\'' . $values . '\'';
                break;
            /** Requires JOIN */
            case 'url_key':
                $join_needed = true;
                $values = implode('\',\'', $entries);
                $values = '\'' . $values . '\'';
                break;
        }
        if ($join_needed) {
            $q_str = 'DELETE '
                . ' FROM ' . $this->entity['navigation_item_localization']['name'] . ' ' . $this->entity['navigation_item_localization']['alias']
                . ' JOIN ' . $this->entity['navigation_item_localization']['name'] . ' ' . $this->entity['navigation_item_localization']['alias']
                . ' WHERE ' . $this->entity['navigation_item_localization']['alias'] . '.' . $by . ' IN(:values)';
        } else {
            $q_str = 'DELETE '
                . ' FROM ' . $this->entity['navigation_item']['name'] . ' ' . $this->entity['navigation_item']['alias']
                . ' WHERE ' . $this->entity['navigation_item']['alias'] . '.' . $by . ' IN(' . $values . ')';
        }
        /**
         * Create query object.
         */
        $query = $this->em->createQuery($q_str);
        /**
         * Free memory.
         */
        unset($values);
        /**
         * 6. Run query
         */
        $query->getResult();
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $entries,
                'total_rows' => count($entries),
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.delete.done',
        );
        return $this->response;
    }

    /**
     * @name            deletePage ()
     *                Deletes an existing page from database.
     *
     * @since            1.0.0
     * @version         1.01.0
     * @author          Can Berkol
     *
     * @use             $this->deletePages()
     *
     * @param           mixed $page Page entity, id, or code.
     * @param           string $by
     * @return          mixed           $response
     */
    public function deletePage($page, $by = 'entity')
    {
        return $this->deletePages(array($page), $by);
    }

    /**
     * @name            delete_pages ()
     *                Deletes provided pages from database.
     *
     * @since            1.0.0
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->doesPageExist()
     * @use             $this->createException()
     *
     * @param           array $collection Collection of Page entities, numeric page ids, or string page codes.
     * @param           string $by Accepts the following options: entity, id, code, url_key
     *
     * @return          array           $response
     */
    public function deletePages($collection, $by = 'entity')
    {
        $this->resetResponse();
        $by_opts = array('entity', 'id', 'code', 'url_key');
        if (!in_array($by, $by_opts)) {
            return $this->createException('InvalidParameterValueException', 'err.invalid.parameter.collection', implode(',', $by_opts));
        }
        /** Parameter must be an array */
        if (!is_array($collection)) {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.collection', 'Array');
        }
        $entries = array();
        /** Loop through items and collect values. */
        $delete_count = 0;
        foreach ($collection as $item) {
            $value = '';
            if (is_object($item)) {
                if (!$item instanceof BundleEntity\Page) {
                    return $this->createException('InvalidParameterException', 'err.invalid.parameter.collection', 'BundleEntity\\Page entity');
                }
                $this->em->remove($item);
                $delete_count++;
            } else if (is_numeric($item) || is_string($item)) {
                $value = $item;
            } else {
                /** If array values are not numeric nor object */
                return $this->createException('InvalidParameterException', 'err.invalid.parameter.collection', 'string, integer, BundleEntity\\Page');
            }
            if (!empty($value) && $value != '') {
                $entries[] = $value;
            }
        }
        /**
         * Control if there is any entity ids in collection.
         */
        if (count($entries) < 1) {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.collection', 'Array');
        }
        $join_needed = false;
        /**
         * Prepare query string.
         */
        switch ($by) {
            case 'entity':
                /** Flush to delete all persisting objects */
                $this->em->flush();
                /**
                 * Prepare & Return Response
                 */
                $this->response = array(
                    'rowCount' => $this->response['rowCount'],
                    'result' => array(
                        'set' => null,
                        'total_rows' => $delete_count,
                        'last_insert_id' => null,
                    ),
                    'error' => false,
                    'code' => 'scc.db.delete.done',
                );
                return $this->response;
            case 'id':
                $values = implode(',', $entries);
                break;
            case 'code':
                $values = implode('\',\'', $entries);
                $values = '\'' . $values . '\'';
                break;
            /** Requires JOIN */
            case 'url_key':
                $join_needed = true;
                $values = implode('\',\'', $entries);
                $values = '\'' . $values . '\'';
                break;
        }
        if ($join_needed) {
            $q_str = 'DELETE '
                . ' FROM ' . $this->entity['page']['name'] . ' ' . $this->entity['page']['alias']
                . ' JOIN ' . $this->entity['page_localization']['name'] . ' ' . $this->entity['page_localization']['alias']
                . ' WHERE ' . $this->entity['page_localization']['alias'] . '.' . $by . ' IN(:values)';
        } else {
            $q_str = 'DELETE '
                . ' FROM ' . $this->entity['page']['name'] . ' ' . $this->entity['page']['alias']
                . ' WHERE ' . $this->entity['page']['alias'] . '.' . $by . ' IN(' . $values . ')';
        }
        /**
         * Create query object.
         */
        $query = $this->em->createQuery($q_str);
        /**
         * Free memory.
         */
        unset($values);
        /**
         * 6. Run query
         */
        $query->getResult();
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $entries,
                'total_rows' => count($entries),
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.delete.done',
        );
        return $this->response;
    }

    /**
     * @name            deleteTheme ()
     *                Deletes an existing theme from database.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->deleteThemes()
     *
     * @param           mixed $theme Theme entity, id, or code.
     * @param           string $by
     * @return          mixed           $response
     */
    public function deleteTheme($theme, $by = 'entity')
    {
        return $this->deleteThemes(array($theme), $by);
    }

    /**
     * @name            deleteThemes ()
     *                Deletes provided themes from database.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->doesThemeExist()
     * @use             $this->createException()
     *
     * @param           array $collection Collection of Theme entities, ids, or codes or url keys
     * @param           string $by Accepts the following options: entity, id, code, url_key
     *
     * @return          array           $response
     */
    public function deleteThemes($collection, $by = 'entity')
    {
        $this->resetResponse();
        $by_opts = array('entity', 'id', 'code', 'url_key');
        if (!in_array($by, $by_opts)) {
            return $this->createException('InvalidParameterValueException', 'err.invalid.parameter.collection', implode(',', $by_opts));
        }
        /** Parameter must be an array */
        if (!is_array($collection)) {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.collection', 'Array');
        }
        $entries = array();
        /** Loop through items and collect values. */
        $delete_count = 0;
        foreach ($collection as $item) {
            $value = '';
            if (is_object($item)) {
                if (!$item instanceof BundleEntity\Theme) {
                    return $this->createException('InvalidParameterException', 'err.invalid.parameter.collection', 'BundleEntity\\Theme');
                }
                $this->em->remove($item);
                $delete_count++;
            } else if (is_numeric($item) || is_string($item)) {
                $value = $item;
            } else {
                /** If array values are not numeric nor object */
                return $this->createException('InvalidParameterException', 'err.invalid.parameter.collection', 'string, integer, BundleEntity\\Theme');
            }
            if (!empty($value) && $value != '') {
                $entries[] = $value;
            }
        }
        /**
         * Control if there is any entity ids in collection.
         */
        if (count($entries) < 1) {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.collection', 'Array');;
        }
        $join_needed = false;
        /**
         * Prepare query string.
         */
        switch ($by) {
            case 'entity':
                /** Flush to delete all persisting objects */
                $this->em->flush();
                /**
                 * Prepare & Return Response
                 */
                $this->response = array(
                    'rowCount' => $this->response['rowCount'],
                    'result' => array(
                        'set' => null,
                        'total_rows' => $delete_count,
                        'last_insert_id' => null,
                    ),
                    'error' => false,
                    'code' => 'scc.db.delete.done',
                );
                return $this->response;
            case 'id':
                $values = implode(',', $entries);
                break;
            case 'code':
                $values = implode('\',\'', $entries);
                $values = '\'' . $values . '\'';
                break;
            /** Requires JOIN */
            case 'url_key':
                $join_needed = true;
                $values = implode('\',\'', $entries);
                $values = '\'' . $values . '\'';
                break;
        }
        if ($join_needed) {
            $q_str = 'DELETE '
                . ' FROM ' . $this->entity['theme_localization']['name'] . ' ' . $this->entity['theme_localization']['alias']
                . ' JOIN ' . $this->entity['theme_localization']['name'] . ' ' . $this->entity['theme_localization']['alias']
                . ' WHERE ' . $this->entity['theme_localization']['alias'] . '.' . $by . ' IN(:values)';
        } else {
            $q_str = 'DELETE '
                . ' FROM ' . $this->entity['theme']['name'] . ' ' . $this->entity['theme']['alias']
                . ' WHERE ' . $this->entity['theme']['alias'] . '.' . $by . ' IN(:values)';
        }
        /**
         * Create query object.
         */
        $query = $this->em->createQuery($q_str);
        $query->setParameter('values', $entries);
        /**
         * Free memory.
         */
        unset($values);
        /**
         * 6. Run query
         */
        $query->getResult();
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $entries,
                'total_rows' => count($entries),
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.delete.done',
        );
        return $this->response;
    }

    /**
     * @name            doesLayoutExist ()
     *                Checks if layout exists in database.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->getLayout()
     *
     * @param           mixed $layout Entity, code, id, url key
     * @param           string $by all, entity, id, code, url_key
     * @param           bool $bypass If set to true does not return response but only the result.
     *
     * @return          mixed           $response
     */
    public function doesLayoutExist($layout, $by = 'id', $bypass = false)
    {
        $this->resetResponse();
        $exist = false;

        $response = $this->getLayout($layout, $by);

        if (!$response['error']) {
            if ($response['result']['total_rows'] > 0) {
                $exist = true;
            }
        }
        if ($bypass) {
            return $exist;
        }
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $exist,
                'total_rows' => 1,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );
        return $this->response;
    }

    /**
     * @name            doesModuleExist ()
     *                Checks if module exists in database.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->getModule()
     *
     * @param           mixed $module Eentity, code, id, url key
     * @param           string $by all, entity, id, username or email
     * @param           bool $bypass If set to true does not return response but only the result.
     *
     * @return          mixed           $response
     */
    public function doesModuleExist($module, $by = 'id', $bypass = false)
    {
        $this->resetResponse();
        $exist = false;

        $response = $this->getModule($module, $by);
        if (!$response['error']) {
            if ($response['result']['total_rows'] > 0) {
                $exist = true;
            }
        }
        if ($bypass) {
            return $exist;
        }
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $exist,
                'total_rows' => 1,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );
        return $this->response;
    }

    /**
     * @name            doesModuleLayoutEntryExist ()
     *                Checks if module exists in database.
     *
     * @since            1.1.5
     * @version         1.1.5
     * @author          Can Berkol
     *
     * @use             $this->getModule()
     *
     * @param           integer $id id number
     * @param           bool $bypass If set to true does not return response but only the result.
     *
     * @return          mixed           $response
     */
    public function doesModuleLayoutEntryExist($id, $bypass = false)
    {
        $this->resetResponse();
        $exist = false;

        $response = $this->getModuleLayoutEntry($id);
        if (!$response['error']) {
            if ($response['result']['total_rows'] > 0) {
                $exist = true;
            }
        }
        if ($bypass) {
            return $exist;
        }
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $exist,
                'total_rows' => 1,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );
        return $this->response;
    }

    /**
     * @name            doesNavigationExist ()
     *                Checks if navigation exists in database.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->getNavigation()
     *
     * @param           mixed $navigation Entity, code, id, url key
     * @param           string $by all, entity, id, code, url_key
     * @param           bool $bypass If set to true does not return response but only the result.
     *
     * @return          mixed           $response
     */
    public function doesNavigationExist($navigation, $by = 'id', $bypass = false)
    {
        $this->resetResponse();
        $exist = false;

        $response = $this->getNavigation($navigation, $by);

        if (!$response['error']) {
            if ($response['result']['total_rows'] > 0) {
                $exist = true;
            }
        }
        if ($bypass) {
            return $exist;
        }
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $exist,
                'total_rows' => 1,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );
        return $this->response;
    }

    /**
     * @name            doesNavigationItemExist ()
     *                Checks if navigation item exists in database.
     *
     * @since            1.0.0
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->getNavigationItem()
     *
     * @param           mixed $navigation_item Entity, code, id, url key
     * @param           string $by all, entity, id, code, url_key
     * @param           bool $bypass If set to true does not return response but only the result.
     *
     * @return          mixed           $response
     */
    public function doesNavigationItemExist($navigation_item, $by = 'id', $bypass = false)
    {
        $this->resetResponse();
        $exist = false;

        $response = $this->getNavigationItem($navigation_item, $by);
        if (!$response['error']) {
            if ($response['result']['total_rows'] > 0) {
                $exist = true;
            }
        }
        if ($bypass) {
            return $exist;
        }
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $exist,
                'total_rows' => 1,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );
        return $this->response;
    }

    /**
     * @name            doesPageExist ()
     *                Checks if page exists in database.
     *
     * @since            1.1.5
     * @version         1.1.5
     * @author          Can Berkol
     *
     * @use             $this->getModule()
     *
     * @param           mixed $page Entity, code, id, url key
     * @param           string $by all, entity, id, username or email
     * @param           bool $bypass If set to true does not return response but only the result.
     *
     * @return          mixed           $response
     */
    public function doesPageExist($page, $by = 'id', $bypass = false)
    {
        $this->resetResponse();
        $exist = false;

        $response = $this->getPage($page, $by);
        if (!$response['error']) {
            if ($response['result']['total_rows'] > 0) {
                $exist = true;
            }
        }
        if ($bypass) {
            return $exist;
        }
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $exist,
                'total_rows' => 1,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );
        return $this->response;
    }

    /**
     * @name            doesThemeExist ()
     *                Checks if theme exists in database.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->getTheme()
     *
     * @param           mixed $theme Theme entity, id or name.
     * @param           string $by entity, id, code, url_key
     * @param           bool $bypass If set to true does not return response but only the result.
     *
     * @return          mixed           $response
     */
    public function doesThemeExist($theme, $by = 'id', $bypass = false)
    {
        $this->resetResponse();
        $exist = false;

        $response = $this->getTheme($theme, $by);
        if (!$response['error']) {
            if ($response['result']['total_rows'] > 0) {
                $exist = true;
            }
        }
        if ($bypass) {
            return $exist;
        }
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $exist,
                'total_rows' => 1,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );
        return $this->response;
    }

    /**
     * @name            getLayout ()
     *                Returns details of a layout.
     *
     * @since            1.0.1
     * @version         1.0.1
     * @author          Can Berkol
     *
     * @use             $this->listLayouts()
     * @use             $this->createException()
     *
     * @param           mixed $layout Navigation entity, id, url_key or code
     * @param           string $by entity, id, url key or username
     *
     * @return          mixed           $response
     */
    public function getLayout($layout, $by = 'id')
    {
        $this->resetResponse();
        if ($by != 'id' && $by != 'entity' && $by != 'url_key' && $by != 'code') {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.by', 'id, url_key, code');
        }
        if (!is_object($layout) && !is_numeric($layout) && !is_string($layout)) {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.layout', 'BundleEntity\\Layout entity or number or string');
        }
        if (is_object($layout)) {
            if (!$layout instanceof BundleEntity\Layout) {
                return $this->createException('InvalidParameterException', 'err.invalid.parameter.layout', 'BundleEntity\\Layout');
            }
            $layout = $layout->getId();
            $by = 'id';
        }
        $filter = array(
            $by => $layout
        );
        $response = $this->listLayouts($filter, null, array('start' => 0, 'count' => 1));
        if ($response['error']) {
            return $response;
        }
        $collection = $response['result']['set'];
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $collection[0],
                'total_rows' => 1,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entity.exist',
        );
        return $this->response;
    }

    /**
     * @name            getLayoutLocalization ()
     *                Returns the entity's lcoalization n a specific language.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->getLayout()
     * @use             $this->createException(
     *
     * @param           BundleEntity\Layout $layout Layout entity
     * @param           MLSEntity\Language $language Language entity
     *
     * @return          mixed           $response
     */
    public function getLayoutLocalization($layout, $language)
    {
        $this->resetResponse();
        if (!$layout instanceof BundleEntity\Layout) {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.layout', 'BundleEntity\\Layout');
        }
        if (!$language instanceof MLSEntity\Language) {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.LANGUAGE', 'MLSEntity\\Language');
        }

        $q_str = 'SELECT ' . $this->entity['layout_localization']['alias']
            . ' FROM ' . $this->entity['layout_localization']['name'] . ' ' . $this->entity['layout_localization']['alias']
            . ' WHERE ' . $this->entity['layout_localization']['alias'] . '.layout = ' . $layout->getId()
            . ' AND ' . $this->entity['layout_localization']['alias'] . '.language = ' . $language->getId()
            . ' LIMIT 1 ';
        $query = $this->createQuery($q_str);
        $result = $query->getResult();
        if (!$result) {
            /**
             * Prepare & Return Response
             */
            $this->response = array(
                'rowCount' => $this->response['rowCount'],
                'result' => array(
                    'set' => null,
                    'total_rows' => 0,
                    'last_insert_id' => null,
                ),
                'error' => true,
                'code' => 'err.db.entity.notexist',
            );
        } else {
            /**
             * Prepare & Return Response
             */
            $this->response = array(
                'rowCount' => $this->response['rowCount'],
                'result' => array(
                    'set' => $result,
                    'total_rows' => 1,
                    'last_insert_id' => null,
                ),
                'error' => false,
                'code' => 'scc.db.entity.exist',
            );
        }
        return $this->response;
    }
    /**
     * @name            getMaxSortOrderOfPageFile ()
     *                  Returns the largest sort order value for a given page from files_of_product table.
     *
     * @since           1.1.7
     * @version         1.1.7
     * @author          Can Berkol
     *
     *
     * @param           mixed   $product    entity, id, sku
     * @param           bool    $bypass     if set to true return bool instead of response
     *
     * @return          mixed           bool | $response
     */
    public function getMaxSortOrderOfPageFile($page, $bypass = false)
    {
        $this->resetResponse();;
        if (!is_object($page) && !is_numeric($page)) {
            return $this->createException('InvalidParameter', 'Page', 'err.invalid.parameter.page');
        }
        if (is_object($page)) {
            if (!$page instanceof BundleEntity\Page) {
                return $this->createException('InvalidParameter', 'Page', 'err.invalid.parameter.page');
            }
        } else {
            /** if numeric value given check if category exists */
            switch ($page) {
                case is_numeric($page):
                    $response = $this->getPage($page, 'id');
                    break;
            }
            if ($response['error']) {
                return $this->createException('InvalidParameter', 'Page', 'err.invalid.parameter.page');
            }
            $product = $response['result']['set'];
        }
        $q_str = 'SELECT MAX(' . $this->entity['files_of_page']['alias'] . '.sort_order) FROM ' . $this->entity['files_of_page']['name'].' '.$this->entity['files_of_page']['alias']
            . ' WHERE ' . $this->entity['files_of_page']['alias'] . '.page = ' . $page->getId();

        $query = $this->em->createQuery($q_str);
        $result = $query->getSingleScalarResult();

        if ($bypass) {
            return $result;
        }
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $result,
                'total_rows' => 1,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );
        return $this->response;
    }
    /**
     * @name            getModule ()
     *                Returns details of a module.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           mixed $module Module id, url_key or code
     * @param           string $by entity, id, code, or url key.
     *
     * @return          mixed           $response
     */
    public function getModule($module, $by = 'id')
    {
        $this->resetResponse();
        if ($by != 'id' && $by != 'code' && $by != 'entity' && $by != 'url_key') {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.by', 'id, code, entity, url_key');
        }
        if (!is_object($module) && !is_numeric($module) && !is_string($module)) {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.module', 'BundleEntity\\Module, integer, string');
        }
        if (is_object($module)) {
            if (!$module instanceof BundleEntity\Module) {
                return $this->createException('InvalidParameterException', 'err.invalid.parameter.module', 'BundleEntity\\Module');
                return $this->response;
            }
            $module = $module->getId();
            $by = 'id';
        }
        $filter = array(
            $by => $module
        );
        $response = $this->listModules($filter, null, array('start' => 0, 'count' => 1));
        if ($response['error']) {
            return $response;
        }
        $collection = $response['result']['set'];
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $collection[0],
                'total_rows' => 1,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );
        return $this->response;
    }

    /**
     * @name            getModuleLayoutEntry ()
     *                Returns details of a module-layout association entry. Contains localized values.
     *
     * @since            1.1.5
     * @version         1.1.5
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           integer $id modules_of_layout entry id.
     *
     * @return          mixed           $response
     */
    public function getModuleLayoutEntry($id)
    {
        $this->resetResponse();
        if (!is_numeric($id)) {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.id', 'integer');
        }

        $entity = $this->em->getRepository($this->entity['modules_of_layout']['name'])->findOneBy(array('id' => $id));

        if (!$entity) {
            return $this->createException('EntityDoesNotExistException', 'err.invalid.entity.notfound', $id);
        }
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => 1,
            'result' => array(
                'set' => $entity,
                'total_rows' => 1,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );
        return $this->response;
    }

    /**
     * @name            getModuleLocalization ()
     *                Returns the entity's localization n a specific language.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->createException(
     *
     * @param           BundleEntity\Layout $module Module entity
     * @param           MLSEntity\Language $language Language entity
     *
     * @return          mixed           $response
     */
    public function getModuleLocalization($module, $language)
    {
        $this->resetResponse();
        if (!$module instanceof BundleEntity\Module) {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.module', 'BundleEntity\\Module');
        }
        if (!$language instanceof MLSEntity\Language) {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.language', 'MLSEntity\\Language');
        }

        $q_str = 'SELECT ' . $this->entity['module_localization']['alias']
            . ' FROM ' . $this->entity['module_localization']['name'] . ' ' . $this->entity['module_localization']['alias']
            . ' WHERE ' . $this->entity['module_localization']['alias'] . '.layout = ' . $module->getId()
            . ' AND ' . $this->entity['module_localization']['alias'] . '.language = ' . $language->getId()
            . ' LIMIT 1 ';
        $query = $this->createQuery($q_str);
        $result = $query->getResult();
        if (!$result) {
            /**
             * Prepare & Return Response
             */
            $this->response = array(
                'rowCount' => $this->response['rowCount'],
                'result' => array(
                    'set' => null,
                    'total_rows' => 0,
                    'last_insert_id' => null,
                ),
                'error' => true,
                'code' => 'err.db.entity.notexist',
            );
        } else {
            /**
             * Prepare & Return Response
             */
            $this->response = array(
                'rowCount' => $this->response['rowCount'],
                'result' => array(
                    'set' => $result,
                    'total_rows' => 1,
                    'last_insert_id' => null,
                ),
                'error' => false,
                'code' => 'scc.db.entity.exist',
            );
        }
        return $this->response;
    }

    /**
     * @name            getNavigation ()
     *                Returns details of a navigation.
     *
     * @since            1.0.0
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->listNavigations()
     * @use             $this->createException()
     *
     * @param           mixed $navigation Navigation entity, id, url_key or code
     * @param           string $by entity, id, url key or username
     *
     * @return          mixed           $response
     */
    public function getNavigation($navigation, $by = 'id')
    {
        $this->resetResponse();
        if ($by != 'id' && $by != 'code' && $by != 'entity' && $by != 'url_key') {
            return $this->createException('InvalidParameterValueException', 'err.invalid.parameter.by', 'id, code, entity, url_key');
        }
        if (!is_object($navigation) && !is_numeric($navigation) && !is_string($navigation)) {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.navigation', 'BundleEntity\\Navigation, id, code, url_key');
        }
        if (is_object($navigation)) {
            if (!$navigation instanceof BundleEntity\Navigation) {
                return $this->createException('InvalidParameterException', 'err.invalid.parameter.navigation', 'BundleEntity\\Navigation');
            }
            $navigation = $navigation->getId();
            $by = 'id';
        }
        $filter = array();
        $filter[] = array(
            'glue' => 'and',
            'condition' => array(
                array(
                    'glue' => 'and',
                    'condition' => array('column' =>$this->entity['navigation']['alias']. '.'.$by, 'comparison' => '=', 'value' => $navigation),
                )
            )
        );
        $response = $this->listNavigations($filter, null, array('start' => 0, 'count' => 1));
        if ($response['error']) {
            return $response;
        }
        $collection = $response['result']['set'];
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $collection[0],
                'total_rows' => 1,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );
        return $this->response;
    }

    /**
     * @name            getNavigationLocalization ()
     *                Returns the entity's localization in a specific language.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           BundleEntity\Navigation $navigation Navigation entity
     * @param           MLSEntity\Language $language Language entity
     *
     * @return          mixed           $response
     */
    public function getNavigationLocalization($navigation, $language)
    {
        $this->resetResponse();
        if (!$navigation instanceof BundleEntity\Navigation) {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.navigation', 'BundleEntity\\Navigation');
        }
        if (!$language instanceof MLSEntity\Language) {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.language', 'MLSEntity\\Language');
        }

        $q_str = 'SELECT ' . $this->entity['navigation_localization']['alias']
            . ' FROM ' . $this->entity['navigation_localization']['name'] . ' ' . $this->entity['navigation_localization']['alias']
            . ' WHERE ' . $this->entity['navigation_localization']['alias'] . '.layout = ' . $navigation->getId()
            . ' AND ' . $this->entity['navigation_localization']['alias'] . '.language = ' . $language->getId()
            . ' LIMIT 1 ';
        $query = $this->createQuery($q_str);
        $result = $query->getResult();
        if (!$result) {
            /**
             * Prepare & Return Response
             */
            $this->response = array(
                'rowCount' => $this->response['rowCount'],
                'result' => array(
                    'set' => null,
                    'total_rows' => 0,
                    'last_insert_id' => null,
                ),
                'error' => true,
                'code' => 'err.db.entity.notexist',
            );
        } else {
            /**
             * Prepare & Return Response
             */
            $this->response = array(
                'rowCount' => $this->response['rowCount'],
                'result' => array(
                    'set' => $result,
                    'total_rows' => 1,
                    'last_insert_id' => null,
                ),
                'error' => false,
                'code' => 'scc.db.entity.exist',
            );
        }
        return $this->response;
    }

    /**
     * @name            getNavigationItem ()
     *                Returns details of a navigation.
     *
     * @since            1.0.0
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->listNavigationItems()
     * @use             $this->createException()
     *
     * @param           mixed $navigation_item Navigation entity, id, url_key or code
     * @param           string $by entity, id, url key or username
     *
     * @return          mixed           $response
     */
    public function getNavigationItem($navigation_item, $by = 'id')
    {
        $this->resetResponse();
        if ($by != 'id' && $by != 'entity' && $by != 'url_key') {
            return $this->createException('InvalidParameteralueException', 'err.invalid.parameter.by', 'id, entity, url_key');
        }
        if (!is_object($navigation_item) && !is_numeric($navigation_item) && !is_string($navigation_item)) {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.naviagtion_item', 'BundleEntitty\NavigationItem, integer, string');
        }
        if (is_object($navigation_item)) {
            if (!$navigation_item instanceof BundleEntity\NavigationItem) {
                new CoreExceptions\InvalidEntityException($this->kernel, 'NavigationItem');
                $this->response['code'] = 'invalid.parameter.navigation_item';
                return $this->response;
            }
            $navigation_item = $navigation_item->getId();
            $by = 'id';
        }

        switch($by){
            case 'url_key':
                $column = $this->entity['navigation_item_localization']['alias'].'.'.$by;
                break;
            default:
                $column = $this->entity['navigation_item']['alias'].'.'.$by;
                break;
        }

        $filter = array();
        $filter[] = array(
            'glue' => 'and',
            'condition' => array(
                array(
                    'glue' => 'and',
                    'condition' => array('column' =>$column, 'comparison' => '=', 'value' => $navigation_item),
                )
            )
        );
        $response = $this->listNavigationItems($filter);
        if ($response['error']) {
            return $response;
        }
        $collection = $response['result']['set'];
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => isset($this->response['rowCount']) ? $this->response['rowCount'] : 0,
            'result' => array(
                'set' => $collection[0],
                'total_rows' => 1,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );
        return $this->response;
    }

    /**
     * @name            getNavigationItemLocalization ()
     *                Returns the entity's localization in a specific language.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           BundleEntity\NavigationItem $item NavigationItem entity
     * @param           MLSEntity\Language $language Language entity
     *
     * @return          mixed           $response
     */
    public function getNavigationItemLocalization($item, $language)
    {
        $this->resetResponse();
        if (!$item instanceof BundleEntity\NavigationItem) {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.navigation_item', 'BundleEntity\\NavigationItem');
        }
        if (!$language instanceof MLSEntity\Language) {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.language', 'MLSEntity\\Language');
        }

        $q_str = 'SELECT ' . $this->entity['navigation_item_localization']['alias']
            . ' FROM ' . $this->entity['navigation_item_localization']['name'] . ' ' . $this->entity['navigation_item_localization']['alias']
            . ' WHERE ' . $this->entity['navigation_item_localization']['alias'] . '.layout = ' . $item->getId()
            . ' AND ' . $this->entity['navigation_item_localization']['alias'] . '.language = ' . $language->getId()
            . ' LIMIT 1 ';
        $query = $this->createQuery($q_str);
        $result = $query->getResult();
        if (!$result) {
            /**
             * Prepare & Return Response
             */
            $this->response = array(
                'rowCount' => $this->response['rowCount'],
                'result' => array(
                    'set' => null,
                    'total_rows' => 0,
                    'last_insert_id' => null,
                ),
                'error' => true,
                'code' => 'err.db.entity.notexist',
            );
        } else {
            /**
             * Prepare & Return Response
             */
            $this->response = array(
                'rowCount' => $this->response['rowCount'],
                'result' => array(
                    'set' => $result,
                    'total_rows' => 1,
                    'last_insert_id' => null,
                ),
                'error' => false,
                'code' => 'scc.db.entity.exist',
            );
        }
        return $this->response;
    }

    /**
     * @name            getPage ()
     *                Returns details of a page.
     *
     * @since            1.0.0
     * @version         1.1.2
     * @author          Can Berkol
     *
     * @use             $this->listPage()
     * @use             $this->createException()
     *
     * @param           mixed $page Page id or code
     * @param           string $by entity, id, url key or username
     *
     * @return          mixed           $response
     */
    public function getPage($page, $by = 'id')
    {
        $this->resetResponse();

        $by_opts = array('id', 'code', 'url_key');
        if (!in_array($by, $by_opts)) {
            return $this->createException('InvalidParameterValueException', implode(',', $by_opts), 'err.invalid.parameter.by');
        }
        if (!is_object($page) && !is_numeric($page) && !is_string($page)) {
            return $this->createException('InvalidParameterException', 'Page', 'err.invalid.parameter.page');
        }
        if (is_object($page)) {
            if (!$page instanceof BundleEntity\Page) {
                return $this->createException('InvalidParameterException', 'Page', 'err.invalid.parameter.page');
            }
            /**
             * Prepare & Return Response
             */
            $this->response = array(
                'rowCount' => $this->response['rowCount'],
                'result' => array(
                    'set' => $page,
                    'total_rows' => 1,
                    'last_insert_id' => null,
                ),
                'error' => false,
                'code' => 'scc.db.entry.exist',
            );
            return $this->response;
        }

        switch ($by) {
            case 'id':
            case 'code':
                $column = $this->entity['page']['alias'] . '.' . $by;
                break;
            case 'url_key':
                $column = $this->entity['page_localization']['alias'] . '.' . $by;
        }
        $filter[] = array(
            'glue' => 'and',
            'condition' => array(
                array(
                    'glue' => 'and',
                    'condition' => array('column' => $column, 'comparison' => '=', 'value' => $page),
                )
            )
        );
        $response = $this->listPages($filter, null, array('start' => 0, 'count' => 1));
        if ($response['error']) {
            return $response;
        }
        $collection = $response['result']['set'];
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $collection[0],
                'total_rows' => 1,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );

        return $this->response;
    }

    /**
     * @name            getPageLocalization ()
     *                  Returns the entity's localization in a specific language.
     *
     * @since           1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           object          $page
     * @param           object          $language
     *
     * @return          mixed           $response
     */
    public function getPageLocalization($page, $language)
    {
        $this->resetResponse();
        if (!$page instanceof BundleEntity\Page) {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.page', 'BundleEntity\\Page');
        }
        if (!$language instanceof MLSEntity\Language) {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.language', 'MLSEntity\\Language');
        }

        $q_str = 'SELECT ' . $this->entity['page_localization']['alias']
            . ' FROM ' . $this->entity['page_localization']['name'] . ' ' . $this->entity['page_localization']['alias']
            . ' WHERE ' . $this->entity['page_localization']['alias'] . '.layout = ' . $page->getId()
            . ' AND ' . $this->entity['page_localization']['alias'] . '.language = ' . $language->getId()
            . ' LIMIT 1 ';
        $query = $this->createQuery($q_str);
        $result = $query->getResult();
        if (!$result) {
            /**
             * Prepare & Return Response
             */
            $this->response = array(
                'rowCount' => $this->response['rowCount'],
                'result' => array(
                    'set' => null,
                    'total_rows' => 0,
                    'last_insert_id' => null,
                ),
                'error' => true,
                'code' => 'err.db.entity.notexist',
            );
        } else {
            /**
             * Prepare & Return Response
             */
            $this->response = array(
                'rowCount' => $this->response['rowCount'],
                'result' => array(
                    'set' => $result,
                    'total_rows' => 1,
                    'last_insert_id' => null,
                ),
                'error' => false,
                'code' => 'scc.db.entity.exist',
            );
        }
        return $this->response;
    }

    /**
     * @name            insertNavigation ()
     *                Inserts one navigation definition into database.
     *
     * @since            1.0.1
     * @version         1.1.6
     * @author          Can Berkol
     *
     * @use             $this->insertNavigation()
     *
     * @param           mixed $collection Navigation Entity or a collection of post input that stores entity details.
     *
     * @return          array           $response
     */
    public function insertNavigation($collection)
    {
        return $this->insertNavigations(array($collection));
    }

    /**
     * @name            insertNavigationLocalization ()
     *                Inserts one or more navigation localizations into database.
     *
     * @since            1.1.6
     * @version         1.1.6
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           array $collection Collection of entities or post data.
     *
     * @return          array           $response
     */
    public function insertNavigationLocalizations($collection)
    {
        $this->resetResponse();
        /** Parameter must be an array */
        if (!is_array($collection)) {
            return $this->createException('InvalidParameter', 'Array', 'err.invalid.parameter.collection');
        }
        $countInserts = 0;
        $insertedItems = array();
        foreach ($collection as $item) {
            if ($item instanceof BundleEntity\NavigationLocalization) {
                $entity = $item;
                $this->em->persist($entity);
                $insertedItems[] = $entity;
                $countInserts++;
            } else {
                foreach ($item['localizations'] as $language => $data) {
                    $entity = new BundleEntity\NavigationLocalization;
                    $entity->setNavigation($item['entity']);
                    $mlsModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
                    $response = $mlsModel->getLanguage($language, 'iso_code');
                    if (!$response['error']) {
                        $entity->setLanguage($response['result']['set']);
                    } else {
                        break 1;
                    }
                    foreach ($data as $column => $value) {
                        $set = 'set' . $this->translateColumnName($column);
                        $entity->$set($value);
                    }
                    $this->em->persist($entity);
                }
                $insertedItems[] = $entity;
                $countInserts++;
            }
        }
        if ($countInserts > 0) {
            $this->em->flush();
        }
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $insertedItems,
                'total_rows' => $countInserts,
                'last_insert_id' => -1,
            ),
            'error' => false,
            'code' => 'scc.db.insert.done',
        );
        return $this->response;
    }

    /**
     * @name            insertNavigations ()
     *                Inserts one or more navigation definitions into database.
     *
     * @since            1.0.1
     * @version         1.1.6
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           array $collection Collection of Navigation entities or array of member detail array.
     *
     * @return          array           $response
     */
    public function insertNavigations($collection)
    {
        $this->resetResponse();
        /** Parameter must be an array */
        if (!is_array($collection)) {
            return $this->createException('InvalidParameter', 'Array', 'err.invalid.parameter.collection');
        }
        $countInserts = 0;
        $countLocalizations = 0;
        $insertedItems = array();
        foreach ($collection as $data) {
            if ($data instanceof BundleEntity\Navigation) {
                $entity = $data;
                $this->em->persist($entity);
                $insertedItems[] = $entity;
                $countInserts++;
            } else if (is_object($data)) {
                $localizations = array();
                $entity = new BundleEntity\Navigation;
                if (!property_exists($data, 'date_added')) {
                    $data->date_added = new \DateTime('now', new \DateTimeZone($this->kernel->getContainer()->getParameter('app_timezone')));
                }
                if (!property_exists($data, 'site')) {
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
                            $response = $sModel->getSite($value, 'id');
                            if (!$response['error']) {
                                $entity->$set($response['result']['set']);
                            } else {
                                new CoreExceptions\SiteDoesNotExistException($this->kernel, $value);
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
            } else {
                new CoreExceptions\InvalidDataException($this->kernel);
            }
        }
        if ($countInserts > 0) {
            $this->em->flush();
        }
        /** Now handle localizations */
        if ($countInserts > 0 && $countLocalizations > 0) {
            $this->insertNavigationLocalizations($localizations);
        }
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $insertedItems,
                'total_rows' => $countInserts,
                'last_insert_id' => $entity->getId(),
            ),
            'error' => false,
            'code' => 'scc.db.insert.done',
        );
        return $this->response;
    }

    /**
     * @name            insertNavigationItem ()
     *                Inserts one navigation into into database.
     *
     * @since            1.0.1
     * @version         1.1.6
     * @author          Can Berkol
     *
     * @use             $this->insertNavigationItems()
     *
     * @param           mixed $navigation_item NavigationItem Entity or a collection of post input that stores entity details.
     *
     * @return          array           $response
     */
    public function insertNavigationItem($navigation_item)
    {
        return $this->insertNavigationItems(array($navigation_item));
    }

    /**
     * @name            insertNavigationItemLocalizations ()
     *                Inserts one or more navigation localizations into database.
     *
     * @since            1.1.6
     * @version         1.1.6
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           array $collection Collection of entities or post data.
     *
     * @return          array           $response
     */
    public function insertNavigationItemLocalizations($collection)
    {

        $this->resetResponse();
        /** Parameter must be an array */
        if (!is_array($collection)) {
            return $this->createException('InvalidParameter', 'Array', 'err.invalid.parameter.collection');
        }
        $countInserts = 0;
        $insertedItems = array();
        foreach ($collection as $item) {
            if ($item instanceof BundleEntity\NavigationItemLocalization) {
                $entity = $item;
                $this->em->persist($entity);
                $insertedItems[] = $entity;
                $countInserts++;
            } else {
                foreach ($item['localizations'] as $language => $data) {
                    $entity = new BundleEntity\NavigationItemLocalization;
                    $entity->setNavigationItem($item['entity']);
                    $mlsModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
                    $response = $mlsModel->getLanguage($language, 'iso_code');

                    if (!$response['error']) {
                        $aLang = $response['result']['set'];
                        $entity->setLanguage($aLang);
                        unset($response);
                    } else {
                        break 1;
                    }
                    foreach ($data as $column => $value) {
                        $set = 'set' . $this->translateColumnName($column);
                        $entity->$set($value);
                    }
                    $this->em->persist($entity);
                }
                $insertedItems[] = $entity;
                $countInserts++;
            }
        }
        if ($countInserts > 0) {
            $this->em->flush();
        }
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $insertedItems,
                'total_rows' => $countInserts,
                'last_insert_id' => -1,
            ),
            'error' => false,
            'code' => 'scc.db.insert.done',
        );
        return $this->response;
    }

    /**
     * @name            insertNavigationItems ()
     *                Inserts one or more navigations into database.
     *
     * @since            1.0.1
     * @version         1.1.6
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           array $collection Collection of Navigation entities or array of member detail array.
     *
     * @return          array           $response
     */
    public function insertNavigationItems($collection)
    {
        $this->resetResponse();
        /** Parameter must be an array */
        if (!is_array($collection)) {
            return $this->createException('InvalidParameter', 'Array', 'err.invalid.parameter.collection');
        }
        $countInserts = 0;
        $countLocalizations = 0;
        $insertedItems = array();
        foreach ($collection as $data) {
            if ($data instanceof BundleEntity\NavigationItem) {
                $entity = $data;
                $this->em->persist($entity);
                $insertedItems[] = $entity;
                $countInserts++;
            } else if (is_object($data)) {
                $localizations = array();
                $entity = new BundleEntity\NavigationItem;
                foreach ($data as $column => $value) {
                    $localeSet = false;
                    $set = 'set' . $this->translateColumnName($column);
                    switch ($column) {
                        case 'local':
                            $localizations[$countInserts]['localizations'] = $value;
                            $localeSet = true;
                            $countLocalizations++;
                            break;
                        case 'navigation':
                            $response = $this->getNavigation($value, 'id');
                            if (!$response['error']) {
                                $entity->$set($response['result']['set']);
                            } else {
                                new CoreExceptions\EntityDoesNotExistException($this->kernel, $value);
                            }
                            unset($response, $sModel);
                            break;
                        case 'page':
                            $response = $this->getPage($value, 'id');
                            if (!$response['error']) {
                                $entity->$set($response['result']['set']);
                            } else {
                                new CoreExceptions\EntityDoesNotExistException($this->kernel, $value);
                            }
                            unset($response, $sModel);
                            break;
                        case 'parent':
                            $response = $this->getNavigation($value, 'id');
                            if (!$response['error']) {
                                $entity->$set($response['result']['set']);
                            } else {
                                new CoreExceptions\EntityDoesNotExistException($this->kernel, $value);
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
            } else {
                new CoreExceptions\InvalidDataException($this->kernel);
            }
        }
        if ($countInserts > 0) {
            $this->em->flush();
        }
        /** Now handle localizations */
        if ($countInserts > 0 && $countLocalizations > 0) {
            $this->insertNavigationItemLocalizations($localizations);
        }
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $insertedItems,
                'total_rows' => $countInserts,
                'last_insert_id' => $entity->getId(),
            ),
            'error' => false,
            'code' => 'scc.db.insert.done',
        );
        return $this->response;
    }

    /**
     * @name            insertPage ()
     *                Inserts one page into database.
     *
     * @since            1.0.0
     * @version         1.1.6
     * @author          Can Berkol
     *
     * @use             $this->insertPages()
     *
     * @param           mixed $page Page Entity or a collection of post input that stores entity details.
     *
     * @return          array           $response
     */
    public function insertPage($page)
    {
        return $this->insertPages(array($page));
    }

    /**
     * @name            insertPageLocalizations ()
     *                Inserts one or more navigation localizations into database.
     *
     * @since            1.1.6
     * @version         1.1.6
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           array $collection Collection of entities or post data.
     *
     * @return          array           $response
     */
    public function insertPageLocalizations($collection)
    {
        $this->resetResponse();
        /** Parameter must be an array */
        if (!is_array($collection)) {
            return $this->createException('InvalidParameter', 'Array', 'err.invalid.parameter.collection');
        }
        $countInserts = 0;
        $insertedItems = array();
        foreach ($collection as $item) {
            if ($item instanceof BundleEntity\PageLocalization) {
                $entity = $item;
                $this->em->persist($entity);
                $insertedItems[] = $entity;
                $countInserts++;
            } else {
                foreach ($item['localizations'] as $language => $data) {
                    $entity = new BundleEntity\PageLocalization;
                    $entity->setPage($item['entity']);
                    $mlsModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
                    $response = $mlsModel->getLanguage($language, 'iso_code');
                    if (!$response['error']) {
                        $entity->setLanguage($response['result']['set']);
                    } else {
                        break 1;
                    }
                    foreach ($data as $column => $value) {
                        $set = 'set' . $this->translateColumnName($column);
                        $entity->$set($value);
                    }
                    $this->em->persist($entity);
                }
                $insertedItems[] = $entity;
                $countInserts++;
            }
        }
        if ($countInserts > 0) {
            $this->em->flush();
        }
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $insertedItems,
                'total_rows' => $countInserts,
                'last_insert_id' => -1,
            ),
            'error' => false,
            'code' => 'scc.db.insert.done',
        );
        return $this->response;
    }

    /**
     * @name            insertPages ()
     *                Inserts one or more pages into database.
     *
     * @since            1.0.0
     * @version         1.1.6
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           array $collection Collection of Page entities or array of member detail array.
     *
     * @return          array           $response
     */
    public function insertPages($collection)
    {
        $this->resetResponse();
        /** Parameter must be an array */
        if (!is_array($collection)) {
            return $this->createException('InvalidParameter', 'Array', 'err.invalid.parameter.collection');
        }
        $countInserts = 0;
        $countLocalizations = 0;
        $insertedItems = array();
        foreach ($collection as $data) {
            if ($data instanceof BundleEntity\Page) {
                $entity = $data;
                $this->em->persist($entity);
                $insertedItems[] = $entity;
                $countInserts++;
            } else if (is_object($data)) {
                $localizations = array();
                $entity = new BundleEntity\Page;
                if (!property_exists($data, 'site')) {
                    $data->site = 1;
                }
                foreach ($data as $column => $value) {
                    $localeSet = false;
                    $set = 'set' . $this->translateColumnName($column);
                    switch ($column) {
                        case 'layout':
                            $response = $this->getLayout($value, 'id');
                            if (!$response['error']) {
                                $entity->$set($response['result']['set']);
                            } else {
                                new CoreExceptions\EntityDoesNotExistExceoption($this->kernel, $value);
                            }
                            unset($response, $sModel);
                            break;
                        case 'local':
                            $localizations[$countInserts]['localizations'] = $value;
                            $localeSet = true;
                            $countLocalizations++;
                            break;
                        case 'site':
                            $sModel = $this->kernel->getContainer()->get('sitemanagement.model');
                            $response = $sModel->getSite($value, 'id');
                            if (!$response['error']) {
                                $entity->$set($response['result']['set']);
                            } else {
                                new CoreExceptions\SiteDoesNotExistException($this->kernel, $value);
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
            } else {
                new CoreExceptions\InvalidDataException($this->kernel);
            }
        }
        if ($countInserts > 0) {
            $this->em->flush();
        }
        /** Now handle localizations */
        if ($countInserts > 0 && $countLocalizations > 0) {
            $this->insertPageLocalizations($localizations);
        }
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $insertedItems,
                'total_rows' => $countInserts,
                'last_insert_id' => $entity->getId(),
            ),
            'error' => false,
            'code' => 'scc.db.insert.done',
        );
        return $this->response;
    }
    /**
     * @name            isFileAssociatedWithPage()
     *                  Checks if the file is already associated with the product.
     *
     * @since           1.1.7
     * @version         1.1.7
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           mixed       $file       'entity' or 'entity' id
     * @param           mixed       $page       'entity' or 'entity' id.
     * @param           bool        $bypass     true or false
     *
     * @return          mixed                   bool or $response
     */
    public function isFileAssociatedWithPage($file, $page, $bypass = false){
        $this->resetResponse();
        /**
         * Validate Parameters
         */
        if (!is_numeric($file) && !$file instanceof FileBundleEntity\File) {
            return $this->createException('InvalidParameter', 'File', 'err.invalid.parameter.file');
        }

        if (!is_numeric($page) && !$page instanceof BundleEntity\Page) {
            return $this->createException('InvalidParameter', 'Page', 'err.invalid.parameter.page');
        }
        $fmmodel = new FileService\FileManagementModel($this->kernel, $this->db_connection, $this->orm);
        /** If no entity is provided as file we need to check if it does exist */
        if (is_numeric($file)) {
            $response = $fmmodel->getFile($file, 'id');
            if ($response['error']) {
                return $this->createException('EntityDoesNotExist', 'File', 'err.db.file.notexist');
            }
            $file = $response['result']['set'];
        }
        /** If no entity is provided as product we need to check if it does exist */
        if (is_numeric($page)) {
            $response = $this->getPage($page, 'id');
            if ($response['error']) {
                return $this->createException('EntityDoesNotExist', 'Product', 'err.db.product.notexist');
            }
            $product = $response['result']['set'];
        }
        $found = false;

        $q_str = 'SELECT COUNT(' . $this->entity['files_of_page']['alias'] . ')'
            . ' FROM ' . $this->entity['files_of_page']['name'] . ' ' . $this->entity['files_of_page']['alias']
            . ' WHERE ' . $this->entity['files_of_page']['alias'] . '.file = ' . $file->getId()
            . ' AND ' . $this->entity['files_of_page']['alias'] . '.page = ' . $page->getId();
        $query = $this->em->createQuery($q_str);

        $result = $query->getSingleScalarResult();

        /** flush all into database */
        if ($result > 0) {
            $found = true;
            $code = 'scc.db.entry.exist';
        } else {
            $code = 'scc.db.entry.noexist';
        }

        if ($bypass) {
            return $found;
        }
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $found,
                'total_rows' => $result,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => $code,
        );
        return $this->response;
    }
    /**
     * @name            listControlPanelThemes ()
     *                Lists all control panel themes.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->listThemes()
     *
     * @param           integer $site
     * @param           array $sort_order
     * @param           array $limit
     *
     * @return          array           $response
     */
    public function listControlPanelThemes($sort_order, $limit)
    {
        $this->resetResponse();
        $filter = array(
            'type' => 'c',
        );
        $response = $this->listThemes($filter, $sort_order, $limit);
        if ($response['error']) {
            return $response;
        }
        $collection = $response['result']['set'];
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $collection[0],
                'total_rows' => 1,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );
        return $this->response;
    }

    /**
     * @name            listEditablePages ()
     *                Lists all editable pages | in other words pages with the status "e" and "s".
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->listPages()
     *
     * @param           array $sort_order
     * @param           array $limit
     * @param           integer $site
     *
     * @return          mixed           $response
     */
    public function listEditablePages($sort_order, $limit, $site = 1)
    {
        $this->resetResponse();
        if (isset($site)) {
            $filter['site'] = $site;
        }
        $filter = array(
            'status' => array('in' => array('e', 's')),
        );
        $response = $this->listPages($filter, $sort_order, $limit);
        if ($response['error']) {
            return $response;
        }
        $collection = $response['result']['set'];
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $collection[0],
                'total_rows' => 1,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );
        return $this->response;
    }

    /**
     * @name            listFilesOfPage ()
     *                Lists file - page associations and related data.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           array $filter Multi-dimensional array
     *
     *                                  Example:
     *                                  $filter = array(
     *                                      'address_type'  => array('in' => array("2,5),
     *                                                               'not_in' => array(4)
     *                                                              ),
     *                                      'member'        => array('in' => array(Member1, Member2)),
     *                                      'tax_id'        => 21312412,
     *                                  );
     *
     *                                  Each array element defines an AND condition.
     *                                  Each array element contains another array with keys
     *                                  in and not_in to include and to exclude data.
     *                                  Each nested array element that is containted in condition states
     *                                  an OR condition.
     *
     * @param           array $sortorder Array
     *                                      'column'            => 'asc|desc'
     * @param           array $limit
     *                                      start
     *                                      count
     *
     * @param           mixed $site id or Site Entity.
     *
     * @return          array           $response
     */
    public function listFilesOfPage($filter = null, $sortorder = null, $limit = null, $site = 1)
    {
        $this->resetResponse();
        if (isset($site)) {
            $filter['site'] = $site;
        }
        if (!is_array($sortorder) && !is_null($sortorder)) {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.sortorder', '');
            return $this->response;
        }
        /**
         * Check if it is needed to join two or more tables.
         */
        /**         * ************************************************** */
        $order_str = '';
        $where_str = '';
        $group_str = '';
        /**
         * Start creating the query.
         */
        $query_str = 'SELECT ' . $this->entity['files_of_page']['alias']
            . ' FROM ' . $this->entity['files_of_page']['name'] . ' ' . $this->entity['files_of_page']['alias'];
        /**
         * Prepare ORDER BY part of query.
         */
        if ($sortorder != null) {
            foreach ($sortorder as $column => $direction) {
                switch ($column) {
                    default:
                        $order_str .= ' ' . $this->entity['files_of_page']['alias'] . '.' . $column . ' ' . $direction . ', ';
                        break;
                }
            }
            $order_str = rtrim($order_str, ', ');
            $order_str = ' ORDER BY ' . $order_str . ' ';
        }
        if ($filter != null) {
            $and = '(';
            foreach ($filter as $column => $value) {
                /** decide which alias to use */
                switch ($column) {
                    default:
                        $alias = $this->entity['files_of_page']['alias'];
                        break;
                }
                /** If value is array we need to run through all values with a loop */
                if (is_array($value)) {
                    $or = '(';
                    foreach ($value as $key => $sub_value) {
                        if (!is_array($sub_value)) {
                            new CoreExceptions\InvalidFilterException($this->kernel, '');
                            break;
                        }
                        $tmp_sub_value = array();
                        foreach ($sub_value as $item) {
                            if (is_object($item)) {
                                $tmp_sub_value[] = $item->getId();
                            } else {
                                $tmp_sub_value[] = $item;
                            }
                        }
                        if (count($tmp_sub_value) > 0) {
                            $sub_value = $tmp_sub_value;
                        }
                        $or .= ' ' . $alias . '.' . $column;
                        switch ($key) {
                            case 'starts':
                                $or .= ' LIKE \'' . $sub_value[0] . '%\' ';
                                break;
                            case 'ends':
                                $or .= ' LIKE \'%' . $sub_value[0] . '\' ';
                                break;
                            case 'contains':
                                $or .= ' LIKE \'%' . $sub_value[0] . '%\' ';
                                break;
                            case 'in':
                            case 'include':
                                $in = implode(',', $sub_value);
                                $or .= ' IN(' . $in . ') ';
                                break;
                            case 'not_in':
                            case 'exclude':
                                $not_in = implode(',', $sub_value);
                                $or .= ' NOT IN(' . $not_in . ') ';
                                break;
                        }
                        $or .= ') OR ';
                    }
                    $or = rtrim($or, ' OR');
                    $and .= $or;
                } else {
                    if (is_object($value)) {
                        $value = $value->getId();
                    }
                    if (is_numeric($value)) {

                        $and .= ' ' . $alias . '.' . $column . ' = ' . $value;
                    } else {
                        $and .= ' ' . $alias . '.' . $column . ' = \'' . $value . '\'';
                    }
                }
                $and .= ' AND ';
            }
            $and = rtrim($and, ' AND') . ')';
            $where_str .= ' WHERE ' . $and;
        }

        $query_str .= $where_str . $group_str . $order_str;
        $query = $this->em->createQuery($query_str);

        if ($limit != null) {
            if (isset($limit['start']) && isset($limit['count'])) {
                /** If limit is set */
                $query->setFirstResult($limit['start']);
                $query->setMaxResults($limit['count']);
            } else {
                new CoreExceptions\InvalidLimitException($this->kernel, '');
            }
        }
        /**
         * Prepare & Return Response
         */
        $result = $query->getResult();

        $total_rows = count($result);
        if ($total_rows < 1) {
            $this->response['code'] = 'err.db.entry.notexist';
            return $this->response;
        }
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $result,
                'total_rows' => $total_rows,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );
        return $this->response;
    }

    /**
     * @name            listFrontendThemes ()
     *                Lists all control panel themes.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->listThemes()
     *
     * @param           array $sort_order
     * @param           array $limit
     *
     * @return          array           $response
     */
    public function listFrontendThemes($sort_order, $limit)
    {
        $this->resetResponse();
        $filter = array(
            'type' => 'f',
        );
        $response = $this->listThemes($filter, $sort_order, $limit);
        if ($response['error']) {
            return $response;
        }
        $collection = $response['result']['set'];
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $collection[0],
                'total_rows' => 1,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );
        return $this->response;
    }

    /**
     * @name            listItemsOfNavigation ()
     *                Lists all items of a navigation.
     *
     * @since            1.0.1
     * @version         1.1.4
     * @author          Can Berkol
     *
     * @use             $this->listNavigationItemsOfNavigation()
     *
     * @param           mixed $navigation
     * @param           mixed $level Top = Parent, bottom = last children, all = all
     * @param           array $sort_order
     * @param           array $limit
     *
     * @return          array           $response
     */
    public function listItemsOfNavigation($navigation, $level = 'top', $sort_order = null, $limit = null)
    {
        return $this->listNavigationItemsOfNavigation($navigation, $level, $sort_order, $limit);
    }

    /**
     * @name            listLayouts ()
     *                List layouts from database based on a variety of conditions.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           array $filter Multi-dimensional array
     *
     *                                  Example:
     *                                  $filter = array(
     *                                      'address_type'  => array('in' => array(2,5),
     *                                                               'not_in' => array(4)
     *                                                              ),
     *                                      'member'        => array('in' => array(Member1, Member2)),
     *                                      'tax_id'        => 21312412,
     *                                  );
     *
     *                                  Each array element defines an AND condition.
     *                                  Each array element contains another array with keys
     *                                  in and not_in to include and to exclude data.
     *                                  Each nested array element that is containted in condition states
     *                                  an OR condition.
     *
     * @param           array $sortorder Array
     *                                      'column'            => 'asc|desc'
     * @param           array $limit
     *                                      start
     *                                      count
     *
     * @param           mixed $site id or Site Entity.
     *
     * @return          array           $response
     */
    public function listLayouts($filter = null, $sortorder = null, $limit = null, $site = 1)
    {
        if (isset($site)) {
            $filter['site'] = $site;
        }
        $this->resetResponse();
        if (!is_array($sortorder) && !is_null($sortorder)) {
            new CoreExceptions\InvalidSortOrderException($this->kernel, '');
            $this->response['code'] = 'err.invalid.parameter.sortorder';
            return $this->response;
        }
        /**
         * Check if it is needed to join two or more tables.
         */
        $join_needed = false;
        if (isset($filter['url_key']) || isset($filter['url_key'])) {
            $join_needed = true;
        }
        /**
         * Add filter checks to below to set join_needed to true.
         */
        /**         * ************************************************** */
        $order_str = '';
        $where_str = '';
        $group_str = '';
        /**
         * Start creating the query.
         */
        if ($join_needed) {
            $query_str = 'SELECT DISTINCT ' . $this->entity['layout_localization']['alias'] . ', ' . $this->entity['layout']['alias']
                . ' FROM ' . $this->entity['layout_localization']['name'] . ' ' . $this->entity['layout_localization']['alias']
                . ' JOIN ' . $this->entity['layout_localization']['alias'] . '.layout ' . $this->entity['layout']['alias'];
        } else {
            $query_str = 'SELECT ' . $this->entity['layout']['alias'] . ' FROM ' . $this->entity['layout']['name'] . ' ' . $this->entity['layout']['alias'];
        }
        /**
         * Prepare ORDER BY part of query.
         */
        if ($sortorder != null) {
            foreach ($sortorder as $column => $direction) {
                switch ($column) {
                    case 'url_key':
                        $order_str .= ' ' . $this->entity['layout_localization']['alias'] . '.' . $column . ' ' . $direction . ', ';
                        break;
                    default:
                        $order_str .= ' ' . $this->entity['layout']['alias'] . '.' . $column . ' ' . $direction . ', ';
                        break;
                }
            }
            $order_str = rtrim($order_str, ', ');
            $order_str = ' ORDER BY ' . $order_str . ' ';
        }
        if ($filter != null) {
            $and = '(';
            foreach ($filter as $column => $value) {
                /** decide which alias to use */
                switch ($column) {
                    case 'url_key':
                        $alias = $this->entity['layout_localization']['alias'];
                        break;
                    default:
                        $alias = $this->entity['layout']['alias'];
                        break;
                }
                /** If value is array we need to run through all values with a loop */
                if (is_array($value)) {
                    $or = '(';
                    foreach ($value as $key => $sub_value) {
                        if (!is_array($sub_value)) {
                            new CoreExceptions\InvalidFilterException($this->kernel, '');
                            break;
                        }
                        $tmp_sub_value = array();
                        foreach ($sub_value as $item) {
                            if (is_object($item)) {
                                $tmp_sub_value[] = $item->getId();
                            } else {
                                $tmp_sub_value[] = $item;
                            }
                        }
                        if (count($tmp_sub_value) > 0) {
                            $sub_value = $tmp_sub_value;
                        }
                        $or .= ' ' . $alias . '.' . $column;
                        switch ($key) {
                            case 'starts':
                                $or .= ' LIKE \'' . $sub_value[0] . '%\' ';
                                break;
                            case 'ends':
                                $or .= ' LIKE \'%' . $sub_value[0] . '\' ';
                                break;
                            case 'contains':
                                $or .= ' LIKE \'%' . $sub_value[0] . '%\' ';
                                break;
                            case 'in':
                            case 'include':
                                $in = implode(',', $sub_value);
                                $or .= ' IN(' . $in . ') ';
                                break;
                            case 'not_in':
                            case 'exclude':
                                $not_in = implode(',', $sub_value);
                                $or .= ' NOT IN(' . $not_in . ') ';
                                break;
                        }
                        $or .= ') OR ';
                    }
                    $or = rtrim($or, ' OR');
                    $and .= $or;
                } else {
                    if (is_object($value)) {
                        $value = $value->getId();
                    }
                    if (is_numeric($value)) {

                        $and .= ' ' . $alias . '.' . $column . ' = ' . $value;
                    } else {
                        $and .= ' ' . $alias . '.' . $column . ' = \'' . $value . '\'';
                    }
                }
                $and .= ' AND ';
            }
            $and = rtrim($and, ' AND') . ')';
            $where_str .= ' WHERE ' . $and;
        }

        $query_str .= $where_str . $group_str . $order_str;
        $query = $this->em->createQuery($query_str);

        if ($limit != null) {
            if (isset($limit['start']) && isset($limit['count'])) {
                /** If limit is set */
                $query->setFirstResult($limit['start']);
                $query->setMaxResults($limit['count']);
            } else {
                new CoreExceptions\InvalidLimitException($this->kernel, '');
            }
        }
        /**
         * Prepare & Return Response
         */
        $result = $query->getResult();
        if ($join_needed) {
            $collection = array();
            foreach ($result as $compound_entity) {
                $collection[] = $compound_entity->getLayout();
            }
            $result = $collection;
        }

        $total_rows = count($result);
        if ($total_rows < 1) {
            $this->response['code'] = 'err.db.entity.notexist';
            return $this->response;
        }
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $result,
                'total_rows' => $total_rows,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entity.exist',
        );
        return $this->response;
    }

    /**
     * @name            listMemberEditablePages ()
     *                Lists only member editable pages | in other words pages with the status "e".
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->listPages()
     *
     * @param           array $sort_order
     * @param           array $limit
     * @param           integer $site
     *
     * @return          mixed           $response
     */
    public function listMemberEditablePages($sort_order, $limit, $site = 1)
    {
        $this->resetResponse();
        if (isset($site)) {
            $filter['site'] = $site;
        }
        $filter = array(
            'status' => array('in' => array('e')),
        );
        $response = $this->listPages($filter, $sort_order, $limit);
        if ($response['error']) {
            return $response;
        }
        $collection = $response['result']['set'];
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $collection[0],
                'total_rows' => 1,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.found',
        );
        return $this->response;
    }

    /**
     * @name            listLayoutsOfSite ()
     *                Lists that that belong to a specific site.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->listLayouts()
     *
     * @param           integer $site
     * @param           array $sort_order
     * @param           array $limit
     *
     * @return          array           $response
     */
    public function listLayoutsOfSite($site = 1, $sort_order, $limit)
    {
        $this->resetResponse();
        if (isset($site)) {
            $filter['site'] = $site;
        }
        $response = $this->listLayouts($filter, $sort_order, $limit);
        if ($response['error']) {
            return $response;
        }
        $collection = $response['result']['set'];
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $collection[0],
                'total_rows' => 1,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );
        return $this->response;
    }

    /**
     * @name            listLayotusOfTheme ()
     *                Lists that that belong to a specific site..
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->listLayouts()
     *
     * @throws          InvalidEntityException
     *
     * @param           mixed $theme Entity or id
     * @param           array $sort_order
     * @param           array $limit
     *
     * @return          array           $response
     */
    public function listLayoutsOfTheme($theme, $sort_order, $limit)
    {
        $this->resetResponse();
        if (is_object($theme) && $theme instanceof BundleEntity\Theme) {
            $theme = $theme->getId();
        }
        $filter = array(
            'theme' => $theme,
        );
        $response = $this->listLayouts($filter, $sort_order, $limit);
        if ($response['error']) {
            return $response;
        }
        $collection = $response['result']['set'];
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $collection[0],
                'total_rows' => 1,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );
        return $this->response;
    }

    /**
     * @name            listModules ()
     *                List modules from database based on a variety of conditions.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->createException
     *
     * @param           array $filter Multi-dimensional array
     *
     *                                  Example:
     *                                  $filter = array(
     *                                      'address_type'  => array('in' => array(2,5),
     *                                                               'not_in' => array(4)
     *                                                              ),
     *                                      'member'        => array('in' => array(Member1, Member2)),
     *                                      'tax_id'        => 21312412,
     *                                  );
     *
     *                                  Each array element defines an AND condition.
     *                                  Each array element contains another array with keys
     *                                  in and not_in to include and to exclude data.
     *                                  Each nested array element that is containted in condition states
     *                                  an OR condition.
     *
     * @param           array $sortorder Array
     *                                      'column'            => 'asc|desc'
     * @param           array $limit
     *                                      start
     *                                      count
     *
     * @param           mixed $site id or Site Entity.
     *
     * @return          array           $response
     */
    public function listModules($filter = null, $sortorder = null, $limit = null, $site = 1)
    {
        if (isset($site)) {
            $filter['site'] = $site;
        }
        $this->resetResponse();
        if (!is_array($sortorder) && !is_null($sortorder)) {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.sortorder', '');
        }
        /**
         * Check if it is needed to join two or more tables.
         */
        $join_needed = false;
        if (isset($filter['url_key']) || isset($filter['url_key'])) {
            $join_needed = true;
        }
        /**
         * Add filter checks to below to set join_needed to true.
         */
        /**         * ************************************************** */
        $order_str = '';
        $where_str = '';
        $group_str = '';
        /**
         * Start creating the query.
         */
        if ($join_needed) {
            $query_str = 'SELECT DISTINCT ' . $this->entity['module_localization']['alias'] . ', ' . $this->entity['module']['alias']
                . ' FROM ' . $this->entity['module_localization']['name'] . ' ' . $this->entity['module_localization']['alias']
                . ' JOIN ' . $this->entity['module_localization']['alias'] . '.page ' . $this->entity['module']['alias'];
        } else {
            $query_str = 'SELECT ' . $this->entity['module']['alias'] . ' FROM ' . $this->entity['module']['name'] . ' ' . $this->entity['module']['alias'];
        }
        /**
         * Prepare ORDER BY part of query.
         */
        if ($sortorder != null) {
            foreach ($sortorder as $column => $direction) {
                switch ($column) {
                    case 'url_key':
                        $order_str .= ' ' . $this->entity['module_localization']['alias'] . '.' . $column . ' ' . $direction . ', ';
                        break;
                    default:
                        $order_str .= ' ' . $this->entity['module']['alias'] . '.' . $column . ' ' . $direction . ', ';
                        break;
                }
            }
            $order_str = rtrim($order_str, ', ');
            $order_str = ' ORDER BY ' . $order_str . ' ';
        }
        if ($filter != null) {
            $and = '(';
            foreach ($filter as $column => $value) {
                /** decide which alias to use */
                switch ($column) {
                    case 'url_key':
                        $alias = $this->entity['module_localization']['alias'];
                        break;
                    default:
                        $alias = $this->entity['module']['alias'];
                        break;
                }
                /** If value is array we need to run through all values with a loop */
                if (is_array($value)) {
                    $or = '(';
                    foreach ($value as $key => $sub_value) {
                        if (!is_array($sub_value)) {
                            new CoreExceptions\InvalidFilterException($this->kernel, '');
                            break;
                        }
                        $tmp_sub_value = array();
                        foreach ($sub_value as $item) {
                            if (is_object($item)) {
                                $tmp_sub_value[] = $item->getId();
                            } else {
                                $tmp_sub_value[] = $item;
                            }
                        }
                        if (count($tmp_sub_value) > 0) {
                            $sub_value = $tmp_sub_value;
                        }
                        $or .= ' ' . $alias . '.' . $column;
                        switch ($key) {
                            case 'starts':
                                $or .= ' LIKE \'' . $sub_value[0] . '%\' ';
                                break;
                            case 'ends':
                                $or .= ' LIKE \'%' . $sub_value[0] . '\' ';
                                break;
                            case 'contains':
                                $or .= ' LIKE \'%' . $sub_value[0] . '%\' ';
                                break;
                            case 'in':
                            case 'include':
                                $in = implode(',', $sub_value);
                                $or .= ' IN(' . $in . ') ';
                                break;
                            case 'not_in':
                            case 'exclude':
                                $not_in = implode(',', $sub_value);
                                $or .= ' NOT IN(' . $not_in . ') ';
                                break;
                        }
                        $or .= ') OR ';
                    }
                    $or = rtrim($or, ' OR');
                    $and .= $or;
                } else {
                    if (is_object($value)) {
                        $value = $value->getId();
                    }
                    if (is_numeric($value)) {

                        $and .= ' ' . $alias . '.' . $column . ' = ' . $value;
                    } else {
                        $and .= ' ' . $alias . '.' . $column . ' = \'' . $value . '\'';
                    }
                }
                $and .= ' AND ';
            }
            $and = rtrim($and, ' AND') . ')';
            $where_str .= ' WHERE ' . $and;
        }

        $query_str .= $where_str . $group_str . $order_str;
        $query = $this->em->createQuery($query_str);

        if ($limit != null) {
            if (isset($limit['start']) && isset($limit['count'])) {
                /** If limit is set */
                $query->setFirstResult($limit['start']);
                $query->setMaxResults($limit['count']);
            } else {
                new CoreExceptions\InvalidLimitException($this->kernel, '');
            }
        }
        /**
         * Prepare & Return Response
         */
        $result = $query->getResult();
        if ($join_needed) {
            $collection = array();
            foreach ($result as $compound_entity) {
                $collection[] = $compound_entity->getPage();
            }
            $result = $collection;
        }

        $total_rows = count($result);
        if ($total_rows < 1) {
            $this->response['code'] = 'err.db.entry.notexist';
            return $this->response;
        }
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $result,
                'total_rows' => $total_rows,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );
        return $this->response;
    }

    /**
     * @name            listModulesOfPageLayouts ()
     *                Lists modules located at a specific page and layout.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           array $filter Multi-dimensional array
     *
     *                                  Example:
     *                                  $filter = array(
     *                                      'address_type'  => array('in' => array(2,5),
     *                                                               'not_in' => array(4)
     *                                                              ),
     *                                      'member'        => array('in' => array(Member1, Member2)),
     *                                      'tax_id'        => 21312412,
     *                                  );
     *
     *                                  Each array element defines an AND condition.
     *                                  Each array element contains another array with keys
     *                                  in and not_in to include and to exclude data.
     *                                  Each nested array element that is containted in condition states
     *                                  an OR condition.
     *
     * @param           array $sortorder Array
     *                                      'column'            => 'asc|desc'
     * @param           array $limit
     *                                      start
     *                                      count
     *
     * @param           mixed $site id or Site Entity.
     *
     * @return          array           $response
     */
    public function listModulesOfPageLayouts($filter = null, $sortorder = null, $limit = null, $site = 1)
    {
        $this->resetResponse();
        /**
         * Check if it is needed to join two or more tables.
         */
        /**         * ************************************************** */
        $order_str = '';
        $where_str = '';
        $group_str = '';
        /**
         * Start creating the query.
         */
        $query_str = 'SELECT ' . $this->entity['modules_of_layout']['alias'] . ', ' . $this->entity['module']['alias']
            . ', ' . $this->entity['layout']['alias']
            . ' FROM ' . $this->entity['modules_of_layout']['name'] . ' ' . $this->entity['modules_of_layout']['alias']
            . ' JOIN ' . $this->entity['modules_of_layout']['alias'] . '.module ' . $this->entity['module']['alias']
            . ' JOIN ' . $this->entity['modules_of_layout']['alias'] . '.layout ' . $this->entity['layout']['alias'];
        /**
         * Prepare ORDER BY part of query.
         */
        if ($sortorder != null) {
            foreach ($sortorder as $column => $direction) {
                switch ($column) {
                    default:
                        $order_str .= ' ' . $this->entity['modules_of_layout']['alias'] . '.' . $column . ' ' . $direction . ', ';
                        break;
                }
            }
            $order_str = rtrim($order_str, ', ');
            $order_str = ' ORDER BY ' . $order_str . ' ';
        }
        if ($filter != null) {
            $and = '(';
            foreach ($filter as $column => $value) {
                /** decide which alias to use */
                switch ($column) {
                    default:
                        $alias = $this->entity['modules_of_layout']['alias'];
                        break;
                }
                /** If value is array we need to run through all values with a loop */
                if (is_array($value)) {
                    $or = '(';
                    foreach ($value as $key => $sub_value) {
                        if (!is_array($sub_value)) {
                            new CoreExceptions\InvalidFilterException($this->kernel, '');
                            break;
                        }
                        $tmp_sub_value = array();
                        foreach ($sub_value as $item) {
                            if (is_object($item)) {
                                $tmp_sub_value[] = $item->getId();
                            } else {
                                $tmp_sub_value[] = $item;
                            }
                        }
                        if (count($tmp_sub_value) > 0) {
                            $sub_value = $tmp_sub_value;
                        }
                        $or .= ' ' . $alias . '.' . $column;
                        switch ($key) {
                            case 'starts':
                                $or .= ' LIKE \'' . $sub_value[0] . '%\' ';
                                break;
                            case 'ends':
                                $or .= ' LIKE \'%' . $sub_value[0] . '\' ';
                                break;
                            case 'contains':
                                $or .= ' LIKE \'%' . $sub_value[0] . '%\' ';
                                break;
                            case 'in':
                            case 'include':
                                $in = implode(',', $sub_value);
                                $or .= ' IN(' . $in . ') ';
                                break;
                            case 'not_in':
                            case 'exclude':
                                $not_in = implode(',', $sub_value);
                                $or .= ' NOT IN(' . $not_in . ') ';
                                break;
                        }
                        $or .= ') OR ';
                    }
                    $or = rtrim($or, ' OR');
                    $and .= $or;
                } else {
                    if (is_object($value)) {
                        $value = $value->getId();
                    }
                    if (is_numeric($value)) {

                        $and .= ' ' . $alias . '.' . $column . ' = ' . $value;
                    } else {
                        $and .= ' ' . $alias . '.' . $column . ' = \'' . $value . '\'';
                    }
                }
                $and .= ' AND ';
            }
            $and = rtrim($and, ' AND') . ')';
            $where_str .= ' WHERE ' . $and;
        }

        $query_str .= $where_str . $group_str . $order_str;
        $query = $this->em->createQuery($query_str);
//        $query->setFetchMode('BiberLtd\Core\Bundles\ContentManagementBundle\Entity\ModulesOfLayout', 'module', 'EAGER');
        if ($limit != null) {
            if (isset($limit['start']) && isset($limit['count'])) {
                /** If limit is set */
                $query->setFirstResult($limit['start']);
                $query->setMaxResults($limit['count']);
            } else {
                new CoreExceptions\InvalidLimitException($this->kernel, '');
            }
        }
        /**
         * Prepare & Return Response
         */
        $result = $query->getResult();

        $total_rows = count($result);
        if ($total_rows < 1) {
            $this->response['code'] = 'err.db.entry.notexist';
            return $this->response;
        }
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $result,
                'total_rows' => $total_rows,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );
        return $this->response;
    }

    /**
     * @name            listModulesOfPageLayouts ()
     *                Lists modules located at a specific page and layout and return the modules grouped by section.
     *
     * @since            1.0.1
     * @version         1.1.3
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           array $page Page Entity
     * @param           array $sortorder Array
     *                                      'column'            => 'asc|desc'
     * @param           array $limit
     *
     * @return          array           $response
     */
    public function listModulesOfPageLayoutsGroupedBySection($page, $sortorder = null, $limit = null)
    {
        $this->resetResponse();
        if (!$page instanceof BundleEntity\Page || !$page || is_null($page)) {
            return $this->createException('InvalidParameterException', 'BundleEntity\\Page','err.invalid.parameter.page');
        }
        $filter['page'] = $page->getId();
        $response = $this->listModulesOfPageLayouts($filter, $sortorder, $limit);
        if ($response['error']) {
            return $response;
        }
        $mops = $response['result']['set'];
        $modules = array();
        $count = 0;
        foreach ($mops as $mop) {
            $modules[$mop->getSection()][$count]['entity'] = $mop->getModule();
            $modules[$mop->getSection()][$count]['contents'] = $mop->getLocalizations();
            $modules[$mop->getSection()][$count]['style'] = $mop->getStyle();
            $count++;
        }
        return $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $modules,
                'total_rows' => $count,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );
    }

    /**
     * @name            listModulesOfSite ()
     *                Lists modules that belong to a site.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->listThemes()
     *
     * @param           mixed $site id or entity
     * @param           array $sort_order
     * @param           array $limit
     *
     * @return          array           $response
     */
    public function listModulesOfSite($site, $sort_order, $limit)
    {
        $this->resetResponse();
        if (is_object($site)) {
            $site = $site->getId();
        }
        $filter = array(
            'site' => $site,
        );
        $response = $this->listModules($filter, $sort_order, $limit);
        if ($response['error']) {
            return $response;
        }
        $collection = $response['result']['set'];
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $collection[0],
                'total_rows' => 1,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );
        return $this->response;
    }

    /**
     * @name            listModulesOfTheme ()
     *                Lists modules that belong to a theme.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->listModules()
     *
     * @param           mixed $theme id or entity
     * @param           array $sort_order
     * @param           array $limit
     *
     * @return          array           $response
     */
    public function listModulesOfTheme($theme, $sort_order, $limit)
    {
        $this->resetResponse();
        if (is_object($theme) && $theme instanceof BundleEntity\Theme) {
            $theme = $theme->getId();
        }
        $filter = array(
            'theme' => $theme,
        );
        $response = $this->listtModules($filter, $sort_order, $limit);
        if ($response['error']) {
            return $response;
        }
        $collection = $response['result']['set'];
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $collection[0],
                'total_rows' => 1,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );
        return $this->response;
    }

    /**
     * @name            listNavigationItems ()
     *                List navigation items from database based on a variety of conditions.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           array $filter Multi-dimensional array
     *
     * @param           array $sortorder Array
     *                                      'column'            => 'asc|desc'
     * @param           array $limit start  count
     *
     * @param           mixed $site id or Site Entity.
     *
     * @return          array           $response
     */
    public function listNavigationItems($filter = null, $sortorder = null, $limit = null)
    {
        $this->resetResponse();

        if (!is_array($sortorder) && !is_null($sortorder)) {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.sortorder', '');
        }
        /**
         * Check if it is needed to join two or more tables.
         */
        $join_needed = false;
        if (isset($filter['url_key']) || isset($filter['url_key'])) {
            $join_needed = true;
        }

        $order_str = '';
        $where_str = '';
        $group_str = '';
        /**
         * Start creating the query.
         */
        if ($join_needed) {
            $query_str = 'SELECT DISTINCT ' . $this->entity['navigation_item_localization']['alias'] . ', ' . $this->entity['navigation_item']['alias'] . ', ' . $this->entity['navigation']['alias']
                . ' FROM ' . $this->entity['navigation_item_localization']['name'] . ' ' . $this->entity['navigation_item_localization']['alias']
                . ' JOIN ' . $this->entity['navigation_item_localization']['alias'] . '.navigation_item ' . $this->entity['navigation_item']['alias']
                . ' JOIN ' . $this->entity['navigation_item']['alias'] . '.navigation ' . $this->entity['navigation']['alias'];
        } else {
            $query_str = 'SELECT ' . $this->entity['navigation_item']['alias'] . ', ' . $this->entity['navigation']['alias'] . ', ' . $this->entity['navigation_item_localization']['alias']
                . ' FROM ' . $this->entity['navigation_item']['name'] . ' ' . $this->entity['navigation_item']['alias']
                . ' JOIN ' . $this->entity['navigation_item']['alias'] . '.navigation ' . $this->entity['navigation']['alias']
                . ' JOIN ' . $this->entity['navigation_item']['alias'] . '.localizations ' . $this->entity['navigation_item_localization']['alias'];
        }
        /**
         * Prepare ORDER BY part of query.
         */
        if ($sortorder != null) {
            foreach ($sortorder as $column => $direction) {
                switch ($column) {
                    case 'name':
                        $order_str .= ' ' . $this->entity['navigation_item_localization']['alias'] . '.' . $column . ' ' . $direction . ', ';
                        break;
                    default:
                        $order_str .= ' ' . $this->entity['navigation_item']['alias'] . '.' . $column . ' ' . $direction . ', ';
                        break;
                }
            }
            $order_str = rtrim($order_str, ', ');
            $order_str = ' ORDER BY ' . $order_str . ' ';
        }
        /**
         * Prepare WHERE section of query.
         */
        if ($filter != null) {
            $filter_str = $this->prepareWhere($filter);
            $where_str .= ' WHERE ' . $filter_str;
        }
        $query_str .= $where_str . $group_str . $order_str;
        $query = $this->em->createQuery($query_str);
        if ($limit != null) {
            if (isset($limit['start']) && isset($limit['count'])) {
                /** If limit is set */
                $query->setFirstResult($limit['start']);
                $query->setMaxResults($limit['count']);
            } else {
                new CoreExceptions\InvalidLimitException($this->kernel, '');
            }
        }
        /**
         * Prepare & Return Response
         */
        $result = $query->getResult();
        if ($join_needed) {
            $collection = array();
            foreach ($result as $compound_entity) {
                $collection[] = $compound_entity->getNavigationItem();
            }
            $result = $collection;
        }

        $total_rows = count($result);
        if ($total_rows < 1) {
            $this->response['code'] = 'err.db.entry.notexist';
            return $this->response;
        }
        $this->response = array(
            'result' => array(
                'set' => $result,
                'total_rows' => $total_rows,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );
        return $this->response;
    }

    /**
     * @name            listNavigationItemsOfNavigation ()
     *                Lists that navigation items of navigation
     *
     * @since            1.0.1
     * @version         1.1.4
     * @author          Can Berkol
     *
     * @use             $this->listNavigationItems()
     *
     * @throws          InvalidEntityException
     *
     * @param           mixed $navigation id, entity
     * @param           mixed $level
     * @param           array $sort_order
     * @param           array $limit
     *
     * @return          array           $response
     */
    public function listNavigationItemsOfNavigation($navigation, $level = 'top', $sort_order = null, $limit = null)
    {
        $this->resetResponse();
        if (is_object($navigation) && $navigation instanceof BundleEntity\Navigation) {
            $navigation = $navigation->getId();
        } else if (!is_numeric($navigation) && is_string($navigation)) {
            $response = $this->getNavigation($navigation, 'code');
            if (!$response['error']) {
                $navigationObj = $response['result']['set'];
            }
            $navigation = $navigationObj->getId();
        }
        unset($navigationObj, $response);
        $filter = array();
        $filter[] = array(
            'glue' => 'and',
            'condition' => array(
                array(
                    'glue' => 'and',
                    'condition' => array('column' => $this->entity['navigation_item']['alias'].'.navigation', 'comparison' => '=', 'value' => $navigation),
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
                    'condition' => array('column' => $this->entity['navigation_item']['alias'].'.is_child', 'comparison' => '=', 'value' => $isChild),
                )
            )
        );
        return $this->listNavigationItems($filter, $sort_order, $limit);
    }

    /**
     * @name            listNavigationItemsOfParent ()
     *                Lists that that belong to a specific site..
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->listNavigationItems()
     *
     * @param           mixed $parent id, entity
     * @param           array $sort_order
     * @param           array $limit
     *
     * @return          array           $response
     */
    public function listNavigationItemsOfParent($parent = 1, $sort_order = null, $limit = null)
    {
        $this->resetResponse();
        if (is_object($parent) && $parent instanceof BundleEntity\NavigationItem) {
            $parent = $parent->getId();
        } else if (!is_numeric($parent) && is_string($parent)) {
            $response = $this->getNavigation($parent, 'code');
            if (!$response['error']) {
                $parentObj = $response['result']['set'];
            }
            $parent = $parentObj->getId();
        }
        unset($parentObj);
        $filter = array();
        $filter[] = array(
            'glue' => 'and',
            'condition' => array(
                array(
                    'glue' => 'and',
                    'condition' => array('column' => $this->entity['navigation_item']['alias'].'.parent', 'comparison' => '=', 'value' => $parent),
                )
            )
        );
        $response = $this->listNavigationItems($filter, $sort_order, $limit);
        if ($response['error']) {
            return $response;
        }
        $collection = $response['result']['set'];
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'result' => array(
                'set' => $collection,
                'total_rows' => count($collection),
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );
        return $this->response;
    }

    /**
     * @name            listNavigations ()
     *                List navigations from database based on a variety of conditions.
     *
     * @since            1.0.1
     * @version         1.1.2
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           array $filter Multi-dimensional array
     *
     *                                  Example:
     *                                  $filter = array(
     *                                      'address_type'  => array('in' => array(2,5),
     *                                                               'not_in' => array(4)
     *                                                              ),
     *                                      'member'        => array('in' => array(Member1, Member2)),
     *                                      'tax_id'        => 21312412,
     *                                  );
     *
     *                                  Each array element defines an AND condition.
     *                                  Each array element contains another array with keys
     *                                  in and not_in to include and to exclude data.
     *                                  Each nested array element that is containted in condition states
     *                                  an OR condition.
     *
     * @param           array $sortorder Array
     *                                      'column'            => 'asc|desc'
     * @param           array $limit
     *                                      start
     *                                      count
     *
     * @param           mixed $site id or Site Entity.
     *
     * @return          array           $response
     */
    public function listNavigations($filter = null, $sortorder = null, $limit = null, $site = 1)
    {
        $filter[] = array(
            'glue' => 'and',
            'condition' => array(
                array(
                    'glue' => 'and',
                    'condition' => array('column' =>$this->entity['navigation']['alias'].'.site' , 'comparison' => '=', 'value' =>$site ),
                )
            )
        );
        $this->resetResponse();
        if (!is_array($sortorder) && !is_null($sortorder)) {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.sortorder', '');
        }
        /**
         * Check if it is needed to join two or more tables.
         */
        $join_needed = false;
        if (isset($filter['url_key']) || isset($filter['url_key'])) {
            $join_needed = true;
        }
        /**
         * Add filter checks to below to set join_needed to true.
         */
        /**         * ************************************************** */
        $order_str = '';
        $where_str = '';
        $group_str = '';
        /**
         * Start creating the query.
         */
        if ($join_needed) {
            $query_str = 'SELECT DISTINCT ' . $this->entity['navigation_localization']['alias'] . ', ' . $this->entity['navigation']['alias']
                . ' FROM ' . $this->entity['navigation_localization']['name'] . ' ' . $this->entity['navigation_localization']['alias']
                . ' JOIN ' . $this->entity['navigation_localization']['alias'] . '.navigation ' . $this->entity['navigation']['alias'];
        } else {
            $query_str = 'SELECT ' . $this->entity['navigation']['alias'] . ' FROM ' . $this->entity['navigation']['name'] . ' ' . $this->entity['navigation']['alias'];
        }
        /**
         * Prepare ORDER BY part of query.
         */
        if ($sortorder != null) {
            foreach ($sortorder as $column => $direction) {
                switch ($column) {
                    case 'url_key':
                        $order_str .= ' ' . $this->entity['navigation_localization']['alias'] . '.' . $column . ' ' . $direction . ', ';
                        break;
                    default:
                        $order_str .= ' ' . $this->entity['navigation']['alias'] . '.' . $column . ' ' . $direction . ', ';
                        break;
                }
            }
            $order_str = rtrim($order_str, ', ');
            $order_str = ' ORDER BY ' . $order_str . ' ';
        }
        /**
         * Prepare WHERE section of query.
         */
        if ($filter != null) {
            $filter_str = $this->prepareWhere($filter);
            $where_str .= ' WHERE ' . $filter_str;
        }
        $query_str .= $where_str . $group_str . $order_str;
        $query = $this->em->createQuery($query_str);


        if ($limit != null) {
            if (isset($limit['start']) && isset($limit['count'])) {
                /** If limit is set */
                $query->setFirstResult($limit['start']);
                $query->setMaxResults($limit['count']);
            } else {
                new CoreExceptions\InvalidLimitException($this->kernel, '');
            }
        }
        /**
         * Prepare & Return Response
         */
        $result = $query->getResult();
        if ($join_needed) {
            $collection = array();
            foreach ($result as $compound_entity) {
                $collection[] = $compound_entity->getNavigation();
            }
            $result = $collection;
        }

        $total_rows = count($result);
        if ($total_rows < 1) {
            $this->response['code'] = 'err.db.ntry.notexist';
            return $this->response;
        }
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $result,
                'total_rows' => $total_rows,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );
        return $this->response;
    }

    /**
     * @name            listNotEditablePages ()
     *                Lists all editable pages | in other words pages with the status "e" and "s".
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->listPages()
     *
     * @param           array $sort_order
     * @param           array $limit
     * @param           integer $site
     *
     * @return          mixed           $response
     */
    public function listNotEditablePages($sort_order, $limit, $site = 1)
    {
        $this->resetResponse();
        if (isset($site)) {
            $filter['site'] = $site;
        }
        $filter = array(
            'status' => array('in' => array('x')),
        );
        $response = $this->listPages($filter, $sort_order, $limit);
        if ($response['error']) {
            return $response;
        }
        $collection = $response['result']['set'];
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $collection[0],
                'total_rows' => 1,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entity.exist',
        );
        return $this->response;

    }

    /**
     * @name            listPages ()
     *                List pages from database based on a variety of conditions.
     *
     * @since            1.0.0
     * @version         1.1.1
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           array $filter Multi-dimensional array
     *
     *                                  Example:
     *                                  $filter = array(
     *                                      'address_type'  => array('in' => array(2,5),
     *                                                               'not_in' => array(4)
     *                                                              ),
     *                                      'member'        => array('in' => array(Member1, Member2)),
     *                                      'tax_id'        => 21312412,
     *                                  );
     *
     *                                  Each array element defines an AND condition.
     *                                  Each array element contains another array with keys
     *                                  in and not_in to include and to exclude data.
     *                                  Each nested array element that is containted in condition states
     *                                  an OR condition.
     *
     * @param           array $sortorder Array
     *                                      'column'            => 'asc|desc'
     * @param           array $limit
     *                                      start
     *                                      count
     *
     * @param           mixed $site id or Site Entity.
     * @param           string $query_str
     *
     * @return          array           $response
     */
    public function listPages($filter = null, $sortorder = null, $limit = null, $site = 1, $query_str = null)
    {
        $this->resetResponse();
        if (!is_array($sortorder) && !is_null($sortorder)) {
            return $this->createException('InvalidSortOrderException', '', 'err.invalid.parameter.sortorder');
        }
        /**
         * Add filter checks to below to set join_needed to true.
         */
        /**         * ************************************************** */
        $order_str = '';
        $where_str = '';
        $group_str = '';
        $filter_str = '';

        /**
         * Start creating the query.
         *
         * Note that if no custom select query is provided we will use the below query as a start.
         */
        if (is_null($query_str)) {
            $query_str = 'SELECT ' . $this->entity['page_localization']['alias'] . ', ' . $this->entity['page']['alias'] . ' ' . ', ' . $this->entity['layout']['alias']
                . ' FROM ' . $this->entity['page_localization']['name'] . ' ' . $this->entity['page_localization']['alias']
                . ' JOIN ' . $this->entity['page_localization']['alias'] . '.page ' . $this->entity['page']['alias']
                . ' JOIN ' . $this->entity['page']['alias'] . '.layout ' . $this->entity['layout']['alias'];
        }

        /**
         * Prepare ORDER BY section of query.
         */
        if ($sortorder != null) {
            foreach ($sortorder as $column => $direction) {
                switch ($column) {
                    case 'id':
                    case 'code':
                    case 'bundle_name':
                        $column = $this->entity['page']['alias'] . '.' . $column;
                        break;
                    case 'title':
                    case 'url_key':
                    case 'meta_title':
                        $column = $this->entity['page_localization']['alias'] . '.' . $column;
                        break;
                }
                $order_str .= ' ' . $column . ' ' . strtoupper($direction) . ', ';
            }
            $order_str = rtrim($order_str, ', ');
            $order_str = ' ORDER BY ' . $order_str . ' ';
        }

        /**
         * Prepare WHERE section of query.
         */
        if ($filter != null) {
            $filter_str = $this->prepareWhere($filter);
            $where_str .= ' WHERE ' . $filter_str;
        }

        $query_str .= $where_str . $group_str . $order_str;

        $query = $this->em->createQuery($query_str);

        $query = $this->addLimit($query, $limit);
        /**
         * Prepare & Return Response
         */
        $result = $query->getResult();
        $pages = array();
        $unique = array();
        foreach ($result as $entry) {
            $id = $entry->getPage()->getId();
            if (!isset($unique[$id])) {
                $pages[] = $entry->getPage();
                $unique[$id] = $entry->getPage();
            }
        }
        unset($unique);
        $total_rows = count($pages);
        if ($total_rows < 1) {
            $this->response['code'] = 'err.db.entry.notexist';
            return $this->response;
        }
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $pages,
                'total_rows' => $total_rows,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );
        return $this->response;
    }

    /**
     * @name            listPagesOfLayout ()
     *                Lists pages that belong to a specifiv layout.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->list_pages()
     *
     * @throws          InvalidEntityException
     *
     * @param           mixed $layout Layout id, entity
     * @param           string $by entity, id, url key or username
     *
     * @return          mixed           $response
     */
    public function listPagesOfLayout($layout, $sort_order, $limit, $site = 1)
    {
        $this->resetResponse();
        if (isset($site)) {
            $filter['site'] = $site;
        }
        if (is_object($layout)) {
            if (!$layout instanceof BundleEntity\Layout) {
                return $this->createException('InvalidParameterException', 'err.invalid.parameter.layout', 'BundleEntity\\Layout');
            }
            $layout = $layout->getId();
        }
        $filter = array(
            'layout' => $layout,
        );
        $response = $this->listPages($filter, $sort_order, $limit);
        if ($response['error']) {
            return $response;
        }
        $collection = $response['result']['set'];
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $collection[0],
                'total_rows' => 1,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );
        return $this->response;
    }

    /**
     * @name            listPagesOfSite ()
     *                Lists pages that that belong to a specific site.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->listPages()
     *
     * @param           integer $site
     * @param           array $sort_order
     * @param           array $limit
     *
     * @return          array           $response
     */
    public function listPagesOfSite($site = 1, $sort_order, $limit)
    {
        $this->resetResponse();
        if (isset($site)) {
            $filter['site'] = $site;
        }
        $response = $this->listPages($filter, $sort_order, $limit);
        if ($response['error']) {
            return $response;
        }
        $collection = $response['result']['set'];
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $collection[0],
                'total_rows' => 1,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );
        return $this->response;
    }

    /**
     * @name            listSupportEditablePages ()
     *                Lists only support staff editable pages | in other words pages with the status "e" and "s".
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->listPages()
     *
     * @param           array $sort_order
     * @param           array $limit
     * @param           integer $site
     *
     * @return          mixed           $response
     */
    public function listSupportEditablePages($sort_order, $limit, $site = 1)
    {
        $this->resetResponse();
        if (isset($site)) {
            $filter['site'] = $site;
        }
        $filter = array(
            'status' => array('in' => array('s')),
        );
        $response = $this->listPages($filter, $sort_order, $limit);
        if ($response['error']) {
            return $response;
        }
        $collection = $response['result']['set'];
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $collection[0],
                'total_rows' => 1,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );
        return $this->response;
    }

    /**
     * @name            listThemes ()
     *                List themes from database based on a variety of conditions.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           array $filter Multi-dimensional array
     *
     *                                  Example:
     *                                  $filter = array(
     *                                      'address_type'  => array('in' => array(2,5),
     *                                                               'not_in' => array(4)
     *                                                              ),
     *                                      'member'        => array('in' => array(Member1, Member2)),
     *                                      'tax_id'        => 21312412,
     *                                  );
     *
     *                                  Each array element defines an AND condition.
     *                                  Each array element contains another array with keys
     *                                  in and not_in to include and to exclude data.
     *                                  Each nested array element that is containted in condition states
     *                                  an OR condition.
     *
     * @param           array $sortorder Array
     *                                      'column'            => 'asc|desc'
     * @param           array $limit
     *                                      start
     *                                      count
     *
     * @param           mixed $site id or Site Entity.
     *
     * @return          array           $response
     */
    public function listThemes($filter = null, $sortorder = null, $limit = null, $site = 1)
    {
        $this->resetResponse();
        if (isset($site)) {
            $filter['site'] = $site;
        }
        if (!is_array($sortorder) && !is_null($sortorder)) {
            return $this->createException('InvalidParameterException', 'err.invalid.parameter.sortorder', '');
        }
        /**
         * Check if it is needed to join two or more tables.
         */
        $join_needed = false;
        if (isset($filter['name']) || isset($filter['name'])) {
            $join_needed = true;
        }

        /**         * ************************************************** */
        $order_str = '';
        $where_str = '';
        $group_str = '';
        /**
         * Start creating the query.
         */
        if ($join_needed) {
            $query_str = 'SELECT DISTINCT ' . $this->entity['theme_localization']['alias']
                . ' FROM ' . $this->entity['theme_localization']['name'] . ' ' . $this->entity['theme_localization']['alias']
                . ' JOIN ' . $this->entity['theme_localization']['alias'] . '.theme ' . $this->entity['theme']['alias'];
//            $group_str = ' GROUP BY '.$this->entity['page']['alias'].'.id';
        } else {
            $query_str = 'SELECT ' . $this->entity['theme']['alias'] . ' FROM ' . $this->entity['theme']['name'] . ' ' . $this->entity['theme']['alias'];
        }
        /**
         * Prepare ORDER BY part of query.
         */
        if ($sortorder != null) {
            foreach ($sortorder as $column => $direction) {
                switch ($column) {
                    case 'name':
                        $order_str .= ' ' . $this->entity['theme_localization']['alias'] . '.' . $column . ' ' . $direction . ', ';
                        break;
                    default:
                        $order_str .= ' ' . $this->entity['theme']['alias'] . '.' . $column . ' ' . $direction . ', ';
                        break;
                }
            }
            $order_str = rtrim($order_str, ', ');
            $order_str = ' ORDER BY ' . $order_str . ' ';
        }
        if ($filter != null) {
            $and = '(';
            foreach ($filter as $column => $value) {
                /** decide which alias to use */
                switch ($column) {
                    case 'name':
                        $alias = $this->entity['theme_localization']['alias'];
                        break;
                    default:
                        $alias = $this->entity['theme']['alias'];
                        break;
                }
                /** If value is array we need to run through all values with a loop */
                if (is_array($value)) {
                    $or = '(';
                    foreach ($value as $key => $sub_value) {
                        if (!is_array($sub_value)) {
                            new CoreExceptions\InvalidFilterException($this->kernel, '');
                            break;
                        }
                        $tmp_sub_value = array();
                        foreach ($sub_value as $item) {
                            if (is_object($item)) {
                                $tmp_sub_value[] = $item->getId();
                            } else {
                                $tmp_sub_value[] = $item;
                            }
                        }
                        if (count($tmp_sub_value) > 0) {
                            $sub_value = $tmp_sub_value;
                        }
                        $or .= ' ' . $alias . '.' . $column;
                        switch ($key) {
                            case 'starts':
                                $or .= ' LIKE \'' . $sub_value[0] . '%\' ';
                                break;
                            case 'ends':
                                $or .= ' LIKE \'%' . $sub_value[0] . '\' ';
                                break;
                            case 'contains':
                                $or .= ' LIKE \'%' . $sub_value[0] . '%\' ';
                                break;
                            case 'in':
                            case 'include':
                                $in = implode(',', $sub_value);
                                $or .= ' IN(' . $in . ') ';
                                break;
                            case 'not_in':
                            case 'exclude':
                                $not_in = implode(',', $sub_value);
                                $or .= ' NOT IN(' . $not_in . ') ';
                                break;
                        }
                        $or .= ') OR ';
                    }
                    $or = rtrim($or, ' OR');
                    $and .= $or;
                } else {
                    if (is_object($value)) {
                        $value = $value->getId();
                    }
                    if (is_numeric($value)) {

                        $and .= ' ' . $alias . '.' . $column . ' = ' . $value;
                    } else {
                        $and .= ' ' . $alias . '.' . $column . ' = \'' . $value . '\'';
                    }
                }
                $and .= ' AND ';
            }
            $and = rtrim($and, ' AND') . ')';
            $where_str .= ' WHERE ' . $and;
        }

        $query_str .= $where_str . $group_str . $order_str;
        $query = $this->em->createQuery($query_str);

        if ($limit != null) {
            if (isset($limit['start']) && isset($limit['count'])) {
                /** If limit is set */
                $query->setFirstResult($limit['start']);
                $query->setMaxResults($limit['count']);
            } else {
                new CoreExceptions\InvalidLimitException($this->kernel, '');
            }
        }
        /**
         * Prepare & Return Response
         */
        $result = $query->getResult();
        if ($join_needed) {
            $collection = array();
            foreach ($result as $compound_entity) {
                $collection[] = $compound_entity->getTheme();
            }
            $result = $collection;
        }

        $total_rows = count($result);
        if ($total_rows < 1) {
            $this->response['code'] = 'err.db.entry.notexist';
            return $this->response;
        }
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $result,
                'total_rows' => $total_rows,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );
        return $this->response;
    }

    /**
     * @name            listThemesOfSite ()
     *                Lists that that belong to a specific site.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->listThemes()
     *
     * @throws          InvalidEntityException
     *
     * @param           integer $site
     * @param           array $sort_order
     * @param           array $limit
     *
     * @return          array           $response
     */
    public function listThemesOfSite($site = 1, $sort_order, $limit)
    {
        $this->resetResponse();
        if (isset($site)) {
            $filter['site'] = $site;
        }
        $response = $this->listThemes($filter, $sort_order, $limit);
        if ($response['error']) {
            return $response;
        }
        $collection = $response['result']['set'];
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $collection[0],
                'total_rows' => 1,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.entry.exist',
        );
        return $this->response;
    }

    /**
     * @name            updatePage ()
     *                Updates single page.
     *
     * @since            1.0.0
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->updatePages()
     *
     * @param           mixed $page Page entity, id, or code.
     * @return          mixed           $response
     */
    public function updatePage($page)
    {
        return $this->updatePages(array($page));
    }

    /**
     * @name            updatePages ()
     *                Updates one or more group details in database.
     *
     * @since            1.0.0
     * @version         1.1.6
     * @author          Can Berkol
     *
     * @use              $this->createException()
     *
     * @param           array $collection Collection of Page entities or array of entity details.
     *
     * @return          array           $response
     */
    public function updatePages($collection)
    {
        $this->resetResponse();
        /** Parameter must be an array */
        if (!is_array($collection)) {
            return $this->createException('InvalidParameter', 'Array', 'err.invalid.parameter.collection');
        }
        $countUpdates = 0;
        $updatedItems = array();
        foreach ($collection as $data) {
            if ($data instanceof BundleEntity\Page) {
                $entity = $data;
                $this->em->persist($entity);
                $updatedItems[] = $entity;
                $countUpdates++;
            } else if (is_object($data)) {
                if (!property_exists($data, 'id') || !is_numeric($data->id)) {
                    return $this->createException('InvalidParameter', 'Each data must contain a valid identifier id, integer', 'err.invalid.parameter.collection');
                }
                if (!property_exists($data, 'site')) {
                    $data->site = 1;
                }
                $response = $this->getPage($data->id, 'id');
                if ($response['error']) {
                    return $this->createException('EntityDoesNotExist', 'Page with id ' . $data->id, 'err.invalid.entity');
                }
                unset($data->id);

                $oldEntity = $response['result']['set'];
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
                                    $localization->setLanguage($response['result']['set']);
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
                            if (!$response['error']) {
                                $oldEntity->$set($response['result']['set']);
                            } else {
                                new CoreExceptions\SiteDoesNotExistException($this->kernel, $value);
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
            } else {
                new CoreExceptions\InvalidDataException($this->kernel);
            }
        }
        if ($countUpdates > 0) {
            $this->em->flush();
        }
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $updatedItems,
                'total_rows' => $countUpdates,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.update.done',
        );
        return $this->response;
    }

    /**
     * @name            updateModule ()
     *                Updates single module.
     *
     * @since            1.0.2
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->updateModules()
     *
     * @param           mixed $module Page entity, id, or code.
     * @return          mixed           $response
     */
    public function updateModule($module)
    {
        return $this->updateModules(array($module));
    }

    /**
     * @name            updateModules ()
     *                Updates one or more group details in database.
     *
     * @since            1.0.2
     * @version         1.1.6
     * @author          Can Berkol
     *
     * @use             $this->createException
     *
     * @param           array $collection Collection of Module entities or array of entity details.
     *
     * @return          array           $response
     */
    public function updateModules($collection)
    {
        $this->resetResponse();
        /** Parameter must be an array */
        if (!is_array($collection)) {
            return $this->createException('InvalidParameter', 'Array', 'err.invalid.parameter.collection');
        }
        $countUpdates = 0;
        $updatedItems = array();
        foreach ($collection as $data) {
            if ($data instanceof BundleEntity\Module) {
                $entity = $data;
                $this->em->persist($entity);
                $updatedItems[] = $entity;
                $countUpdates++;
            } else if (is_object($data)) {
                if (!property_exists($data, 'id') || !is_numeric($data->id)) {
                    return $this->createException('InvalidParameter', 'Each data must contain a valid identifier id, integer', 'err.invalid.parameter.collection');
                }
                if (!property_exists($data, 'site')) {
                    $data->site = 1;
                }
                if (!property_exists($data, 'theme')) {
                    $data->theme = 1;
                }
                $response = $this->getModule($data->id, 'id');
                if ($response['error']) {
                    return $this->createException('EntityDoesNotExist', 'Module with id ' . $data->id, 'err.invalid.entity');
                }
                $oldEntity = $response['result']['set'];
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
                                    $localization = new BundleEntity\ProductCategoryLocalization();
                                    $mlsModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
                                    $response = $mlsModel->getLanguage($langCode, 'iso_code');
                                    $localization->setLanguage($response['result']['set']);
                                    $localization->setCategory($oldEntity);
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
                            if (!$response['error']) {
                                $oldEntity->$set($response['result']['set']);
                            } else {
                                new CoreExceptions\EntityDoesNotExistEception($this->kernel, $value);
                            }
                            unset($response, $fModel);
                            break;
                        case 'site':
                            $sModel = $this->kernel->getContainer()->get('sitemanagement.model');
                            $response = $sModel->getSite($value, 'id');
                            if (!$response['error']) {
                                $oldEntity->$set($response['result']['set']);
                            } else {
                                new CoreExceptions\SiteDoesNotExistException($this->kernel, $value);
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
            } else {
                new CoreExceptions\InvalidDataException($this->kernel);
            }
        }
        if ($countUpdates > 0) {
            $this->em->flush();
        }
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $updatedItems,
                'total_rows' => $countUpdates,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.update.done',
        );
        return $this->response;
    }

    /**
     * @name            updateLayout ()
     *                Updates single layout.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->updateLayouts()
     *
     * @param           mixed $layout Layout entity, id, or code.
     * @return          mixed           $response
     */
    public function updateLayout($layout)
    {
        return $this->updateLayouts(array($layout));
    }

    /**
     * @name            updateLayouts ()
     *                Updates one or more layouts details in database.
     *
     * @since            1.0.1
     * @version         1.1.6
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           array $collection Collection of Layout entities or array of entity details.
     *
     * @return          array           $response
     */
    public function updateLayouts($collection)
    {
        $this->resetResponse();
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
            } else if (is_object($data)) {
                if (!property_exists($data, 'id') || !is_numeric($data->id)) {
                    return $this->createException('InvalidParameter', 'Each data must contain a valid identifier id, integer', 'err.invalid.parameter.collection');
                }
                if (!property_exists($data, 'site')) {
                    $data->site = 1;
                }
                if (!property_exists($data, 'theme')) {
                    $data->theme = 1;
                }
                $response = $this->getLayout($data->id, 'id');
                if ($response['error']) {
                    return $this->createException('EntityDoesNotExist', 'Layout with id ' . $data->id, 'err.invalid.entity');
                }
                $oldEntity = $response['result']['set'];
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
                                    $localization = new BundleEntity\ProductCategoryLocalization();
                                    $mlsModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
                                    $response = $mlsModel->getLanguage($langCode, 'iso_code');
                                    $localization->setLanguage($response['result']['set']);
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
                            $response = $this->getTheme($value, 'id');
                            if (!$response['error']) {
                                $oldEntity->$set($response['result']['set']);
                            } else {
                                new CoreExceptions\EntityDoesNotExistException($this->kernel, $value);
                            }
                            unset($response, $fModel);
                            break;
                        case 'site':
                            $sModel = $this->kernel->getContainer()->get('sitemanagement.model');
                            $response = $sModel->getSite($value, 'id');
                            if (!$response['error']) {
                                $oldEntity->$set($response['result']['set']);
                            } else {
                                new CoreExceptions\SiteDoesNotExistException($this->kernel, $value);
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
            } else {
                new CoreExceptions\InvalidDataException($this->kernel);
            }
        }
        if ($countUpdates > 0) {
            $this->em->flush();
        }
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $updatedItems,
                'total_rows' => $countUpdates,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.update.done',
        );
        return $this->response;
    }

    /**
     * @name            updateModuleLayoutEntry ()
     *                Updates single module layout entry.
     *
     * @since            1.1.5
     * @version         1.1.5
     * @author          Can Berkol
     *
     * @use             $this->updateLayouts()
     *
     * @param           mixed $entry Module Layout entity, id, or code.
     * @return          mixed           $response
     */
    public function updateModuleLayoutEntry($entry)
    {
        return $this->updateModuleLayoutEntries(array($entry));
    }

    /**
     * @name            updateModuleLayoutEntries ()
     *                Updates one or more module layout entries
     *
     * @since            1.1.5
     * @version         1.1.6
     * @author          Can Berkol
     *
     * @use             $this->doesModuleLayoutEntryExist()
     * @use             $this->createException()
     *
     * @param           array $collection Collection of Layout entities or array of entity details.
     *
     * @return          array           $response
     */
    public function updateModuleLayoutEntries($collection)
    {
        $this->resetResponse();
        /** Parameter must be an array */
        if (!is_array($collection)) {
            return $this->createException('InvalidParameter', 'Array', 'err.invalid.parameter.collection');
        }
        $countUpdates = 0;
        $updatedItems = array();
        foreach ($collection as $data) {
            if ($data instanceof BundleEntity\ModulesOfLayout) {
                $entity = $data;
                $this->em->persist($entity);
                $updatedItems[] = $entity;
                $countUpdates++;
            } else if (is_object($data)) {
                if (!property_exists($data, 'id') || !is_numeric($data->id)) {
                    return $this->createException('InvalidParameter', 'Each data must contain a valid identifier id, integer', 'err.invalid.parameter.collection');
                }
                if (!property_exists($data, 'sort_order')) {
                    $data->sort_order = 1;
                }
                $response = $this->getModuleLayoutEntry($data->id, 'id');
                if ($response['error']) {
                    return $this->createException('EntityDoesNotExist', 'ModulesOfLayout with id ' . $data->id, 'err.invalid.entity');
                }
                $oldEntity = $response['result']['set'];
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
                                    $localization = new BundleEntity\ProductCategoryLocalization();
                                    $mlsModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
                                    $response = $mlsModel->getLanguage($langCode, 'iso_code');
                                    $localization->setLanguage($response['result']['set']);
                                    $localization->setModulesOfLayout($oldEntity);
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
                            $response = $this->getPage($value, 'id');
                            if (!$response['error']) {
                                $oldEntity->$set($response['result']['set']);
                            } else {
                                new CoreExceptions\EntityDoesNotExistException($this->kernel, $value);
                            }
                            unset($response, $fModel);
                            break;
                        case 'layout':
                            $response = $this->getLayout($value, 'id');
                            if (!$response['error']) {
                                $oldEntity->$set($response['result']['set']);
                            } else {
                                new CoreExceptions\EntityDoesNotExistException($this->kernel, $value);
                            }
                            unset($response, $fModel);
                            break;
                        case 'module':
                            $response = $this->getModule($value, 'id');
                            if (!$response['error']) {
                                $oldEntity->$set($response['result']['set']);
                            } else {
                                new CoreExceptions\EntityDoesNotExistException($this->kernel, $value);
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
            } else {
                new CoreExceptions\InvalidDataException($this->kernel);
            }
        }
        if ($countUpdates > 0) {
            $this->em->flush();
        }
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $updatedItems,
                'total_rows' => $countUpdates,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.update.done',
        );
        return $this->response;
    }

    /**
     * @name            updateNavigation ()
     *                Updates single navigation.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->updateNavigations()
     *
     * @param           mixed $navigation Navigation entity, id, or code.
     * @return          mixed           $response
     */
    public function updateNavigation($navigation)
    {
        return $this->updateNavigations(array($navigation));
    }

    /**
     * @name            updateNavigations ()
     *                Updates one or more navigation details in database.
     *
     * @since            1.0.1
     * @version         1.1.6
     * @author          Can Berkol
     *
     * @use             $his->createException()
     *
     * @param           array $collection Collection of Navigation entities or array of entity details.
     *
     * @return          array           $response
     */
    public function updateNavigations($collection)
    {
        $this->resetResponse();
        /** Parameter must be an array */
        if (!is_array($collection)) {
            return $this->createException('InvalidParameter', 'Array', 'err.invalid.parameter.collection');
        }
        $countUpdates = 0;
        $updatedItems = array();
        foreach ($collection as $data) {
            if ($data instanceof BundleEntity\Navigation) {
                $entity = $data;
                $this->em->persist($entity);
                $updatedItems[] = $entity;
                $countUpdates++;
            } else if (is_object($data)) {
                if (!property_exists($data, 'id') || !is_numeric($data->id)) {
                    return $this->createException('InvalidParameter', 'Each data must contain a valid identifier id, integer', 'err.invalid.parameter.collection');
                }
                if (property_exists($data, 'date_added')) {
                    unset($data->date_added);
                }
                if (!property_exists($data, 'site')) {
                    $data->site = 1;
                }
                $response = $this->getNavigation($data->id, 'id');
                if ($response['error']) {
                    return $this->createException('EntityDoesNotExist', 'Navigation with id ' . $data->id, 'err.invalid.entity');
                }
                $oldEntity = $response['result']['set'];
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
                                    $localization = new BundleEntity\ProductCategoryLocalization();
                                    $mlsModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
                                    $response = $mlsModel->getLanguage($langCode, 'iso_code');
                                    $localization->setLanguage($response['result']['set']);
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
                            $response = $sModel->getSite($value, 'id');
                            if (!$response['error']) {
                                $oldEntity->$set($response['result']['set']);
                            } else {
                                new CoreExceptions\SiteDoesNotExistException($this->kernel, $value);
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
            } else {
                new CoreExceptions\InvalidDataException($this->kernel);
            }
        }
        if ($countUpdates > 0) {
            $this->em->flush();
        }
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $updatedItems,
                'total_rows' => $countUpdates,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.update.done',
        );
        return $this->response;
    }

    /**
     * @name            updateNavigationItem ()
     *                Updates single navigation_item.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->update_themes()
     *
     * @param           mixed           navigation_item           Navigation entity, id, or code.
     * @return          mixed           $response
     */
    public function updateNavigationItem($navigation_item)
    {
        return $this->updateNavigationItems(array($navigation_item));
    }

    /**
     * @name            updateNavigationItems ()
     *                  Updates one or more navigation item details in database.
     *
     * @since           1.0.1
     * @version         1.1.8
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           array $collection Collection of Navigation entities or array of entity details.
     *
     * @return          array           $response
     */
    public function updateNavigationItems($collection)
    {
        $this->resetResponse();
        /** Parameter must be an array */
        if (!is_array($collection)) {
            return $this->createException('InvalidParameter', 'Array', 'err.invalid.parameter.collection');
        }
        $countUpdates = 0;
        $updatedItems = array();
        foreach ($collection as $data) {
            if ($data instanceof BundleEntity\NavigationItem) {
                $entity = $data;
                $this->em->persist($entity);
                $updatedItems[] = $entity;
                $countUpdates++;
            } else if (is_object($data)) {
                if (!property_exists($data, 'id') || !is_numeric($data->id)) {
                    return $this->createException('InvalidParameter', 'Each data must contain a valid identifier id, integer', 'err.invalid.parameter.collection');
                }
                $response = $this->getNavigationItem($data->id, 'id');
                if ($response['error']) {
                    return $this->createException('EntityDoesNotExist', 'NavigationItem with id ' . $data->id, 'err.invalid.entity');
                }
                $oldEntity = $response['result']['set'];
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
                                    $response = $mlsModel->getLanguage($langCode, 'iso_code');
                                    $localization->setLanguage($response['result']['set']);
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
                            $response = $this->getPage($value, 'id');
                            if (!$response['error']) {
                                $oldEntity->$set($response['result']['set']);
                            } else {
                                new CoreExceptions\EntityDoesNotExistException($this->kernel, $value);
                            }
                            unset($response);
                            break;
                        case 'parent':
                            $response = $this->getNavigation($value, 'id');
                            if (!$response['error']) {
                                $oldEntity->$set($response['result']['set']);
                            } else {
                                new CoreExceptions\EntityDoesNotExistException($this->kernel, $value);
                            }
                            unset($response, $fModel);
                            break;
                        case 'navigation':
                            $response = $this->getNavigation($value, 'id');
                            if (!$response['error']) {
                                $oldEntity->$set($response['result']['set']);
                            } else {
                                new CoreExceptions\EntityDoesNotExistException($this->kernel, $value);
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
            } else {
                new CoreExceptions\InvalidDataException($this->kernel);
            }
        }
        if ($countUpdates > 0) {
            $this->em->flush();
        }
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $updatedItems,
                'total_rows' => $countUpdates,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.update.done',
        );
        return $this->response;
    }

    /**
     * @name            updateTheme ()
     *                Updates single theme.
     *
     * @since            1.0.1
     * @version         1.1.0
     * @author          Can Berkol
     *
     * @use             $this->updateThemes()
     *
     * @param           mixed $theme Theme entity, id, or code.
     * @return          mixed           $response
     */
    public function updateTheme($theme)
    {
        return $this->updateThemes(array($theme));
    }

    /**
     * @name            updateThemes ()
     *                Updates one or more theme details in database.
     *
     * @since           1.0.1
     * @version         1.0.6
     *
     * @author          Can Berkol
     *
     * @use             $this->createException()
     *
     * @param           array $collection Collection of Theme entities or array of entity details.
     *
     * @return          array           $response
     */
    public function updateThemes($collection)
    {
        $this->resetResponse();
        /** Parameter must be an array */
        if (!is_array($collection)) {
            return $this->createException('InvalidParameter', 'Array', 'err.invalid.parameter.collection');
        }
        $countUpdates = 0;
        $updatedItems = array();
        foreach ($collection as $data) {
            if ($data instanceof BundleEntity\Theme) {
                $entity = $data;
                $this->em->persist($entity);
                $updatedItems[] = $entity;
                $countUpdates++;
            } else if (is_object($data)) {
                if (!property_exists($data, 'id') || !is_numeric($data->id)) {
                    return $this->createException('InvalidParameter', 'Each data must contain a valid identifier id, integer', 'err.invalid.parameter.collection');
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
                $response = $this->getTheme($data->id, 'id');
                if ($response['error']) {
                    return $this->createException('EntityDoesNotExist', 'ProductCategory with id ' . $data->id, 'err.invalid.entity');
                }
                $oldEntity = $response['result']['set'];
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
                                    $localization = new BundleEntity\ProductCategoryLocalization();
                                    $mlsModel = $this->kernel->getContainer()->get('multilanguagesupport.model');
                                    $response = $mlsModel->getLanguage($langCode, 'iso_code');
                                    $localization->setLanguage($response['result']['set']);
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
                            if (!$response['error']) {
                                $oldEntity->$set($response['result']['set']);
                            } else {
                                new CoreExceptions\SiteDoesNotExistException($this->kernel, $value);
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
            } else {
                new CoreExceptions\InvalidDataException($this->kernel);
            }
        }
        if ($countUpdates > 0) {
            $this->em->flush();
        }
        /**
         * Prepare & Return Response
         */
        $this->response = array(
            'rowCount' => $this->response['rowCount'],
            'result' => array(
                'set' => $updatedItems,
                'total_rows' => $countUpdates,
                'last_insert_id' => null,
            ),
            'error' => false,
            'code' => 'scc.db.update.done',
        );
        return $this->response;
    }
}

/**
 * Change Log
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
 * **************************************
 * v1.1.3                      Can Berkol
 * 01.12.2013
 * **************************************
 * U listModulesOfPageLayoutsGroupedBySection()
 *
 * **************************************
 * v1.1.2                      Can Berkol
 * 27.11.2013
 * **************************************
 * B getPage() Response return bug on error is fixed.
 * U getPage() Lazyloading JOIN added.
 * U listNavigations() Lazyloading JOIN added.
 *
 * **************************************
 * v1.1.1                      Can Berkol
 * 21.11.2013
 * **************************************
 * A listPages()
 * B listModulesOfPageLayoutsGroupedBySection()
 * D list_pages()
 *
 * **************************************
 * v1.1.0                      Can Berkol
 * 13.11.2013
 * **************************************
 * A getLayoutLocalization()
 * A getModuleLocalization()
 * A getNavigationLocalization()
 * A getNavigationItemLocalization()
 * A getPageLocalization()
 * A getThemeLocalization()
 * A listModulesOfPageLayouts
 * M function names are now camelCase.
 *
 * **************************************
 * v1.0.2                      Can Berkol
 * 10.10.2013
 * **************************************
 * A update_module()
 * A update_modules()
 *
 * **************************************
 * v1.0.1                      Can Berkol
 * 09.09.2013
 * **************************************
 * A delete_layout()
 * A delete_layouts()
 * A delete_module()
 * A delete_modules()
 * A delete_navigation()
 * A delete_navigations()
 * A delete_navigation_item()
 * A delete_navigation_items()
 * A delete_theme()
 * A delete_themes()
 * A does_layout_exist()
 * A does_module_exist()
 * A does_navigation_exists()
 * A does_navigation_item_exists()
 * A does_theme_exists()
 * A getLayout()
 * A getNavigation()
 * A getNavigationItem()
 * A insert_navigation()
 * A insert_navigations()
 * A insert_navigation_item()
 * A insert_navigation_items()
 * A list_control_panel_themes()
 * A list_editable_pages()
 * A list_files_of_page()
 * A list_frontend_themes()
 * A list_items_of_navigation()
 * A list_layouts()
 * A list_layouts_of_theme()
 * A list_layouts_of_site()
 * A list_member_editable_pages()
 * A list_modules()
 * A list_modules_of_theme()
 * A list_modules_of_page_layout()
 * A list_modules_of_site()
 * A list_navigation_items()
 * A list_navigation_items_of_navigation()
 * A list_navigation_items_of_parent()
 * A list_navigations()
 * A list_not_editable_pages()
 * A list_pages_of_layout()
 * A list_pages_of_site()
 * A list_support_editable_pages()
 * A list_themes()
 * A list_themes_of_site()
 * A update_layout()
 * A update_layouts()
 * A update_navigation()
 * A update_navigations()
 * A update_navigation_item()
 * A update_navigation_items()
 * A update_theme()
 * A update_themes()
 *
 * **************************************
 * v1.0.0                      Can Berkol
 * 08.09.2013
 * **************************************
 * A __construct()
 * A __destruct()
 * A delete_page()
 * A delete_pages()
 * A does_page_exist()
 * A getModule()
 * A getPage()
 * A insert_pages()
 * A insert_page()
 * A list_pages()
 * A update_page()
 * A update_pages()
 *
 */