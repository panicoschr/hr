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



$char_from = $form->getField('char_from') ? 'char_from' : ($form->getField('name') ? 'name' : '');
$char_to = $form->getField('char_to') ? 'char_to' : ($form->getField('name') ? 'name' : '');
$absence_id = $form->getField('absence_id') ? 'absence_id' : ($form->getField('name') ? 'name' : '');
$plan_id = $form->getField('plan_id') ? 'plan_id' : ($form->getField('name') ? 'name' : '');
$measureunitofpattern = $form->getField('measureunitofpattern') ? 'measureunitofpattern' : ($form->getField('name') ? 'name' : '');
$measureunitandid = $form->getField('measureunitandid') ? 'measureunitandid' : ($form->getField('name') ? 'name' : '');
$entitlement = $form->getField('entitlement') ? 'entitlement' : ($form->getField('name') ? 'name' : '');
$measureunit = $form->getField('measureunit') ? 'measureunit' : ($form->getField('name') ? 'name' : '');



?>
<div class="form-inline form-inline-header">
	<?php
        echo $plan_id ? $form->renderField($plan_id) : '';      
        echo $measureunitandid ? $form->renderField($measureunitandid) : '';        
        echo $measureunitofpattern ? $form->renderField($measureunitofpattern) : '';     
        echo $char_from ? $form->renderField($char_from) : '';        
	echo $char_to ? $form->renderField($char_to) : '';           
        echo $entitlement ? $form->renderField($entitlement) : ''; 
        echo $absence_id ? $form->renderField($absence_id) : '';   
        echo $measureunit ? $form->renderField($measureunit) : ''; 
        
	//echo $form->renderField('alias');
	?>
</div>
