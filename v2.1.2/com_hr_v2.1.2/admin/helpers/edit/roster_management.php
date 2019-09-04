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

$roster_id = $form->getField('roster_id') ? 'roster_id' : ($form->getField('name') ? 'name' : '');
$refcatid = $form->getField('refcatid') ? 'refcatid' : ($form->getField('name') ? 'name' : '');
$employee_id = $form->getField('employee_id') ? 'employee_id' : ($form->getField('name') ? 'name' : '');
$roster_datetime_from = $form->getField('roster_datetime_from') ? 'roster_datetime_from' : ($form->getField('name') ? 'name' : '');
$roster_datetime_to = $form->getField('roster_datetime_to') ? 'roster_datetime_to' : ($form->getField('name') ? 'name' : '');



?>
<div class="form-inline form-inline-header">
	<?php
        
        echo $roster_id ? $form->renderField($roster_id) : '';   
        echo $refcatid ? $form->renderField($refcatid) : '';   
        echo $employee_id ? $form->renderField($employee_id) : '';  
        echo $roster_datetime_from ? $form->renderField($roster_datetime_from) : '';        
	echo $roster_datetime_to ? $form->renderField($roster_datetime_to) : '';     
    

	?>
</div>
