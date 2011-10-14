<?php
/**
 * Renders the email for the user send in the registration process
 * @package	VirtueMart
 * @subpackage User
 * @author Max Milbers
 * @author Valérie Isaksen
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: view.html.php 2459 2010-07-02 17:30:23Z milbo $
 */
$li = '<br />';
?>

<html>
    <head>
    </head>
    <body>
<?php
echo JText::sprintf('COM_VIRTUEMART_WELCOME_USER', $this->user->name) . $li;

if (!empty($this->activationLink)) {
    $activationLink = '<a class="default" href="' . JURI::root() . $this->activationLink . '">' . JText::_('COM_VIRTUEMART_LINK_ACTIVATE_ACCOUNT') . '</a>';
}
echo $li;
echo $activationLink . $li;
echo JText::_('COM_VIRTUEMART_REGISTRATION_DATA') . $li;

echo JText::_('COM_VIRTUEMART_YOUR_LOGINAME') . ": " . $this->user->username . $li;
echo JText::_('COM_VIRTUEMART_YOUR_DISPLAYED_NAME') . ": " . $this->user->name . $li;
echo JText::_('COM_VIRTUEMART_YOUR_PASSWORD') . ": " . $this->user->password_clear . $li;
echo $activationLink . $li;
echo JText::_('COM_VIRTUEMART_YOUR_ADDRESS') . ": " . $li;

foreach ($this->userFields['fields'] as $userField) {
    if (!empty($userField['value']) && $userField['type'] != 'delimiter') {
	echo $userField['title'] . ': ' . $userField['value'] . $li;
    }
}

echo $li;

//echo JURI::root() . JRoute::_('index.php?option=com_virtuemart&view=user', $this->useXHTML, $this->useSSL) . $li;
//Multi-X
//echo JURI::root().JRoute::_('index.php?option=com_virtuemart&view=vendor&virtuemart_vendor_id='.$this->vendor->virtuemart_vendor_id).$li;
?>
    </body>

</html>