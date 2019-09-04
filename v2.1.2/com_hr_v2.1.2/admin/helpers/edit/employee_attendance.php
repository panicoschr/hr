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
$log_in = $form->getField('log_in') ? 'log_in' : ($form->getField('name') ? 'name' : '');
$log_out = $form->getField('log_out') ? 'log_out' : ($form->getField('name') ? 'name' : '');

?>
<div class="form-inline form-inline-header">
	<?php
        echo $employee_id ? $form->renderField($employee_id) : '';        
        echo $log_in ? $form->renderField($log_in) : '';    
        echo $log_out ? $form->renderField($log_out) : ''; 
	//echo $form->renderField('alias');
	?>
</div>
