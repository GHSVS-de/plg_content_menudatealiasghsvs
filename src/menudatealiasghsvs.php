<?php
/**
 * @package plugin.content menudatealiasghsvs for Joomla! ge 3.6.0
 * @version See menudatealiasghsvs.xml
 * @author G@HService Berlin Neukölln, Volkmar Volli Schlothauer
 * @copyright Copyright (C) 2016-2019, G@HService Berlin Neukölln, Volkmar Volli Schlothauer. All rights reserved.
 * @license GNU General Public License version 3 or later; see LICENSE.txt
 * @authorUrl https://www.ghsvs.de
 * @link german description: https://www.ghsvs.de/programmierer-schnipsel/joomla/189-plugin-menuetyp-menuealias-alias-wieder-zeitstempel
 */
?>
<?php
defined('JPATH_BASE') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;

class PlgContentMenuDateAliasGhsvs extends CMSPlugin
{
	protected $app;

	public function onContentBeforeSave($context, $table, $isNew)
	{
		$onlyNewMenuItems = $this->params->get('onlyNewMenuItems', 1);

		if (
			$this->app->isClient('administrator')
			&& version_compare(JVERSION, '3.6.0', 'ge')
			&& $context === 'com_menus.item'
			&& ($isNew || !$onlyNewMenuItems)
			&& !$table->alias
			&& $table->type === 'alias'

			// Some more against paranoia at the moment.
			&& !$table->component_id
			&& strpos($table->params, '"aliasoptions":"')
		){
			$table->alias = Factory::getDate()->format('Y-m-d-H-i-s');
		}
	}
}
