<?php

	/*
		Courses => [ Manage | Edit | Update | Add | Insert | Delete | Stats ]
	*/

	$action = isset($_GET['action']) ? $_GET['action'] : 'Manage';

	// If The Page Is Main Page

	if ($action == 'Manage') {

		echo 'Welcome You Are In Manage Courses Page';
		echo '<a href="?action=Insert">Add New Courses +</a>';

	} elseif ($action == 'Add') {

		echo 'Welcome You Are In Add Courses Page';

	} elseif ($action == 'Insert') {

		echo 'Welcome You Are In Insert Courses Page';

	} else {

		echo 'Error There\'s No Page With This Name';

	}