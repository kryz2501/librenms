#!/usr/bin/env php
<?php
/*
* LibreNMS auto generate oxidized group configuration
* this should be outputted to /includes/config_ox_groups.php
* and "require $install_dir . '/includes/config_ox_groups.php';"
* added to the the main config.php to pull this configuration in
*
*/

$init_modules = array();
require realpath(__DIR__ . '/..') . '/includes/init.php';
?>
<?php
$pgroup     = $config['distributed_poller_group'];
echo "<?php" . PHP_EOL;
foreach (dbFetchRows("SELECT `pattern`, REPLACE(name,' ','_') FROM `device_groups` WHERE name NOT LIKE '_Internal_Systems'") as $groups) {
	$groupmatch = str_replace("%devices.sysName ~ \"", "'/^", $groups['pattern']);
	$groupmatch = str_replace("%\"", "/'", $groupmatch);
        $groupmatch = str_replace("&&", "", $groupmatch);
        $groupmatch = str_replace("  ", "", $groupmatch);
	if (empty($groupmatch)) {
		/* donothing */
		}
	else {
		echo "\$config['oxidized']['group']['hostname'][] = array('regex' =>" . $groupmatch . ", 'group' => '" . $groups['REPLACE(name,\' \',\'_\')'] . "');" . PHP_EOL;
	}
}
echo "?>";
