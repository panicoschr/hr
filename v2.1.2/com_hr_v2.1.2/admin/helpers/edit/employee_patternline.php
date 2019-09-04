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

$ref_datetime = $form->getField('ref_datetime') ? 'ref_datetime' : ($form->getField('name') ? 'name' : '');
$employee_id = $form->getField('employee_id') ? 'employee_id' : ($form->getField('name') ? 'name' : '');
$no_of_day = $form->getField('no_of_day') ? 'no_of_day' : ($form->getField('name') ? 'name' : '');

?>
<div class="form-inline form-inline-header">
	<?php
        echo $ref_datetime ? $form->renderField($ref_datetime) : '';    
        echo $employee_id ? $form->renderField($employee_id) : '';  
         echo $no_of_day ? $form->renderField($no_of_day) : ''; 
	//echo $form->renderField('alias');
	?>
</div>
