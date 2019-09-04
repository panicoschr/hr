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

$measureunitandid = $form->getField('measureunitandid') ? 'measureunitandid' : ($form->getField('name') ? 'name' : '');
$employee_id = $form->getField('employee_id') ? 'employee_id' : ($form->getField('name') ? 'name' : '');
$datetime_from = $form->getField('datetime_from') ? 'datetime_from' : ($form->getField('name') ? 'name' : '');
$datetime_to = $form->getField('datetime_to') ? 'datetime_to' : ($form->getField('name') ? 'name' : '');
$abs_certification = $form->getField('abs_certification') ? 'abs_certification' : ($form->getField('name') ? 'name' : '');
$final_approval_status = $form->getField('final_approval_status') ? 'final_approval_status' : ($form->getField('name') ? 'name' : '');
$charge_status = $form->getField('charge_status') ? 'charge_status' : ($form->getField('name') ? 'name' : '');
$measureunit = $form->getField('measureunit') ? 'measureunit' : ($form->getField('name') ? 'name' : '');
$absence_id = $form->getField('absence_id') ? 'absence_id' : ($form->getField('name') ? 'name' : '');




?>
<div class="form-inline form-inline-header">
	<?php
        echo $employee_id ? $form->renderField($employee_id) : '';        
	echo $measureunitandid ? $form->renderField($measureunitandid) : '';           
        echo $datetime_from ? $form->renderField($datetime_from) : '';
        echo $datetime_to ? $form->renderField($datetime_to) : '';
        echo $abs_certification ? $form->renderField($abs_certification) : '';        
        echo $final_approval_status ? $form->renderField($final_approval_status) : '';
        echo $charge_status ? $form->renderField($charge_status) : '';        
        echo $measureunit ? $form->renderField($measureunit) : '';
        echo $absence_id ? $form->renderField($absence_id) : '';
        
        
	//echo $form->renderField('alias');
	?>
</div>
