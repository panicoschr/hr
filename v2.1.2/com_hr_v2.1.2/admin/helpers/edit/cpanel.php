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


$url = $form->getField('url') ? 'url' : ($form->getField('name') ? 'name' : '');
$parent_id = $form->getField('parent_id') ? 'parent_id' : ($form->getField('name') ? 'name' : '');


?>
<div class="form-inline form-inline-header">
	<?php
       
        echo $url ? $form->renderField($url) : '';          
        echo $parent_id ? $form->renderField($parent_id) : '';        
 
       
	?>
</div>
