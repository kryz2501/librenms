#!/usr/bin/env php
<?php
/*
* LibreNMS
*
* Copyright (c) 2015 Søren Friis Rosiak <sorenrosiak@gmail.com>
* This program is free software: you can redistribute it and/or modify it
* under the terms of the GNU General Public License as published by the
* Free Software Foundation, either version 3 of the License, or (at your
* option) any later version.  Please see LICENSE.txt at the top level of
* the source code distribution for details.
*
* Modified by chris E to fix librenms access to smokeping
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
