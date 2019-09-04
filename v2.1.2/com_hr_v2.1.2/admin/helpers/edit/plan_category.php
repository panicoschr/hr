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



$datetime_from = $form->getField('datetime_from') ? 'datetime_from' : ($form->getField('name') ? 'name' : '');
$datetime_to = $form->getField('datetime_to') ? 'datetime_to' : ($form->getField('name') ? 'name' : '');
$refcatid = $form->getField('refcatid') ? 'refcatid' : ($form->getField('name') ? 'name' : '');
$plan_id = $form->getField('plan_id') ? 'plan_id' : ($form->getField('name') ? 'name' : '');


?>
<div class="form-inline form-inline-header">
	<?php
        echo $plan_id ? $form->renderField($plan_id) : '';      
        echo $refcatid ? $form->renderField($refcatid) : '';        
        echo $datetime_from ? $form->renderField($datetime_from) : '';        
	echo $datetime_to ? $form->renderField($datetime_to) : '';           
       
        
	//echo $form->renderField('alias');
	?>
</div>
