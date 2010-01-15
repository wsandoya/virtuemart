<?php
/**
 * updatesMigration controller
 *
 * @package	VirtueMart
 * @subpackage updatesMigration
 * @author Max Milbers, RickG
 * @copyright Copyright (c) 2009 VirtueMart Team. All rights reserved.
 */

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

jimport('joomla.application.component.controller');

/**
 * updatesMigration Controller
 *
 * @package    VirtueMart
 * @subpackage updatesMigration
 * @author Max Milbers
 */

class VirtuemartControllerUpdatesMigration extends JController {

    private $installer;

    /**
     * Method to display the view
     *
     * @access	public
     */
    function __construct() {
	parent::__construct();

	$document = JFactory::getDocument();
	$viewType = $document->getType();
	$view = $this->getView('updatesMigration', $viewType);

	// Push a model into the view
	$model = $this->getModel('updatesMigration');
	if (!JError::isError($model)) {
	    $view->setModel($model, true);
	}
    }

    /**
     * Display the upgrade view
     *
     * @author RickG
     */
    function display() {
	parent::display();
    }


    /**
     * Install sample data into the database
     *
     * @author RickG
     */
    function checkForLatestVersion() {
	$model = $this->getModel('updatesMigration');
	JRequest::setVar('latestverison', $model->getLatestVersion());
	JRequest::setVar('view', 'updatesMigration');

	parent::display();
    }


    /**
     * Install sample data into the database
     *
     * @author RickG
     */
    function installSampleData() {
	$model = $this->getModel('updatesMigration');
	$model->installSampleData();

	$msg = JText::_('Sample data installed!!');
	$this->setRedirect('index.php?option=com_virtuemart', $msg);
    }


    /**
     * Install sample data into the database
     *
     * @author RickG
     */
    function userSync() {
	$model = $this->getModel('updatesMigration');
	$model->integrateJoomlaUsers();

	$msg = JText::_('Users Syncronized!');
	$this->setRedirect('index.php?option=com_virtuemart', $msg);
    }


    /**
     * Install sample data into the database
     *
     * @author RickG
     */
    function restoreSystemDefaults() {
	$model = $this->getModel('updatesMigration');
	$model->restoreSystemDefaults();

	$msg = JText::_('System defaults restored!');
	$this->setRedirect('index.php?option=com_virtuemart', $msg);
    }


    /**
     * Remove all the Virtuemart tables from the database.
     *
     * @author RickG
     */
    function deleteVmTables() {
	$model = $this->getModel('updatesMigration');
	if (!$model->removeAllVMTables()) {
	    $this->setRedirect('index.php?option=com_virtuemart', $model->getError());
	}
	else {
	    $this->setRedirect('index.php?option=com_installer');
	}
    }


    function deleteVmData() {
	$model = $this->getModel('updatesMigration');
	if (!$model->removeAllVMData()) {
	    $this->setRedirect('index.php?option=com_virtuemart', $model->getError());
	}
	else {
	    $this->setRedirect('index.php?option=com_virtuemart');
	}
    }
    

    function deleteAll() {
	$this -> installer -> populateVmDatabase("delete_essential.sql");
	$this -> installer -> populateVmDatabase("delete_data.sql");
	$this->setRedirect(JPATH_ADMINISTRATOR, $msg);
    }


    function deleteRestorable() {
	$this -> installer -> populateVmDatabase("delete_restoreable.sql");
	$this->setRedirect(JPATH_ADMINISTRATOR, $msg);
    }




}

?>