<?php
class Data_tables_places_model extends CI_Model {

   

    public function __construct()
    {
        parent::__construct();
		//$this->output->enable_profiler(TRUE);
    }


    /**
	 * 
	 * Return geo levels
	 * 
	 * 
	 */
	function get_regions($region_type=null)
	{
		$regions=array(
			'national'=> array(
				'name'=>'National',
				'lvl'=>0
			),
			'state'=> array(
				'name'=>'State',
				'lvl'=>1
			),
			'district'=> array(
				'name'=>'District',
				'lvl'=>2,
				'parent_type'=> 'state'
			),
			'subdistrict'=>array(
				'name'=>'District',
				'lvl'=>3,
				'parent_type'=> 'state'
			),
			'town'=> array(
				'name'=>'District',
				'lvl'=>3,
				'parent_type'=> 'state'
			),
			'village'=> array(
				'name'=>'Village',
				'lvl'=>3,
				'parent_type'=> 'state'
			)
        );
        
        if($region_type){
            if(array_key_exists($region_type,$regions)){
                return $regions[$region_type];
            }
            else{
                throw new Exception("Region type not defined");
            }
        }

		return $regions;
	}



/**
 * 
 * 
 * @region_type - national, state, district, subdistrict, etc
 * @uid - user defined region id
 * @name - region name
 * 
 * to find the parent by uid, need
 * 
 * @parent_type - state, district
 * @parent_uid - parent UID - 001, 00005
 * 
 */
    function create_region($region_type,$uid,$name,$parent_type=NULL,$parent_uid=NULL)
    {    
        $region=$this->get_regions($region_type);

        $options=array(
            'lvl'=>$region['lvl'],
            'uid'=>$uid,
            'name'=>$name,
            'pid'=>0
        );

        //get parent ID
        if(isset($region['parent_type'])){

            if (!$parent_type || !$parent_uid){
                throw new exception("Parent type and UID is required");
            }

            $parent=$this->find_region($parent_type,$parent_uid);

            if($parent){
                $options['pid']=$parent['id'];
            }
            else{
                throw new Exception("Parent not found:: ". $parent_type.':'.$parent_uid);                
            }
        }

        $result=$this->db->insert('data_tables_places',$options);

        if(!$result){
            $error=$this->db->error();
            if(isset($error['message'])){
                throw new Exception($error['message']);
            }else{
                throw new Exception('db-error:: '.$this->db->last_query());
            }
        }

        return $result;
    }


    /**
     * 
     * find region using type and UID
     * 
     */
    function find_region($region_type,$uid)
    {
        $region=$this->get_regions($region_type);

        $this->db->select("*");
        $this->db->where('lvl',$region['lvl']);
        $this->db->where('uid',$uid);
        $result=$this->db->get("data_tables_places")->row_array();

        return $result;
    }


    /**
     * 
     * find region using type and UID
     * 
     */
    function find_regions($region_type,$uid=null,$limit=100)
    {
        $region=$this->get_regions($region_type);

        $this->db->select("id,uid,name");
        $this->db->where('lvl',$region['lvl']);

        if($limit>0){
            $this->db->limit($limit);
        }

        if($uid){
            $this->db->where('uid',$uid);
        }
        
        $result=$this->db->get("data_tables_places")->result_array();

        return $result;
    }


    /////////////////////// states /////////////////////////////////////////


    function create_state($uid,$name)
    {
        if($this->state_exists($uid)){
            throw new Exception("STATE_EXISTS:: ".$uid);
        }

        $options=array(
            'uid'=>$uid,
            'name'=>$name
        );

        $result=$this->db->insert('region_states', $options);
        return $result;
    }

    function state_exists($uid)
    {
        $this->db->select("*");
        $this->db->where("uid",$uid);
        return $this->db->get("region_states")->row_array();
    }


    
    function get_state_by_name($state_name)
    {
        $this->db->select("*");
        $this->db->where("name",$state_name);
        return $this->db->get("region_states")->row_array();
    }


    /////////////////////// districts /////////////////////////////////////////

    function create_district($uid,$state_uid,$name)
    {
        //district?
        $district=$this->district_exists($state_uid,$name);

        if($district){
            return true;
        }

        $options=array(
            'uid'=>$uid,
            'state_uid'=>$state_uid,
            'name'=>$name
        );

        return $this->db->insert('region_districts', $options);
    }


    function district_exists($state_uid,$name)
    {        
        $this->db->select("*");
        $this->db->where("name",$name);
        $this->db->where("state_uid",$state_uid);
        return $this->db->get("region_districts")->row_array();
    }

/////////////////////// sub-districts /////////////////////////////////////////    

    function create_subdistrict($district_uid,$subdistrict_uid, $subdistrict_name)
    {        

        $subdistrict=$this->subdistrict_exists($district_uid, $subdistrict_uid);

        if($subdistrict){
            return false;
        }
                
        $options=array(
            'uid'=>$subdistrict_uid,
            'district_uid'=>$district_uid,
            'name'=>$subdistrict_name
        );

        return $this->db->insert('region_sub_districts', $options);
    }


