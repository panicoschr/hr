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

$ref_year = $form->getField('ref_year') ? 'ref_year' : ($form->getField('name') ? 'name' : '');
$refcatid = $form->getField('refcatid') ? 'refcatid' : ($form->getField('name') ? 'name' : '');
$plan_id = $form->getField('plan_id') ? 'plan_id' : ($form->getField('name') ? 'name' : '');
$employee_id = $form->getField('employee_id') ? 'employee_id' : ($form->getField('name') ? 'name' : '');
$datetime_from = $form->getField('datetime_from') ? 'datetime_from' : ($form->getField('name') ? 'name' : '');
$datetime_to = $form->getField('datetime_to') ? 'datetime_to' : ($form->getField('name') ? 'name' : '');
$validity_datetime_from = $form->getField('validity_datetime_from') ? 'validity_datetime_from' : ($form->getField('name') ? 'name' : '');
$validity_datetime_to = $form->getField('validity_datetime_to') ? 'validity_datetime_to' : ($form->getField('name') ? 'name' : '');


//$measureunitandid = $form->getField('measureunitandid') ? 'measureunitandid' : ($form->getField('name') ? 'name' : '');
//$measureunit = $form->getField('measureunit') ? 'measureunit' : ($form->getField('name') ? 'name' : '');

//$absence_id = $form->getField('absence_id') ? 'absence_id' : ($form->getField('name') ? 'name' : '');

//$entitlement = $form->getField('entitlement') ? 'entitlement' : ($form->getField('name') ? 'name' : '');



?>
<div class="form-inline form-inline-header">
	<?php
        
        echo $ref_year ? $form->renderField($ref_year) : '';   
        echo $refcatid ? $form->renderField($refcatid) : '';   
        echo $plan_id ? $form->renderField($plan_id) : '';      
        echo $employee_id ? $form->renderField($employee_id) : '';  
        echo $datetime_from ? $form->renderField($datetime_from) : '';        
	echo $datetime_to ? $form->renderField($datetime_to) : '';     
        echo $validity_datetime_from ? $form->renderField($validity_datetime_from) : '';        
	echo $validity_datetime_to ? $form->renderField($validity_datetime_to) : '';             
     //    echo $measureunitandid ? $form->renderField($measureunitandid) : ''; 
     //   echo $absence_id ? $form->renderField($absence_id) : '';      
     ///   echo $measureunit ? $form->renderField($measureunit) : ''; 
  
      //  echo $entitlement ? $form->renderField($entitlement) : '';    
               
   
       
	//echo $form->renderField('alias');
	?>
</div>
