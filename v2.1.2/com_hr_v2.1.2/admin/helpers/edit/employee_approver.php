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
$absence_id = $form->getField('absence_id') ? 'absence_id' : ($form->getField('name') ? 'name' : '');
$approver1_id = $form->getField('approver1_id') ? 'approver1_id' : ($form->getField('name') ? 'name' : '');
$approver2_id = $form->getField('approver2_id') ? 'approver2_id' : ($form->getField('name') ? 'name' : '');
$approver3_id = $form->getField('approver3_id') ? 'approver3_id' : ($form->getField('name') ? 'name' : '');
$approver4_id = $form->getField('approver4_id') ? 'approver4_id' : ($form->getField('name') ? 'name' : '');
$approver5_id = $form->getField('approver5_id') ? 'approver5_id' : ($form->getField('name') ? 'name' : '');
?>
<div class="form-inline form-inline-header">
	<?php
        echo $employee_id ? $form->renderField($employee_id) : '';  
        echo $absence_id ? $form->renderField($absence_id) : '';      
        echo $approver1_id ? $form->renderField($approver1_id) : ''; 
        echo $approver2_id ? $form->renderField($approver2_id) : ''; 
        echo $approver3_id ? $form->renderField($approver3_id) : ''; 
        echo $approver4_id ? $form->renderField($approver4_id) : ''; 
        echo $approver5_id ? $form->renderField($approver5_id) : ''; 
        
	//echo $form->renderField('alias');
	?>
</div>
