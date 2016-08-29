<?php
	require_once 'nzcf.class.php';
	
	class NZCFPersonException extends NZCFException {}
	class NZCFPersonUnknownDataException extends NZCFPersonException {}
	
	
	class Address
	{
		protected $address;
		protected $city;
		protected $postcode;
		protected $contact;
		
		public function __construct()
		{
		}
	}
	
	class Person
	{
		private $data = array( 
			'firstname' => null,
			'lastname'  => null,
			'gender'    => null,
			'contact'   => array(
				'address'=>array(), 
				'phone'=>array() 
			)
		);
		
		public function get_display_name()
		{
			return $this->data['lastname'].', '.$this->data['firstname'];
		}
		
		public function __call( $name, $arguments )
		{
			$action = substr($name, 0, 4);
			$var = substr($name, 4);
			
			switch( $action )
			{
				case 'get_':
					if( isset($this->data[$var]) )
						return $this->data[$var];
					else 
						return null;
					break;
					
				case 'set_':
					switch( $var )
					{
						// Date format attributes
						case 'created':
						case 'dob':
						case 'joined_date':
						case 'left_date':
							if( strtotime( $arguments[0] ) )
								$this->data[$var] = strtotime($arguments[0]);
							elseif( is_null($arguments[0]) )
								$this->data[$var] = null;
							else
								throw new NZCFPersonUnknownDataException('Unknown value for "'.htmlentities($name).'" ("'.htmlentities(json_encode($arguments[0])).'"). Expected date.');
							break;
							
						// Number format attributes (NOT telephone numbers!)
						case 'enabled':
						case 'gender':
						case 'personnel_id':
						case 'access_rights':
							if( is_null($arguments[0]) )
								$this->data[$var] = null;
							elseif( (int)$arguments[0] || $arguments[0] == '0' )
								$this->data[$var] = (int)$arguments[0];
							else
								throw new NZCFPersonUnknownDataException('Bad format for "'.htmlentities($name).'". ("'.htmlentities(json_encode($arguments[0])).'"). Expected int.');
							break;
							
						//Generalised arrays
						case 'units':
							$this->data[$var] = $arguments[0];
							break;
						
						// Usernames are special case
						case 'usernames':
							if( is_array($arguments[0]) )
							{
								$haveprimary = false;
								foreach( $arguments[0] as $username )
								{
									if( !isset($username->username) || !strlen(trim($username->username)) )
										throw new NZCFPersonUnknownDataException('No valid username set. ("'.htmlentities(json_encode($arguments[0])).'").');
									if( !filter_var($username->username, FILTER_VALIDATE_EMAIL) || ( !NZCF_DEBUG && !checkdnsrr(substr($username->username, strpos($username->username, '@')+1), 'MX') ) )
										throw new NZCFPersonUnknownDataException('Username does not appear to be valid email address. ("'.htmlentities(json_encode($username->username)).'").');
									if( isset($username->primary) && (bool)$username->primary )
										$haveprimary = true;
								}
								if( !$haveprimary )
									throw new NZCFPersonUnknownDataException('No primary username set. ("'.htmlentities(json_encode($arguments[0])).'"). Expected int.');
								else
									$this->data[$var] = $arguments[0];
							} else
								throw new NZCFPersonUnknownDataException('Bad format for "'.htmlentities($name).'". ("'.htmlentities(json_encode($arguments[0])).'"). Expected int.');
							break;
							
						// As are personnel notes
						case 'notes':
							if( !is_array($arguments[0]) || !array_key_exists('allergies', $arguments[0])  || !array_key_exists( 'medical_conditions', $arguments[0]) || !array_key_exists('medicinal_reactions', $arguments[0]) || !array_key_exists('dietary_restrictions', $arguments[0]) || !array_key_exists('other_notes', $arguments[0]) )
								throw new NZCFPersonUnknownDataException('Bad format for "'.htmlentities($name).'". ("'.htmlentities(json_encode($arguments[0])).'"). Expected int.');
							else
								$this->data[$var] = $arguments[0];
							break;
							
						// Escape all other strings, avoid XSS/deliberate HTML output hacking etc.
						default:
							$this->data[$var] = htmlentities(json_encode($arguments[0]));
					}
					
					return $this;
					break;
				default: 
					throw new NZCFPersonUnknownDataException('Unknown action "'.$action.'".');
			}
		}
	}	
	
	class User extends Person
	{
		public function __construct( $personnel_id = 0 )
		{
			$this->set_created( null )->set_enabled( 0 )->set_password( null )->set_personnel_id( 0 );
			$NZCF = new NZCF();
			
			if( $personnel_id )
			{
				if( $personnel = $NZCF->get_personnel($personnel_id) )
				{
					foreach( $personnel as $key => $val )
						if( !is_null($val) && $key != 'email' && $key != 'joined_date' && $key != 'left_date'   )
						{
							$foo = 'set_'.$key;
							$this->$foo($val);
						}
					$this->set_usernames( $NZCF->get_usernames($personnel_id) );
					
					if( $units = $NZCF->get_units_for_personnel($personnel_id) )
					{
						$foo = array();
						foreach( $units as $unit )
							$foo[] = $unit;
						$this->set_units( $foo );
					}
						
				}
			}
		}
	}	
	
	class NZCFMember extends User
	{
		public function __construct( $personnel_id = 0 )
		{
			$this->set_dob( null )->set_rank( null )->set_joined_date( null )->set_left_date( null )->set_notes(
				array(
					'allergies'            => $this->get_allergies(),
					'medical_conditions'   => $this->get_medical_conditions(),
					'medicinal_reactions'  => null,
					'dietary_restrictions' => null,
					'other_notes'=>null
				)
			);
			parent::__construct($personnel_id);
		}
	}	
	
	
	
?>