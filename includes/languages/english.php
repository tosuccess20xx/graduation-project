<?php

	function lang($phrase) {

		static $lang = array(

			// Navbar Links

			'HOME_ADMIN' 	=> 'Home',
			'SECTIONS' 		=> 'Sections',
			'COURSES' 		=> 'Courses',
			'TEACHERS' 		=> 'Teachers',
			'STUDENTS' 		=> 'Students',
			'COMMENTS'		=> 'Comments',
			'STATISTICS' 	=> 'Statistics',
			'' => '',
			'' => '',
			'' => '',
			'' => '',
			'' => '',
			'' => ''
		);

		return $lang[$phrase];

	}