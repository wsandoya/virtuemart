<?php

/**
 *
 * List/add/edit/remove Users
 *
 * @package	VirtueMart
 * @subpackage User
 * @author Oscar van Eijk
 * @author Max Milbers
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: view.html.php 5133 2011-12-19 12:02:41Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
jimport('joomla.application.component.view');

// Set to '0' to use tabs i.s.o. sliders
// Might be a config option later on, now just here for testing.
define('__VM_USER_USE_SLIDERS', 0);

/**
 * HTML View class for maintaining the list of users
 *
 * @package	VirtueMart
 * @subpackage User
 * @author Oscar van Eijk
 * @author Max Milbers
 */
class VirtuemartViewVendor extends JView {

    private $_model;
    private $_currentUser = 0;
    private $_cuid = 0;
    private $_userDetails = 0;
    private $_userFieldsModel = 0;
    private $_userInfoID = 0;
    private $_list = 0;

    /**
     * Displays the view, collects needed data for the different layouts
     *
     * Okey I try now a completly new idea.
     * We make a function for every tab and the display is getting the right tabs by an own function
     * putting that in an array and after that we call the preparedataforlayoutBlub
     *
     * @author Oscar van Eijk
     * @author Max Milbers
     */
    function display($tpl = null) {

		$document = JFactory::getDocument();

		$layoutName = $this->getLayout();
		$task = JRequest::getWord('task');



		if (!class_exists('VirtuemartModelVendor'))
			require(JPATH_VM_ADMINISTRATOR . DS . 'models' . DS . 'vendor.php');
		$model = new VirtuemartModelVendor();

		if ($task == 'default' or !JRequest::getInt('virtuemart_vendor_id') ) { // tos or details
			$document->setTitle( JText::_('COM_VIRTUEMART_VENDOR_LIST') );
			$vendors = $model->getVendors();
			$this->assignRef('vendors', $vendors);

		} else { //list
		
			$vendor = $model->getVendor();
			$document->setTitle( JText::_('COM_VIRTUEMART_VENDOR_DETAILS') );
			$this->assignRef('vendor', $vendor);
		}

		parent::display($tpl);
    }

}

//No Closing Tag