    function subdistrict_exists($district_uid, $subdistrict_uid)
    {
        $this->db->select("*");
        $this->db->where("uid",$subdistrict_uid);
        $this->db->where("district_uid",$district_uid);
        return $this->db->get("region_sub_districts")->row_array();
    }


/////////////////////// towns /////////////////////////////////////////    

    function create_town($district_uid,$town_uid,$town_name)
    {        
        $town=$this->town_exists($district_uid,$town_uid);

        if($town){
            return false;
        }

        $options=array(
            'uid'=>$town_uid,
            'district_uid'=>$district_uid,
            'name'=>$town_name
        );

        return $this->db->insert('region_towns', $options);
    }


    function town_exists($district_uid,$town_uid)
    {
        $this->db->select("*");
        $this->db->where("uid",$town_uid);
        $this->db->where("district_uid",$district_uid);
        return $this->db->get("region_towns")->row_array();
    }
    
    function town_uid_exists($town_uid)
    {
        $this->db->select("*");
        $this->db->where("uid",$town_uid);        
        return $this->db->get("region_towns")->row_array();
    }

//////////////////////////////////////////////////////////////////////////////////////////////////



    function create_regionxxxxxxxx($region_type,$uid,$name,$state_code=null, $district_code=null, $subdistrict_code=null, $town_code=null)
    {    
        switch(trim(strtolower($region_type)))
        {
            case 'state':
                return $this->create_state($uid,$name);
            break;

            case 'district':            
                return $this->create_district($uid,$state_code,$name);
            break;

            case 'tehsil / sub-district':
            case 'subdistrict':
                return $this->create_subdistrict($district_code,$subdistrict_code, $name);
            break;

            case 'town':
                return $this->create_town($district_code,$uid,$name);
            break;

            default:
                throw new Exception("TYPE-NOT-SUPPORTED::".$region_type);
        }    
    }


    function create_region_x($region_type,$code,$name, $pid)
    {
        $options=array(
            'region_type'=>$region_type,
            'code'=>$code,
            'name'=>$name,
            'pid'=>$pid
        );

        return $this->db->insert('region_codes', $options);
    }


    function region_exists($region_type,$name)
    {
        $this->db->select("*");
        $this->db->where("region_type",$region_type);
        $this->db->where("name",$name);
        return $this->db->get("region_codes")->row_array();
    }


    /*

CREATE TABLE `census_table` (
  `id` int(11) NOT NULL,
  `census` int(11) DEFAULT NULL,
  `scst` varchar(3) DEFAULT NULL,
  `table_id` varchar(45) DEFAULT NULL,
  `geo_level` varchar(45) DEFAULT NULL COMMENT 'national\nstate\ndistrict\nsubdistrict\ntown\nvillage',
  `state_code` varchar(45) DEFAULT NULL,
  `district_code` varchar(45) DEFAULT NULL,
  `town_code` varchar(45) DEFAULT NULL,
  `subdistrict_code` varchar(45) DEFAULT NULL,
  `village_code` varchar(45) DEFAULT NULL,
  `residence` varchar(45) DEFAULT NULL COMMENT 'national\nurban\nrural',
  `value` varchar(45) DEFAULT NULL,
  `feature_1` varchar(45) DEFAULT NULL,
  `feature_2` varchar(45) DEFAULT NULL,
  `feature_3` varchar(45) DEFAULT NULL,
  `feature_4` varchar(45) DEFAULT NULL,
  `feature_5` varchar(45) DEFAULT NULL,
  `feature_6` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    */
    function insert_table($table_id,$options)
    {
        $valid_fields=array(
            'census',
            'scst',
            'geo_level',
            'state_code',
            'district_code',
            'subdistrict_code',
            'town_code',
            'residence',
            'value',
            'feature_1',
            'feature_2',
            'feature_3',
            'feature_4',
            'feature_5'
        );
        /*
        $options['state_name'],
        $options['district_name'],
        $options['subdistrict_name'],
        $options['town_name']
        */

        if(isset($options['features'])){
            $k=1;
            foreach($options['features'] as $feature)
            {
                $options['feature_'.$k]=$feature;
                $k++;
            }
        }

        $data=array();

        foreach($options as $key=>$value){
			if (in_array($key,$valid_fields) ){
				$data[$key]=$value;
			}
        }
        
        $data['table_id']=$table_id;

        return $this->db->insert('census_table', $data);

    }


    



    


    /**
     * 
     * 
     * Return states
     * 
     */
    function get_states()
    {
        $this->db->select("*");
        //$this->db->where("table_id",$table_id);
        return $this->db->get("region_states")->result_array();
    }


    function get_districts($state)
    {
        

        $state_uid=$state;

        if(!is_numeric($state)){
            $state_uid=$this->get_state_by_name($state);

            if(!$state_uid){
                throw new Exception("STATE_NOT_FOUND:: ".$state);
            }

        }

        $this->db->select("*");
        $this->db->where("state_uid",$state_uid);
        return $this->db->get("region_districts")->result_array();
    }


    function get_subdistricts($district_code)
    {        
        $this->db->select("*");
        $this->db->where("district_uid",$district_code);
        return $this->db->get("region_sub_districts")->result_array();
    }

    function get_towns($district_code)
    {        
        $this->db->select("*");
        $this->db->where("district_uid",$district_code);
        return $this->db->get("region_towns")->result_array();
    }
}    