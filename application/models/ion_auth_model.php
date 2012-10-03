<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Ion Auth Model
* 
* Author:  Ben Edmunds
* 		   ben.edmunds@gmail.com
*          @benedmunds
* 
* Added Awesomeness: Phil Sturgeon
* 
* Location: http://github.com/benedmunds/CodeIgniter-Ion-Auth
*          
* Created:  10.01.2009 
* 
* Description:  Modified auth system based on redux_auth with extensive customization.  This is basically what Redux Auth 2 should be.  Original redux license is below.
* Original Author name has been kept but that does not mean that the method has not been modified.
* 
* Requirements: PHP5 or above
* 
*/ 

//  CI 2.0 Compatibility
if(!class_exists('CI_Model')) { class CI_Model extends Model {} }


class Ion_auth_model extends CI_Model
{
	public $errors= array();
	
	/**
	 * Holds an array of tables used
	 *
	 * @var string
	 **/
	public $tables = array();
	
	/**
	 * activation code
	 *
	 * @var string
	 **/
	public $activation_code;
	
	/**
	 * forgotten password key
	 *
	 * @var string
	 **/
	public $forgotten_password_code;
	
	/**
	 * new password
	 *
	 * @var string
	 **/
	public $new_password;
	
	/**
	 * Identity
	 *
	 * @var string
	 **/
	public $identity;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->config('ion_auth');
		$this->load->helper('cookie');
        $this->load->library('session');
		$this->tables  = $this->config->item('tables');
		$this->columns = $this->config->item('columns');
		$this->load->helper('date');
		$this->identity_column = $this->config->item('identity');
	    $this->store_salt      = $this->config->item('store_salt');
	    $this->salt_length     = $this->config->item('salt_length');
	    $this->meta_join       = $this->config->item('join');
	}
	
	/**
	 * Misc functions
	 * 
	 * Hash password : Hashes the password to be stored in the database.
     * Hash password db : This function takes a password and validates it
     * against an entry in the users table.
     * Salt : Generates a random salt value.
	 *
	 * @author Mathew
	 */
	 
	/**
	 * Hashes the password to be stored in the database.
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function hash_password($password, $salt=false)
	{
	    if (empty($password))
	    {
	    	return FALSE;
	    }
	    
		return md5($password);//M1
		
	    if ($this->store_salt && $salt) 
		{
			return  sha1($password . $salt);
		}
		else 
		{			
	    	$salt = $this->salt();
	    	return  $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
		}		
	}
	
	/**
	 * This function takes a password and validates it
     * against an entry in the users table.
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function hash_password_db($identity, $password)
	{
	   if (empty($identity) || empty($password))
	   {
	        return FALSE;
	   }
	   
	   $query = $this->db->select('password')
	   					 ->select('salt')
						 ->where($this->identity_column, $identity)
						 ->where($this->ion_auth->_extra_where)
						 //->limit(1)
						 ->get($this->tables['users']);
            
        $result = $query->row();
        
		if ($query->num_rows() !== 1)
		{
		    return FALSE;
		}

		return md5($password);//M1
		
		/*
		if ($this->store_salt) 
		{
			return sha1($password . $result->salt);
		}
		else 
		{
			$salt = substr($result->password, 0, $this->salt_length);
	
			return $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
		}
		*/
	}
	
	/**
	 * Generates a random salt value.
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function salt()
	{
		return substr(sha1(uniqid(rand(), true)), 0, $this->salt_length);
	}
    
	/**
	 * Activation functions
	 * 
     * Activate : Validates and removes activation code.
     * Deactivae : Updates a users row with an activation code.
	 *
	 * @author Mathew
	 */
	
	/**
	 * activate
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function activate($id, $code = false)
<<<<<<< HEAD
	{	
		$result=FALSE;
			    
=======
	{	    
		$result=FALSE;
		
>>>>>>> 0df80238506a3fa904ffbc982da373dfec446f9c
	    if ($code != false) 
	    {  
		    $query = $this->db->select($this->identity_column)
	        	->where('activation_code', $code)
	        	->limit(1)
	        	->get($this->tables['users']);

			$result = $query->row();

			if ($query->num_rows() !== 1)
			{
				log_message('error', "account activate failed: id=$id, code=$code");
				return FALSE;
			}
		    
			$identity = $result->{$this->identity_column};
			
			$data = array(
				'activation_code' => '',
				'active'          => 1
			);

			$this->db->where($this->ion_auth->_extra_where);
			$result=$this->db->update($this->tables['users'], $data, array($this->identity_column => $identity));
	    }
	    else 
	    {
			if (!$this->ion_auth->is_admin()) 
			{
				log_message('error', "account activation failed using is_admin: $id");
				return false;
			}

			$data = array(
				'activation_code' => '',
				'active' => 1
			);
		   
			$this->db->where($this->ion_auth->_extra_where);
			$result=$this->db->update($this->tables['users'], $data, array('id' => $id));
	    }
		
		return $result;//$this->db->affected_rows() == 1;
	}
	
	
	/**
	 * Deactivate
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function deactivate($id = 0)
	{
	    if (empty($id))
	    {
	        return FALSE;
	    }
	    
		$activation_code       = sha1(md5(microtime()));
		$this->activation_code = $activation_code;
		
		$data = array(
			'activation_code' => $activation_code,
			'active'          => 0
		);
        
		$this->db->where($this->ion_auth->_extra_where);
		$result=$this->db->update($this->tables['users'], $data, array('id' => $id));
<<<<<<< HEAD
		log_message('info', "delog_message('info' query: ".$this->db->last_query());
=======

>>>>>>> 0df80238506a3fa904ffbc982da373dfec446f9c
		return $result;
	}

	/**
	 * change password
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function change_password($identity, $old, $new)
	{
	    $query = $this->db->select('password')
						  ->where($this->identity_column, $identity)
						  ->where($this->ion_auth->_extra_where)
						  ->limit(1)
						  ->get($this->tables['users']);
	    $result = $query->row();

	    $db_password = $result->password; 
	    $old         = $this->hash_password_db($identity, $old);
	    $new         = $this->hash_password($new);

	    if ($db_password === $old)
	    {
	        $data = array('password' => $new);
	        
	        $this->db->where($this->ion_auth->_extra_where);
	        $result=$this->db->update($this->tables['users'], $data, array($this->identity_column => $identity));
	        
	        return $result;
	    }
	    
	    return FALSE;
	}
	
	/**
	 * Checks username
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function username_check($username = '')
	{
	    if (empty($username))
	    {
	        return FALSE;
	    }
		   
	    return $this->db->where('username', $username)
	    	->where($this->ion_auth->_extra_where)
			->count_all_results($this->tables['users']) > 0;
	}
	
	/**
	 * Checks email
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function email_check($email = '')
	{
	    if (empty($email))
	    {
	        return FALSE;
	    }
		   
	    return $this->db->where('email', $email)
	    	->where($this->ion_auth->_extra_where)
			->count_all_results($this->tables['users']) > 0;
	}
	
	/**
	 * Identity check
	 *
	 * @return bool
	 * @author Mathew
	 **/
	protected function identity_check($identity = '')
	{
	    if (empty($identity))
	    {
	        return FALSE;
	    }
	    
	    return $this->db->where($this->identity_column, $identity)
					->count_all_results($this->tables['users']) > 0;
	}

	/**
	 * Insert a forgotten password key.
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function forgotten_password($email = '')
	{
	    if (empty($email))
	    {
	        return FALSE;
	    }
	    
		$key = $this->hash_password(microtime().$email);
			
		$this->forgotten_password_code = $key;
		
		$this->db->where($this->ion_auth->_extra_where);
		   
		$result=$this->db->update($this->tables['users'], array('forgotten_password_code' => $key), array('email' => $email));
		
		return $result;
	}
	
	/**
	 * Forgotten Password Complete
	 *
	 * @return string
	 * @author Mathew
	 **/
	public function forgotten_password_complete($code)
	{
	    if (empty($code))
	    {
	        return FALSE;
	    }
		   
	   	$this->db->where('forgotten_password_code', $code);

	   	if ($this->db->count_all_results($this->tables['users']) > 0) 
        {
        	$password = $this->salt();
		    
            $data = array(
            	'password'                => $this->hash_password($password),
                'forgotten_password_code' => '0',
                'active'                  => 1
            );
		   
           	$this->db->update($this->tables['users'], $data, array('forgotten_password_code' => $code));

            return $password;
        }
        
        return FALSE;
	}

	/**
	 * profile
	 *
	 * @return void
	 * @author Mathew
	 **/
	public function profile($identity = '')
	{ 
	    if (empty($identity))
	    {
	        return FALSE;
	    }
	    
		$this->db->select(array(
	    	$this->tables['users'].'.id',
	    	$this->tables['users'].'.username',
	    	$this->tables['users'].'.password',
	    	$this->tables['users'].'.email',
	    	$this->tables['users'].'.activation_code',
	    	$this->tables['users'].'.forgotten_password_code',
	    	$this->tables['users'].'.ip_address',
	    	$this->tables['users'].'.active',
	    	$this->tables['groups'].'.name AS group_name',
	    	$this->tables['groups'].'.description AS group_description'
	    ));

		if (!empty($this->columns))
        {
            foreach ($this->columns as $field)
            {
                $this->db->select($this->tables['meta'] .'.' . $field);
            }
        }

		$this->db->join($this->tables['meta'], $this->tables['users'].'.id = '.$this->tables['meta'].'.'.$this->meta_join, 'left');
		$this->db->join($this->tables['groups'], $this->tables['users'].'.group_id = '.$this->tables['groups'].'.id', 'left');
		
		if (strlen($identity) === 32)
	    {
	        $this->db->where($this->tables['users'].'.forgotten_password_code', $identity);
	    }
	    else
	    {
	        $this->db->where($this->tables['users'].'.'.$this->identity_column, $identity);
	    }
	    
		$this->db->where($this->ion_auth->_extra_where);
		   
		$this->db->limit(1);
		$i = $this->db->get($this->tables['users']);
		
		return ($i->num_rows > 0) ? $i->row() : FALSE;
	}

	/**
	 * Basic functionality
	 * 
	 * Register
	 * Login
	 *
	 * @author Mathew
	 */
	
	/**
	 * register
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function register($username, $password, $email, $additional_data = false, $group_name = false, $auth_type=NULL)
	{	
	    if (empty($username) || empty($password) || empty($email) )
	    {
	        return FALSE;
	    }
		
		//check if email already exists
		if ($this->email_check($email))
		{
			$this->errors[]='Email already exists';
			return FALSE;	
		}
	    
	    // If username is taken, use username1 or username2, etc.
	    if ($this->identity != 'username') 
	    {
		    for($i = 0; $this->username_check($username); $i++)
		    {
		    	if($i > 0)
		    	{
		    		$username .= $i;
		    	}
		    }
	    }
        // Group ID
        if(empty($group_name))
        {
        	$group_name = $this->config->item('default_group');
        }
        
	    $group_id = $this->db->select('id')
				    	 ->where('name', $group_name)
				    	 ->get($this->tables['groups'])
				    	 ->row()->id;

	    // IP Address
        $ip_address = $this->input->ip_address();
	    
        if ($this->store_salt) 
        {
        	$salt = $this->salt();
        }
        else 
        {
        	$salt = false;
        }
		$password = $this->hash_password($password, $salt);
		
        // Users table.
		$data = array(
			'username'   => $username, 
			'password'   => $password,
  			'email'      => $email,
			'group_id'   => $group_id,
			'ip_address' => $ip_address,
        	'created_on' => now(),
			'last_login' => now(),
			'active'     => 1,
			'authtype'	 => $auth_type
		);
		
		if ($this->store_salt) 
        {
        	$data['salt'] = $salt;
        }
		  
		$this->db->insert($this->tables['users'], array_merge($data, $this->ion_auth->_extra_set));
        
		// Meta table.
		$id = $this->db->insert_id();
		
		$data = array($this->meta_join => $id);
		
		if (!empty($this->columns))
	    {
	        foreach ($this->columns as $input)
	        {
	        	if (is_array($additional_data) && isset($additional_data[$input])) 
	        	{
	        		$data[$input] = $additional_data[$input];	
	        	}
	        	else 
	        	{
	            	$data[$input] = $this->input->post($input);
	        	}
	        }
	    }

		$this->db->insert($this->tables['meta'], $data);
		
		return $id;
	}
	
	/**
	 * login
	 *
	 * @return bool
	 * @author Mathew
	 **/
	public function login($identity, $password, $remember=FALSE)
	{
	    if (empty($identity) || empty($password) || !$this->identity_check($identity))
	    {
	        return FALSE;
	    }

	    $query = $this->db->select('username,email, id, password, group_id')
						  ->where($this->identity_column, $identity)
						  ->where($this->ion_auth->_extra_where)
						  ->where('active', 1)
						  //->limit(1)
						  ->get($this->tables['users']);
						  
        $result = $query->row();

        if ($query->num_rows() == 1)
        {
            $password = $this->hash_password_db($identity, $password);

    		if ($result->password === $password)
    		{
        		$this->update_last_login($result->id);
    		    $this->session->set_userdata('email',  $result->email);
				$this->session->set_userdata('username',  $result->username);
    		    //$this->session->set_userdata('id',  $result->id); //kept for backwards compatibility
    		    $this->session->set_userdata('user_id',  $result->id); //everyone likes to overwrite id so we'll use user_id
    		    $this->session->set_userdata('group_id',  $result->group_id);
				$this->session->set_userdata('site_user_roles', $this->get_user_site_roles($result->id));//array of user roles per site
    		    
    		    $group_row = $this->db->select('name')->where('id', $result->group_id)->get($this->tables['groups'])->row();

    		    $this->session->set_userdata('group',  $group_row->name);
    		    
    		    if ($remember && $this->config->item('remember_users'))
    		    {
    		    	$this->remember_user($result->id);
    		    }
    		    
    		    return TRUE;
    		}
        }
        
		return FALSE;		
	}
	
	
	/**
	 * get_users
	 *
	 * @return object Users
	 * @author Ben Edmunds
	 **/
	public function get_users($group_name = false)
	{
		$this->db->select(array(
	    	$this->tables['users'].'.id',
			$this->tables['users'].'.group_id',
	    	$this->tables['users'].'.username',
	    	$this->tables['users'].'.password',
	    	$this->tables['users'].'.email',
	    	$this->tables['users'].'.activation_code',
	    	$this->tables['users'].'.forgotten_password_code',
	    	$this->tables['users'].'.ip_address',
	    	$this->tables['users'].'.active',
	    	$this->tables['groups'].'.name AS user_group',
	    	$this->tables['groups'].'.description AS group_description'
	    ));
	    
		if (!empty($this->columns))
        {
            foreach ($this->columns as $field)
            {
                $this->db->select($this->tables['meta'].'.'. $field);
            }
        }
        
		$this->db->join($this->tables['meta'], $this->tables['users'].'.id = '.$this->tables['meta'].'.'.$this->meta_join, 'left');
		$this->db->join($this->tables['groups'], $this->tables['users'].'.group_id = '.$this->tables['groups'].'.id', 'left');
		
		if(!empty($group_name))
		{
	    	$this->db->where($this->tables['groups'].'.name', $group_name);
		}
		
		return $this->db->where($this->ion_auth->_extra_where)
					    ->get($this->tables['users']);
	}
	
	/**
	 * get_active_users
	 *
	 * @return object
	 * @author Ben Edmunds
	 **/
	public function get_active_users($group_name = false)
	{
	    $this->db->where($this->tables['users'].'.active', 1);
	    
		return $this->get_users($group_name);
	}
	
	/**
	 * get_inactive_users
	 *
	 * @return object
	 * @author Ben Edmunds
	 **/
	public function get_inactive_users($group_name = false)
	{
	    $this->db->where($this->tables['users'].'.active', 0);
	    
		return $this->get_users($group_name);
	}
	
	/**
	 * get_user
	 *
	 * @return object
	 * @author Phil Sturgeon
	 **/
	public function get_user($id = false)
	{
		//if no id was passed use the current users id
		if (empty($id)) 
		{
			$id = $this->session->userdata('user_id');
		}
		
		$this->db->where($this->tables['users'].'.id', $id);
		//$this->db->limit(1);
		
		return $this->get_users();
	}
	
	/**
	 * get_user_by_email
	 *
	 * @return object
	 * @author Ben Edmunds
	 **/
	public function get_user_by_email($email)
	{
		$this->db->where($this->tables['users'].'.email', $email);
		$this->db->limit(1);
		
		return $this->get_users();
	}

	/**
	 * get_user_by_username
	 *
	 * @return object
	 * @author Mehmood Asghar
	 **/
	public function get_user_by_username($username)
	{
		$this->db->where($this->tables['users'].'.username', $username);
		$this->db->limit(1);
		
		return $this->get_users();
	}
	
	/**
	 * get_newest_users
	 *
	 * @return object
	 * @author Ben Edmunds
	 **/
	public function get_newest_users($limit = 10)
  	{
    	$this->db->order_by($this->tables['users'].'.created_on', 'desc');
    	$this->db->limit($limit);
    	
    	return $this->get_users();
  	}
	
	/**
	 * get_users_group
	 *
	 * @return object
	 * @author Ben Edmunds
	 **/
	public function get_users_group($id=false)
	{
		//if no id was passed use the current users id
		if (!$id)  
		{
			$id = $this->session->userdata('user_id');
		}
		
	    $query = $this->db->select('group_id')
						  ->where('id', $id)
						  ->get($this->tables['users']);

		$user = $query->row();
		
		return $this->db->select('name, description')
						->where('id', $user->group_id)
						->get($this->tables['groups'])
						->row();
	}
	

	/**
	 * update_user
	 *
	 * @return bool
	 * @author Phil Sturgeon
	 **/
	public function update_user($id, $data)
	{
	    $this->db->trans_begin();
	    
		$update_needed=false;
		
	    if (!empty($this->columns))
	    {						
	        foreach ($this->columns as $field)
	        {
	        	if (is_array($data) && isset($data[$field])) 
	        	{
	            	$this->db->set($field, $data[$field]);
	            	unset($data[$field]);
	            	$update_needed=TRUE;
	        	}
	        }
			
	        if ($update_needed)
	        {
	        	// 'user_id' = $id
				$this->db->where($this->meta_join, $id);
	        	$this->db->update($this->tables['meta']);
	        }	
	    }
	    
        if (array_key_exists('username', $data) || array_key_exists('password', $data) || array_key_exists('email', $data)) 
        {
	        if (array_key_exists('password', $data))
			{
			    $data['password'] = $this->hash_password($data['password']);
			}
	
			$this->db->where($this->ion_auth->_extra_where);
	
			$this->db->update($this->tables['users'], $data, array('id' => $id));
        }
            
		if ($this->db->trans_status() === FALSE)
		{
		    $this->db->trans_rollback();
		    return FALSE;
		}
		else
		{
		    $this->db->trans_commit();
		    return TRUE;
		}
	}
	

	/**
	 * delete_user
	 *
	 * @return bool
	 * @author Phil Sturgeon
	 **/
	public function delete_user($id)
	{
		$this->db->trans_begin();
		
		$this->db->delete($this->tables['meta'], array($this->meta_join => $id));
		$this->db->delete($this->tables['users'], array('id' => $id));
		
		if ($this->db->trans_status() === FALSE)
		{
		    $this->db->trans_rollback();
		    return FALSE;
		}
		else
		{
		    $this->db->trans_commit();
		    return TRUE;
		}
	}
	

	/**
	 * update_last_login
	 *
	 * @return bool
	 * @author Ben Edmunds
	 **/
	public function update_last_login($id)
	{
		$this->load->helper('date');

		if (isset($this->ion_auth) && isset($this->ion_auth->_extra_where))
		{
			$this->db->where($this->ion_auth->_extra_where);
		}		
		$this->db->update($this->tables['users'], array('last_login' => now()), array('id' => $id));
		
		return $this->db->affected_rows() == 1;
	}
	

	/**
	 * set_lang
	 *
	 * @return bool
	 * @author Ben Edmunds
	 **/
	public function set_lang($lang = 'en')
	{	
		set_cookie(array(
			'name'   => 'lang_code',
			'value'  => $lang,
			'expire' => $this->config->item('user_expire') + time()
		));
		
		return TRUE;
	}
	
	/**
	 * login_remembed_user
	 *
	 * @return bool
	 * @author Ben Edmunds
	 **/
	public function login_remembered_user()
	{
		//check for valid data
		if (!get_cookie('identity') || !get_cookie('remember_code') || !$this->identity_check(get_cookie('identity'))) 
		{
			return FALSE;
		}
		
		//get the user
		$query = $this->db->select($this->identity_column.', id, group_id,username')
						  ->where($this->identity_column, get_cookie('identity'))
						  ->where('remember_code', get_cookie('remember_code'))
						  ->limit(1)
						  ->get($this->tables['users']);
        
		//if the user was found, sign them in
        if ($query->num_rows() == 1)
        {
        	$user = $query->row();
        	
        	$this->update_last_login($user->id);
        	
            $this->session->set_userdata($this->identity_column,  $user->{$this->identity_column});
    		$this->session->set_userdata('id',  $user->id); //kept for backwards compatibility
    		$this->session->set_userdata('user_id',  $user->id); //everyone likes to overwrite id so we'll use user_id
    		$this->session->set_userdata('group_id',  $user->group_id);
    		$this->session->set_userdata('username',  $user->username);
    		    
    		$group_row = $this->db->select('name')->where('id', $user->group_id)->get($this->tables['groups'])->row();
	    
    		$this->session->set_userdata('group',  $group_row->name);
    		
    		//extend the users cookies if the option is enabled
    		if ($this->config->item('user_extend_on_login'))
    		{
    			$this->remember_user($user->id);
    		}
    		
    		return TRUE;
          }
        
		  return FALSE;		
	}
	
	/**
	 * remember_user
	 *
	 * @return bool
	 * @author Ben Edmunds
	 **/
	private function remember_user($id)
	{
		if (!$id) 
		{			
			return FALSE;
		}
		
		$salt = sha1(md5(microtime()));
		
		$is_updated=$this->db->update($this->tables['users'], array('remember_code' => $salt), array('id' => $id));
		
		if ($is_updated !== FALSE) 
		{
			$user = $this->get_user($id)->row();
			
			$identity = array('name'   => 'identity',
	                   		  'value'  => $user->{$this->identity_column},
	                   		  'expire' => $this->config->item('user_expire'),
	               			 );
			set_cookie($identity); 
			
			$remember_code = array('name'   => 'remember_code',
	                   		  	   'value'  => $salt,
	                   		  	   'expire' => $this->config->item('user_expire'),
	               			 	  );
			set_cookie($remember_code); 
			
			return TRUE;
		}
		
		return FALSE;
	}	
	
	/**
	* Returns a list of all countries in the database
	*
	*/
	function get_all_countries()
	{
		$this->db->select('countryid,name');
		$this->db->order_by("name","asc");
		$query=$this->db->get('countries');
		
		$output=array('-'=>'-');
		
		if ($query)
		{
			$rows=$query->result_array();
			
			foreach($rows as $row)
			{
				$output[$row['name']]=$row['name'];
			}				
		}
		
		return $output;
	}
	
	
	/**
	*
	* Returna an array of email addresses for all administrators
	*
	**/
	function get_admin_emails()
	{
		$this->db->select('username,email');
		$this->db->where('group_id',1);
		$this->db->where('active',1);
		$query=$this->db->get($this->tables['users']);
		
		$emails=array();
		if ($query)
		{
			$rows=$query->result_array();
			
			foreach($rows as $row)
			{
				$emails[]=$row['email'];	
			}
			
			return $emails;
		}
		return FALSE;
	}
	
	/**
	*
	* Return user roles for all or single site
	*
	* TODO: not implemented yet
	*/
	function get_user_site_roles($userid,$siteid=NULL)
	{
		return true;
		
		if ($siteid!=NULL)
		{
			$this->db->where('siteid',$siteid);
		}
		$this->db->where('userid',$userid);
		$query=$this->db->get($this->tables['user_site_roles']);				
		$rows=$query->result_array();
		return $rows;
	}
	
	/**
	*
	* Checks if a usr is admin or not
	*/
	function is_admin($userid)
	{	
		$this->db->select("group_type");
		$this->db->join('user_groups', 'user_groups.id = '.$this->tables['users'].'.group_id', 'left');
		$this->db->where($this->tables['users'].".id",$userid);
		$query=$this->db->get($this->tables['users']);
		
		if ($query)
		{
			$user_arr=$query->row_array();
			
			if ($user_arr)
			{
				if ($user_arr['group_type']=='admin')
				{
					return TRUE;
				}	
			}
		}
		return FALSE;
	}
	
	/**
	*
	* Checks if user has access to a URL
	**/	
	function has_access($userid,$url)
	{
		$this->db->select("user_permissions.*");
		$this->db->join($this->tables['permissions'], $this->tables['permissions'].'.roleid = '.$this->tables['users'].'.group_id', 'left');
		$this->db->where($this->tables['users'].".id",$userid);
		$query=$this->db->get($this->tables['users']);
		
		if ($query)
		{
			//ACL array
			$acl_arr=$query->result_array();
			
			if ($acl_arr)
			{
				//check if the current page url matches with the ACL
				foreach($acl_arr as $acl)
				{
					$acl=(object)$acl;
					
					//match page url
					if (trim($acl->page_url)==trim($url))
					{
						return (bool)$acl->access;
					}
				}			
			}
						
			// Loop through the route array looking for wild-cards
			foreach ($acl_arr as $acl)
			{
				$acl=(object)$acl;
				
				// Convert wild-cards to RegEx
				$key = str_replace('*', '.+', $acl->page_url);
	
				// Does the RegEx match?
				if (preg_match('#^'.$key.'$#', $url))
				{
					return (bool)$acl->access;
				}
			}
			
		}
		return FALSE;
	}
	
	
	/**
	*
	* Test user has permissions to the study
	**/
	function is_study_owner($surveyid,$userid)
	{
		$this->load->model("Catalog_model");
		
		#check what sort of access user has for accessing surveys
		$sql='select users.id,users.group_id,user_groups.group_type,user_groups.repo_access from users
		    	inner join user_groups on users.group_id=user_groups.id
		    	where users.id='.$this->db->escape($userid);
		
		$query=$this->db->query($sql);

		if (!$query)
		{
			return FALSE;
		}
		
		//get rows as array
		$repo_data=$query->result_array();
		
		$group_id=$repo_data[0]["group_id"];
		$group_type=$repo_data[0]["group_type"];
		$repo_access=$repo_data[0]["repo_access"];
		
		//if user is an admin and has UNLIMITED access to repositories, no further checks are needed
		if ($group_type=='admin' && $repo_access=='UNLIMITED')
		{
			return TRUE;
		}
		/*else if ($repo_access!='LIMITED')
		{
			return FALSE;
		}*/

		//For LIMITED access, check permission at the repo level
		
		//get repositoryid for the survey [one survey can only be owned by one repo]
		$repository_obj=$this->Catalog_model->get_repository_by_survey($surveyid);
		$repositoryid=FALSE;
		
		if ($repository_obj!==FALSE)
		{
			$repositoryid=$repository_obj["repositoryid"];
		}
		else
		{
			show_error("No Repository info was found");
		}

		//get a list of repos user has access to
		$user_repos=$this->get_user_repositories($userid);
		
		if (!$user_repos)
		{
			show_error("User has access to no repositories");
		}
				
		//check if user has access to current study's repo
		foreach($user_repos as $repo)
		{
			if ($repo['repositoryid']==$repositoryid)
			{
				return TRUE;
			}
		}
		return FALSE;
	}
	

	/**
	*
	* Return an array of repository IDs for a user
	*
	**/
	function get_user_repositories($userid)
	{
		//check user group type
		$user_group=$this->get_user_group_info($userid);
		
		if (!$user_group)
		{
			show_error("User Group NOT FOUND");
		}
		
		$group_id=$user_group["group_id"];
		$group_type=$user_group["group_type"];
		$repo_access=$user_group["repo_access"];

		//get user repositories
		$this->db->select("repositories.repositoryid as repositoryid,repositories.title as title");
		$this->db->join('user_repositories', 'repositories.id= user_repositories.repositoryid','left');
		$this->db->order_by('title'); 

		if ($repo_access!=='UNLIMITED')
		{
			//show user repositories
			$this->db->where("userid",$userid);
		}
				
		$query=$this->db->get("repositories");
		
		if ($query)
		{
			return $query->result_array();
		}
			
		return FALSE;			
	}
	
	
	/**
	*
	* Returns user group info
	**/
	function get_user_group_info($userid)
	{
		#check what sort of access user has for accessing surveys
		$sql='select users.id,users.group_id,user_groups.group_type,user_groups.repo_access from users
		    	inner join user_groups on users.group_id=user_groups.id
		    	where users.id='.$this->db->escape($userid);
		
		$query=$this->db->query($sql);
	
		if (!$query)
		{
			return FALSE;
		}
		
		return $query->row_array();
	}
	
	/**
	*
	* Returns all user groups
	**/
	function get_user_groups()
	{
		$this->db->select("*");
		$query=$this->db->get($this->tables['groups']);
	
		if (!$query)
		{
			return FALSE;
		}
		
		return $query->result_array();
	}
	
	/**
	*
	* Return all admin user accounts
	**/
	public function get_admin_users($group_name = false)
	{
		$this->db->select(array(
	    	$this->tables['users'].'.id',
			$this->tables['users'].'.group_id',
	    	$this->tables['users'].'.username',
	    	$this->tables['users'].'.password',
	    	$this->tables['users'].'.email',
	    	$this->tables['users'].'.activation_code',
	    	$this->tables['users'].'.forgotten_password_code',
	    	$this->tables['users'].'.ip_address',
	    	$this->tables['users'].'.active',
	    	$this->tables['groups'].'.name AS group_name',
	    	$this->tables['groups'].'.description AS group_description'
	    ));
	    
		if (!empty($this->columns))
        {
            foreach ($this->columns as $field)
            {
                $this->db->select($this->tables['meta'].'.'. $field);
            }
        }
        
		$this->db->join($this->tables['meta'], $this->tables['users'].'.id = '.$this->tables['meta'].'.'.$this->meta_join, 'left');
		$this->db->join($this->tables['groups'], $this->tables['users'].'.group_id = '.$this->tables['groups'].'.id', 'left');
		//get only admin accounts
		$this->db->where($this->tables['groups'].'.name !=', 'user');
		
		if(!empty($group_name))
		{
	    	$this->db->where($this->tables['groups'].'.name', $group_name);
		}
		
		$query=$this->db->where($this->ion_auth->_extra_where)
					    ->get($this->tables['users']);
		if($query)
		{
			return $query->result_array();
		}
		
		return FALSE;	
	}
}
