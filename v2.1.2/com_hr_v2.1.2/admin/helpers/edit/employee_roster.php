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
$datetime_from = $form->getField('datetime_from') ? 'datetime_from' : ($form->getField('name') ? 'name' : '');
$datetime_to = $form->getField('datetime_to') ? 'datetime_to' : ($form->getField('name') ? 'name' : '');
$work_status = $form->getField('work_status') ? 'work_status' : ($form->getField('name') ? 'name' : '');
$no_of_day = $form->getField('no_of_day') ? 'no_of_day' : ($form->getField('name') ? 'name' : '');

?>
<div class="form-inline form-inline-header">
	<?php
        echo $employee_id ? $form->renderField($employee_id) : '';  
        echo $datetime_from ? $form->renderField($datetime_from) : '';        
	echo $datetime_to ? $form->renderField($datetime_to) : '';     
        echo $work_status ? $form->renderField($work_status) : '';        
	echo $no_of_day ? $form->renderField($no_of_day) : '';             
    
       
	//echo $form->renderField('alias');
	?>
</div>
