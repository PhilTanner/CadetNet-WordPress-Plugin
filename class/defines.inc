	if( $_SERVER['HTTP_HOST'] == '49sqn.philtanner.com') {
		define( 'WP_NZCF_UA_DEBUG', 					0 );
	} else {
		define( 'WP_NZCF_UA_DEBUG', 					1 );
	}
	
	define( 'WP_NZCF_UA_VERSION',						'0.01' );
	
	/* Location/Regional settings */
	setlocale(LC_ALL, 		'en_NZ.UTF-8');
	define( 'WP_NZCF_UA_TZ_DEFAULT', 'Pacific/Auckland' );
	
	date_default_timezone_set(WP_NZCF_UA_TZ_DEFAULT);
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
		setlocale(LC_ALL, 'enz'); // http://msdn.microsoft.com/en-us/library/cdax410z%28v=vs.90%29.aspx
	}
	
	/*
		Permissions structure will be held as a bitmask, with the following meaning behind the bits 
		(Read vertically downwards from right):
		
		64 32 16 8 4  2 1
		^  ^  ^  ^ ^  ^ ^
		G  A  U  F O  E V
		l  r  n  l w  d i
		o  e  i  i n  i e
		b  a  t  g    t w
		o  a     h R
		l        t e
		           c
		           o
		           r
		           d
		
		The actual rights will be stored in an array. So, order to give someone Edit permission to their 
		own Personnel records, View to their Unit's NOK and view to the area Activities, set an array like this:
		
		$perms = array( 
			WP_NZCF_UA_PERMISSION_PERSONNEL   => WP_NZCF_UA_PERMISSION_EDIT | WP_NZCF_UA_PERMISSION_OWN_RECORD,
			WP_NZCF_UA_PERMISSION_NEXT_OF_KIN => WP_NZCF_UA_PERMISSION_VIEW | WP_NZCF_UA_PERMISSION_UNIT,
			WP_NZCF_UA_PERMISSION_ACTIVITIES  => WP_NZCF_UA_PERMISSION_VIEW | WP_NZCF_UA_PERMISSION_AREA
		);
		
		And to check if the user has the permission to view their area's NOK records:
		
		$haspermission = ( $perms[ WP_NZCF_UA_PERMISSION_NEXT_OF_KIN ] & WP_NZCF_UA_PERMISSION_VIEW & WP_NZCF_UA_PERMISSION_AREA );
		
		which will return 0 for the above, or >0 for the below:
		
		$haspermission = ( $perms[ WP_NZCF_UA_PERMISSION_NEXT_OF_KIN ] & WP_NZCF_UA_PERMISSION_VIEW & WP_NZCF_UA_PERMISSION_UNIT );
		
		Granting either view or edit permission to a geographic level automatically grants the same permission 
		to the lower levels.
		
	*/
	
	// Translated into code:
	define( 'WP_NZCF_UA_PERMISSION_VIEW',		1 );
	define( 'WP_NZCF_UA_PERMISSION_EDIT',		1 << 1 );
	
	define( 'WP_NZCF_UA_PERMISSION_OWN_RECORD',	1 << 2 );
	define( 'WP_NZCF_UA_PERMISSION_FLIGHT',		1 << 3 );
	define( 'WP_NZCF_UA_PERMISSION_UNIT',		1 << 4 );
	define( 'WP_NZCF_UA_PERMISSION_AREA',		1 << 5 );
	define( 'WP_NZCF_UA_PERMISSION_GLOBAL',		1 << 6 );
	
	define( 'WP_NZCF_UA_PERMISSION_PERSONNEL',		1 << 7 );
	define( 'WP_NZCF_UA_PERMISSION_NEXT_OF_KIN',		1 << 8 );
	define( 'WP_NZCF_UA_PERMISSION_ATTENDANCE',		1 << 9 );
	define( 'WP_NZCF_UA_PERMISSION_ACTIVITIES',		1 << 10 );
	define( 'WP_NZCF_UA_PERMISSION_FINANCE',		1 << 11 );
	define( 'WP_NZCF_UA_PERMISSION_LOGISTICS',		1 << 12 );
	define( 'WP_NZCF_UA_PERMISSION_TRAINING',		1 << 13 );
	
	define( 'WP_NZCF_UA_PERMISSION_ASSIGN_USER_RIGHTS',		1 << 14 );
	
	// Give admin everything we can think of in the future (max value of MySQL mediumint unsigned field access_rights).
	define( 'NZCF_USER_LEVEL_ADMIN',			(1 << 24) - 1);
	
	
	
	/* Rank level permission setttings */		
	define( 'NZCF_USER_LEVEL_USER',			  WP_NZCF_UA_PERMISSION_VIEW | WP_NZCF_UA_PERMISSION_OWN_RECORD | WP_NZCF_UA_PERMISSION_PERSONNEL | WP_NZCF_UA_PERMISSION_NEXT_OF_KIN | WP_NZCF_UA_PERMISSION_ATTENDANCE | WP_NZCF_UA_PERMISSION_ACTIVITIES | WP_NZCF_UA_PERMISSION_FINANCE | WP_NZCF_UA_PERMISSION_LOGISTICS | WP_NZCF_UA_PERMISSION_TRAINING );
	define( 'NZCF_USER_LEVEL_CADET',			  NZCF_PERMISSION_TRAINING_VIEW | NZCF_PERMISSION_ACTIVITIES_VIEW );
	define( 'NZCF_USER_LEVEL_JNCO', 				NZCF_USER_LEVEL_CADET | NZCF_PERMISSION_ATTENDANCE_VIEW );
	define( 'NZCF_USER_LEVEL_SNCO', 				NZCF_USER_LEVEL_JNCO | NZCF_PERMISSION_PERSONNEL_VIEW | NZCF_PERMISSION_LOCATIONS_VIEW);
	define( 'NZCF_USER_LEVEL_SUPOFF', 			NZCF_USER_LEVEL_SNCO | NZCF_PERMISSION_FINANCE_VIEW );
	define( 'NZCF_USER_LEVEL_OFFICER', 			NZCF_USER_LEVEL_SUPOFF | NZCF_PERMISSION_FINANCE_VIEW | NZCF_PERMISSION_STORES_VIEW | NZCF_PERMISSION_SYSTEM_VIEW );
	
	/* Specific Officer roles */
	define( 'NZCF_USER_LEVEL_ADJUTANT', 			NZCF_USER_LEVEL_OFFICER | NZCF_PERMISSION_PERSONNEL_EDIT | NZCF_PERMISSION_ATTENDANCE_EDIT | NZCF_PERMISSION_ACTIVITIES_EDIT | NZCF_PERMISSION_FINANCE_EDIT | NZCF_PERMISSION_LOCATIONS_EDIT | NZCF_PERMISSION_ACTIVITY_TYPE_EDIT);
	define( 'NZCF_USER_LEVEL_STORES', 			NZCF_USER_LEVEL_OFFICER | NZCF_PERMISSION_STORES_EDIT );
	define( 'NZCF_USER_LEVEL_TRAINING', 			NZCF_USER_LEVEL_OFFICER | NZCF_PERMISSION_LOCATIONS_EDIT | NZCF_PERMISSION_ACTIVITIES_EDIT | NZCF_PERMISSION_ACTIVITY_TYPE_EDIT | NZCF_PERMISSION_TRAINING_EDIT);
	define( 'NZCF_USER_LEVEL_CUCDR', 			NZCF_USER_LEVEL_OFFICER | NZCF_PERMISSION_PERSONNEL_EDIT | NZCF_PERMISSION_ACTIVITY_TYPE_EDIT );
	
	/* Unit Support Committee roles */
	define( 'NZCF_USER_LEVEL_USC', 				NZCF_USER_LEVEL_OFFICER & ~NZCF_PERMISSION_STORES_VIEW & ~NZCF_PERMISSION_LOCATIONS_VIEW);
	define( 'NZCF_USER_LEVEL_TREASURER',			NZCF_USER_LEVEL_USC | NZCF_PERMISSION_FINANCE_EDIT );
	
	define( 'NZCF_USER_LEVEL_EMRG_CONTACT', 		NZCF_PERMISSION_PERSONNEL_VIEW | NZCF_PERMISSION_ACTIVITIES_VIEW | NZCF_PERMISSION_LOCATIONS_VIEW );
	
	
	define( 'WP_NZCF_UA_ATTENDANCE_PRESENT',				0 );
	define( 'WP_NZCF_UA_ATTENDANCE_PRESENT_FIRST_HALF',	1 );
	define( 'WP_NZCF_UA_ATTENDANCE_PRESENT_SECOND_HALF',	2 );
	define( 'WP_NZCF_UA_ATTENDANCE_ON_LEAVE',				3 );
	define( 'WP_NZCF_UA_ATTENDANCE_ABSENT_WITHOUT_LEAVE',	4 );

	define( 'WP_NZCF_UA_ACTIVITY_RECOGNISED',			0 );
	define( 'WP_NZCF_UA_ACTIVITY_AUTHORISED',			1 );

	define( 'WP_NZCF_UA_DRESS_CODE_BLUES',				0 );
	define( 'WP_NZCF_UA_DRESS_CODE_DPM',				1 );
	define( 'WP_NZCF_UA_DRESS_CODE_BLUES_AND_DPM',			2 );
	define( 'WP_NZCF_UA_DRESS_CODE_MUFTI',			3 );
	
	define( 'WP_NZCF_UA_NOK_TYPE_MOTHER',				0 );
	define( 'WP_NZCF_UA_NOK_TYPE_FATHER',				1 );
	define( 'WP_NZCF_UA_NOK_TYPE_STEPMOTHER',			2 );
	define( 'WP_NZCF_UA_NOK_TYPE_STEPFATHER',			3 );
	define( 'WP_NZCF_UA_NOK_TYPE_SPOUSE',				4 );
	define( 'WP_NZCF_UA_NOK_TYPE_SIBLING',				5 );
	define( 'WP_NZCF_UA_NOK_TYPE_DOMPTNR',				6 );
	define( 'WP_NZCF_UA_NOK_TYPE_OTHER',				7 );
	define( 'WP_NZCF_UA_NOK_TYPE_GRANDMOTHER',			8 );
	define( 'WP_NZCF_UA_NOK_TYPE_GRANDFATHER',			9 );
	define( 'WP_NZCF_UA_NOK_TYPE_UNCLE',				10 );
	define( 'WP_NZCF_UA_NOK_TYPE_AUNT',				11 );
	
	// New Zealand Cadet Forces, as a bitmask - used for lesson training
	define( 'WP_NZCF_UA_NZCF_ATC', 		1 );
	define( 'WP_NZCF_UA_NZCF_CC',		1 << 1 );
	define( 'WP_NZCF_UA_NZCF_SC',		1 << 2 );
	define( 'WP_NZCF_UA_CORPS_ALL',	NZCF_CORPS_ATC | NZCF_CORPS_CC | NZCF_CORPS_SCC );
	
	define( 'WP_NZCF_UA_ADMIN_AREA_NORTH', 	1 );
	define( 'WP_NZCF_UA_ADMIN_AREA_CENTRAL',	1 << 1 );
	define( 'WP_NZCF_UA_ADMIN_AREA_SOUTH',	1 << 2 );
	
	define( 'WP_NZCF_UA_LESSON_LEVEL_ADVANCED', 	1 );
	define( 'WP_NZCF_UA_LESSON_LEVEL_PROFICIENT',	1 << 1 );
	define( 'WP_NZCF_UA_LESSON_LEVEL_BASIC',		1 << 2 );
	
	define( 'WP_NZCF_UA_SESSION_TYPE_CALENDAR',		'activities_calendar' );

	define( 'WP_NZCF_UA_SETTING_MONEYFORMAT_PARENTHESIS', 0 );
	define( 'WP_NZCF_UA_SETTING_MONEYFORMAT_TEXTUAL', 1 );
	
	define( 'WP_NZCF_UA_PAYMENT_TYPE_INVOICE_TERM_FEE', 			0 );
	define( 'WP_NZCF_UA_PAYMENT_TYPE_INVOICE_ACTIVITY_FEE', 		1 );
	define( 'WP_NZCF_UA_PAYMENT_TYPE_INVOICE_OUTSTANDING_MONEY',	2 );
	define( 'WP_NZCF_UA_PAYMENT_TYPE_INVOICE_UNIFORM_DEPOSIT',		3 );
	define( 'WP_NZCF_UA_PAYMENT_TYPE_INVOICE_FUNDRAISING',			4 );
	define( 'WP_NZCF_UA_PAYMENT_TYPE_INVOICE_MISCELLANEOUS',		19 );
	
	define( 'WP_NZCF_UA_PAYMENT_TYPE_RECEIPT_TERM_FEE', 			20 );
	define( 'WP_NZCF_UA_PAYMENT_TYPE_RECEIPT_ACTIVITY_FEE', 		21 );
	define( 'WP_NZCF_UA_PAYMENT_TYPE_RECEIPT_OUTSTANDING_MONEY', 	22 );
	define( 'WP_NZCF_UA_PAYMENT_TYPE_RECEIPT_UNIFORM_DEPOSIT', 		23 );
	define( 'WP_NZCF_UA_PAYMENT_TYPE_RECEIPT_FUNDRAISING',			24 );
	define( 'WP_NZCF_UA_PAYMENT_TYPE_RECEIPT_MISCELLANEOUS',		39 );
	
	
	/* Presentation settings */
	define( 'WP_NZCF_UA_SETTING_DATETIME_INPUT',         "Y-m-d\TH:i");
	define( 'WP_NZCF_UA_SETTING_DATETIME_OUTPUT',         "j M, H:i");
	define( 'WP_NZCF_UA_SETTING_DATE_INPUT',         "Y-m-d");
	define( 'WP_NZCF_UA_SETTING_DATE_OUTPUT',         "j M");
	define( 'WP_NZCF_UA_SETTING_FULL_DISPLAY_NAME',		'CONCAT("RNK, ", `personnel`.`lastname`,", ",`personnel`.`firstname`)' );
	define( 'WP_NZCF_UA_SETTING_DISPLAY_NAME',		'CONCAT(`personnel`.`lastname`,", ",`personnel`.`firstname`)' );
	define( 'WP_NZCF_UA_SETTING_DISPLAY_RANK_SHORTNAME', '( SELECT `rank_shortname` FROM `personnel_rank` INNER JOIN `rank` ON `rank`.`rank_id` = `personnel_rank`.`rank_id` WHERE `personnel_rank`.`personnel_id` = `personnel`.`personnel_id` ORDER BY `date_achieved` DESC LIMIT 1 )');
	
	/* Money settings */
	define( 'WP_NZCF_UA_SETTING_FINANCE_MONEYFORMAT', 	'%(#2.2n' );
	
	
	$translations = array( 
		'attendance_symbols' => array(
			WP_NZCF_UA_ATTENDANCE_PRESENT_FIRST_HALF 			=> '/',
			WP_NZCF_UA_ATTENDANCE_PRESENT_SECOND_HALF			=> '\',
			WP_NZCF_UA_ATTENDANCE_PRESENT						=> 'X',
			WP_NZCF_UA_ATTENDANCE_ON_LEAVE						=> 'L',
			WP_NZCF_UA_ATTENDANCE_ABSENT_WITHOUT_LEAVE			=> 'a'
		), 
		'uniforms' => array(
			WP_NZCF_UA_DRESS_CODE_DPM							= 'DPM',
			WP_NZCF_UA_DRESS_CODE_BLUES						= 'No 6 GP Uniform',
			WP_NZCF_UA_DRESS_CODE_BLUES_AND_DPM				= 'Mixed',
			WP_NZCF_UA_DRESS_CODE_MUFTI						= 'Mufti'
		),
		'payment_types' => array(
			WP_NZCF_UA_PAYMENT_TYPE_INVOICE_TERM_FEE			= 'Term invoice',
			WP_NZCF_UA_PAYMENT_TYPE_INVOICE_ACTIVITY_FEE		= 'Activity invoice',
			WP_NZCF_UA_PAYMENT_TYPE_INVOICE_OUTSTANDING_MONEY	= 'Outstanding money',
			WP_NZCF_UA_PAYMENT_TYPE_INVOICE_UNIFORM_DEPOSIT	= 'Uniform deposit',
			WP_NZCF_UA_PAYMENT_TYPE_INVOICE_FUNDRAISING		= 'Fundraising invoice',
			WP_NZCF_UA_PAYMENT_TYPE_INVOICE_MISCELLANEOUS		= 'Invoice (misc.)',
			
			WP_NZCF_UA_PAYMENT_TYPE_RECEIPT_TERM_FEE			= 'Term fee receipt',
			WP_NZCF_UA_PAYMENT_TYPE_RECEIPT_ACTIVITY_FEE		= 'Activity fee receipt',
			WP_NZCF_UA_PAYMENT_TYPE_RECEIPT_OUTSTANDING_MONEY	= 'Outstanding money payment',
			WP_NZCF_UA_PAYMENT_TYPE_RECEIPT_UNIFORM_DEPOSIT	= 'Uniform deposit payment',
			WP_NZCF_UA_PAYMENT_TYPE_RECEIPT_FUNDRAISING		= 'Fundraising payment',
			WP_NZCF_UA_PAYMENT_TYPE_RECEIPT_MISCELLANEOUS		= 'Payment (misc.)'
		)
	);
