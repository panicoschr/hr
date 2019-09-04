<?php
/**
 * @package	HR
 * @subpackage	Components
 * @copyright	WWW.MEPRO.CO - All rights reserved.
 * @author	MEPRO SOFTWARE SOLUTIONS
 * @link	http://www.mepro.co
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
*/

// No direct access
defined('_JEXEC') or die;

$form = $displayData->getForm();

$employee_id = $form->getField('employee_id') ? 'employee_id' : ($form->getField('name') ? 'name' : '');
$title_id = $form->getField('title_id') ? 'title_id' : ($form->getField('name') ? 'name' : '');
$datetime_from = $form->getField('datetime_from') ? 'datetime_from' : ($form->getField('name') ? 'name' : '');
$datetime_to = $form->getField('datetime_to') ? 'datetime_to' : ($form->getField('name') ? 'name' : '');

?>
<div class="form-inline form-inline-header">
	<?php
        echo $employee_id ? $form->renderField($employee_id) : '';  
        echo $title_id ? $form->renderField($title_id) : '';          
        echo $datetime_from ? $form->renderField($datetime_from) : '';        
	echo $datetime_to ? $form->renderField($datetime_to) : '';     
       
	?>
</div>
