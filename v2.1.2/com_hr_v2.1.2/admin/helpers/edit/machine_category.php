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

$machine_id = $form->getField('machine_id') ? 'machine_id' : ($form->getField('name') ? 'name' : '');
$refcatid = $form->getField('refcatid') ? 'refcatid' : ($form->getField('name') ? 'name' : '');


?>
<div class="form-inline form-inline-header">
	<?php
        echo $machine_id ? $form->renderField($machine_id) : '';  
        echo $refcatid ? $form->renderField($refcatid) : '';          
       
	//echo $form->renderField('alias');
	?>
</div>
