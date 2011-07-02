<?php
/**
*
* Description
*
* @package	VirtueMart
* @subpackage
* @author
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id$
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
jimport( 'joomla.application.component.view');

/**
 * HTML View class for the VirtueMart Component
 *
 * @package		VirtueMart
 * @author
 */
class VirtuemartViewProduct extends JView {

	function display($tpl = null) {

		$this->loadHelper('customhandler');

		$filter = JRequest::getVar('q', false);
		$type = JRequest::getVar('type', false);
		$id = JRequest::getInt('id', false);
		$row = JRequest::getInt('row', false);

		$db = JFactory::getDBO();
		/* Get the task */
		if ($type=='relatedproducts') {
			$query = "SELECT virtuemart_product_id AS id, CONCAT(product_name, '::', product_sku) AS value
				FROM #__virtuemart_products";
			if ($filter) $query .= " WHERE product_name LIKE '%".$filter."%' limit 0,50";
				$db->setQuery($query);
				echo json_encode($db->loadObjectList());
				return;
		} else if ($type=='product') {

			$query = 'SELECT * FROM `#__virtuemart_customs` WHERE field_type ="R" ';
			$db->setQuery($query);
			$customs = $db->loadObject();

			$query = "SELECT virtuemart_product_id AS id, CONCAT(product_name, '::', product_sku) AS value
				FROM #__virtuemart_products WHERE virtuemart_product_id =".$id;
			$db->setQuery($query);

			$field = $db->loadObject();
			$html = array ();
			$display = VmCustomHandler::inputType($field->id,'R',0,0,$row,0);
			$cartIcone= 'icon-16-default-off.png';
			$html[] = '<tr>
				 <td>'.JText::_('COM_VIRTUEMART_RELATED_PRODUCTS').'</td>
				 <td>'.$display.'
				 </td>
				 <td>'.JText::_('COM_VIRTUEMART_RELATED_PRODUCTS').'
					<input type="hidden" value="R" name="field['.$row.'][field_type]" />
					<input type="hidden" value="'.$customs->virtuemart_custom_id.'" name="field['.$row.'][virtuemart_custom_id]" />
					<input type="hidden" value="'.$field->id.'" name="field['.$row.'][custom_value]" />
					<input type="hidden" value="0" name="field['.$row.'][admin_only]" />
				 </td>
				 <td><img src="components/com_virtuemart/assets/images/icon_16/'.$cartIcone.'" width="16" height="16" border="0" /></td>
				 <td></td>
				</tr>';
			$json['value'] = $html;
			$json['ok'] = $field->id ;
		}else if ($type=='relatedcategories') {
			$query = "SELECT virtuemart_category_id AS id, CONCAT(category_name, '::', virtuemart_category_id) AS value
				FROM #__virtuemart_categories ";
			if ($filter) $query .= " WHERE category_name LIKE '%".$filter."%' limit 0,50";
			$db->setQuery($query);
			if ($result = $db->loadObjectList() ) echo json_encode($result);
			else echo $db->_sql;
			return;

		} else if ($type=='category') {

			$query = 'SELECT * FROM `#__virtuemart_customs` WHERE field_type = "Z" ';
			$db->setQuery($query);
			$customs = $db->loadObject();


			$query = "SELECT virtuemart_category_id AS id, category_name AS value
				FROM #__virtuemart_categories WHERE virtuemart_category_id =".$id;
			$db->setQuery($query);


			$field = $db->loadObject();
			$html = array ();
			$display = VmCustomHandler::inputType($field->id,'Z',0,0,$row,0);
			$cartIcone= 'icon-16-default-off.png';
			$html[] = '<tr>
				 <td>'.JText::_('COM_VIRTUEMART_RELATED_CATEGORIES').'</td>
				 <td>'.$display.'
				 </td>
				 <td>'.JText::_('COM_VIRTUEMART_RELATED_CATEGORIES').'
					<input type="hidden" value="Z" name="field['.$row.'][field_type]" />
					<input type="hidden" value="'.$customs->virtuemart_custom_id.'" name="field['.$row.'][virtuemart_custom_id]" />
					<input type="hidden" value="'.$field->id.'" name="field['.$row.'][custom_value]" />
					<input type="hidden" value="0" name="field['.$row.'][admin_only]" />
				 </td>
				 <td><img src="components/com_virtuemart/assets/images/icon_16/'.$cartIcone.'" width="16" height="16" border="0" /></td>
				 <td></td>
				</tr>';
			$json['value'] = $html;
			$json['ok'] = $field->id ;
		} else if ($type=='custom') {
			$query = "SELECT CONCAT(virtuemart_custom_id, '|', custom_value, '|', field_type) AS id, CONCAT(custom_title, '::', custom_tip) AS value
				FROM #__virtuemart_customs";
			if ($filter) $query .= " WHERE custom_title LIKE '%".$filter."%' limit 0,50";
			$db->setQuery($query);
			$json['value'] = $db->loadObjectList();
			$json['ok'] = 1 ;
		} else if ($type=='customfield') {
			$fieldTypes= VmCustomHandler::getField_types() ;

			$query = "SELECT * FROM #__virtuemart_customs
			WHERE `field_type`!='C' AND (`virtuemart_custom_id`=".$id." or `custom_parent_id`=".$id.")";
			$query .=" order by custom_parent_id asc";
			$db->setQuery($query);
			$rows = $db->loadObjectlist();
			$html = array ();
			foreach ($rows as $field) {
			 $display = VmCustomHandler::inputType($field->custom_value,$field->field_type,$field->is_list,0,$row,$field->is_cart_attribute);
			 if ($field->is_cart_attribute) $cartIcone=  'icon-16-default.png';
			 else  $cartIcone= 'icon-16-default-off.png';
			 $html[] = '<tr>
				 <td>'.$field->custom_title.'</td>
				 <td>'.$display.$field->custom_tip.'
				 </td>
				 <td>'.$fieldTypes[$field->field_type].'
					<input type="hidden" value="'.$field->field_type .'" name="field['.$row.'][field_type]" />
					<input type="hidden" value="'.$field->virtuemart_custom_id.'" name="field['.$row.'][virtuemart_custom_id]" />
					<input type="hidden" value="'.$field->admin_only.'" name="field['.$row.'][admin_only]" />
				 </td>
				 <td><img src="components/com_virtuemart/assets/images/icon_16/'.$cartIcone.'" width="16" height="16" border="0" /></td>
				 <td></td>
				</tr>';
				$row++;
			}
			$json['value'] = $html;
			$json['ok'] = 1 ;
		} else $json['ok'] = 0 ;
		if ( empty($json)) {
			$json['value'] = null;
			$json['ok'] = 1 ;
		}
		echo json_encode($json);


	}

}
// pure php no closing tag
