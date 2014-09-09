<?php
/**
 * sys.en.php
 *
 * This file registers the bundle's system (error and success) messages in English.
 *
 * @vendor      BiberLtd
 * @package		Core\Bundles\MemberManagementBundle
 * @subpackage	Resources
 * @name	    sys.en.php
 *
 * @author		Can Berkol
 *
 * @copyright   Biber Ltd. (www.biberltd.com)
 *
 * @version     1.0.0
 * @date        06.08.2013
 *
 * =============================================================================================================
 * !!! IMPORTANT !!!
 *
 * Depending your environment run the following code after you have modified this file to clear Symfony Cache.
 * Otherwise your changes will NOT take affect!
 *
 * $ sudo -u apache php app/console cache:clear
 * OR
 * $ php app/console cache:clear
 * =============================================================================================================
 * TODOs:
 * None
 */
/** Nested keys are accepted */
return array(
    /** Error messages */
    'err'       => array(
        /** Member Management Model */
        'cmm'   => array(
            'invalid'       =>  array(
                'entity'        => array(
                    'page'      => '"$collection" must contain Page entity.',
                ),
                'parameter'     =>  array(
                    'by'        => 'The "$by" parameter accepts only one of the "entity," "id," or "code" strings.',
                    'collection'=> 'The "$collection" parameter must be an array.',
                    'sortorder' => 'The "$sortorder" parameter must hold and Array of key => value pairs.',
                ),
            ),
            'not_found'         => 'The requested entity cannot be found in database.',
            'unknown'                   => 'An unknown error occured or the MemberManagementModelModel has NOT been created.',
        ),
    ),
    /** Success messages */
    'scc'       => array(
        /** Member Management Model */
        'cmm'   => array(
            'default'       => 'Database transaction is processed successfuly.',
            'deleted'       => 'Selected entries have been succesfully deleted.',
            'inserted'      => array(
                'group_to_members' => 'Groups have been aassociated with the member.',
                'multiple'      => 'The data has been successfully added to the database.',
                'single'        => 'The data has been successfully added to the database.',
                'member_to_groups' => 'Members have been associated with the selected groups.',
            ),
            'updated'       => array(
                'multiple'      => 'The data has been successfully updated.',
                'single'        => 'The data has been successfully updated.',
            ),
            'validated'     => 'Member credentials are correct.',
        ),
    ),
);
/**
 * Change Log
 * **************************************
 * v1.0.0                      Can Berkol
 * 06.08.2013
 * **************************************
 * A err
 * A err.mlsm
 * A err.mlsm.duplicate
 * A err.mlsm.duplicate.language
 * A err.mlsm.invalid
 * A err.mlsm.invalid.entity
 * A err.mlsm.invalid.entity.language
 * A err.mlsm.invalid.parameter
 * A err.mlsm.invalid.parameter.by
 * A err.mlsm.invalid.parameter.languages
 * A err.mlsm.unknown
 * A scc
 * A scc.smm
 * A scc.smm.default
 * A scc.smm.deleted
 * A scc.smm.inserted
 * A scc.smm.inserted.multiple
 * A scc.smm.inserted.single
 * A scc.smm.updated
 * A scc.smm.updated.multiple
 * A scc.smm.updated.single
 */