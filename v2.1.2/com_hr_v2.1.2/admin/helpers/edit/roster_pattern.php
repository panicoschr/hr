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
$no_of_day = $form->getField('no_of_day') ? 'no_of_day' : ($form->getField('name') ? 'name' : '');
$time_from_text = $form->getField('time_from_text') ? 'time_from_text' : ($form->getField('name') ? 'name' : '');
$time_to_text = $form->getField('time_to_text') ? 'time_to_text' : ($form->getField('name') ? 'name' : '');
$time_from = $form->getField('time_from') ? 'time_from' : ($form->getField('name') ? 'name' : '');
$time_to = $form->getField('time_to') ? 'time_to' : ($form->getField('name') ? 'name' : '');

$time_from_to_sort = $form->getField('time_from_to_sort') ? 'time_from_to_sort' : ($form->getField('name') ? 'name' : '');
$time_to_to_sort = $form->getField('time_to_to_sort') ? 'time_to_to_sort' : ($form->getField('name') ? 'name' : '');
$plus_no_of_days = $form->getField('plus_no_of_days') ? 'plus_no_of_days' : ($form->getField('name') ? 'name' : '');
$work_status = $form->getField('work_status') ? 'work_status' : ($form->getField('name') ? 'name' : '');



?>
<div class="form-inline form-inline-header">
	<?php
        echo $roster_id ? $form->renderField($roster_id) : '';      
        echo $no_of_day ? $form->renderField($no_of_day) : '';        
        echo $time_from_text ? $form->renderField($time_from_text) : '';        
	echo $time_to_text ? $form->renderField($time_to_text) : '';      
        echo $time_from ? $form->renderField($time_from) : '';        
	echo $time_to ? $form->renderField($time_to) : '';            
        echo $plus_no_of_days ? $form->renderField($plus_no_of_days) : '';   
        echo $work_status ? $form->renderField($work_status) : '';   
        echo $time_from_to_sort ? $form->renderField($time_from_to_sort) : '';   
        echo $time_to_to_sort ? $form->renderField($time_to_to_sort) : '';  
       
        
	//echo $form->renderField('alias');
	?>
</div>
