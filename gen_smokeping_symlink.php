#!/usr/bin/env php
<?php
/*
* LibreNMS
*
* when using smokeping generation script to group by device group
* this will generate symlinks from the general "type" folders that 
* librenms will group devices
*/

$init_modules = array();
require realpath(__DIR__ . '/..') . '/includes/init.php';

?>


<?php
$pgroup     = $config['distributed_poller_group'];
foreach (dbFetchRows("SELECT `id`, REPLACE(name,' ','_') FROM `device_groups` WHERE name NOT LIKE '_Internal_Systems'") as $groups) {
	foreach (dbFetchRows("select hostname, type from `devices` inner join `device_group_device` on devices.device_id = device_group_device.device_id where device_group_device.device_group_id = ? AND `ignore` = 0 AND`disabled` = 0", array($groups['id'])) as $devices) {
	$output = shell_exec('ln -s ' . $config['smokeping']['dir'] . $groups['REPLACE(name,\' \',\'_\')'] . '/' . $devices['hostname'] . '.rrd ' . $config['smokeping']['dir'] . $devices['type'] . '/' . $devices['hostname'] . '.rrd');
	echo "<pre>$output</pre>";    	
	}
}
