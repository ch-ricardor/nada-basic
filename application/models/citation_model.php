<?php
class Citation_model extends Model {
 
    public function __construct()
    {
        parent::__construct();
		//$this->output->enable_profiler(TRUE);
    }
	
	//search
    function search($limit = NULL, $offset = NULL,$filter=NULL,$sort_by=NULL,$sort_order=NULL)
    {
		//select columns for output
		$this->db->select('*');

		//allowed_fields
		$db_fields=array('title','subtitle','alt_title','authors','editors','translators','place_publication','publisher','url','place_state');
		
		//set where
		if ($filter)
		{			
			foreach($filter as $f)
			{
				//split keyword by space
				$keywords_array=explode(" ", trim($f['keywords']));
				
				foreach($keywords_array as $keyword)
				{
					if (trim($keyword)!="" && strlen($keyword)>2)
					{
						//search only in the allowed fields
						if (in_array($f['field'],$db_fields))
						{
							$this->db->like($f['field'], trim($keyword)); 
						}
						else if ($f['field']=='all')
						{
							foreach($db_fields as $field)
							{
								$this->db->or_like($field, trim($keyword)); 
							}							
						}
						else if ($f['field']==strtolower('country'))
						{
							//study country search
							$citations=$this->search_citation_by_country(trim($keyword));
							$this->db->where_in('id', $citations);
						}
					}
				}
			}
		}
				
		//set order by
		if ($sort_by!='' && $sort_order!='')
		{
			$this->db->order_by($sort_by, $sort_order); 
		}
		
		//set Limit clause
	  	$this->db->limit($limit, $offset);
		$this->db->from('citations');

        $query= $this->db->get();
		
		if ($query)
		{
			$result=$query->result_array();
			
			//find authors for citations
			foreach($result as $key=>$row)
			{
				$result[$key]['authors']=$this->get_citation_authors($row['id'],'author');
			}
			
			return $result;
		}
		
		
		return FALSE;
    }
	
  	/**
	*
	* Search on citation survey country
	*
	* return arrayy of citation IDs
 	**/
	function search_citation_by_country($keyword=NULL)
	{
		$this->db->select('citationid');
		$this->db->from('survey_citations');
		$this->db->join('surveys', 'survey_citations.sid = surveys.id','inner');
		$this->db->group_by('survey_citations.citationid');
		$this->db->like('surveys.nation',$keyword);
        $query= $this->db->get();

		$output=array();			

		if ($query)
		{
			$result=$query->result_array();
			
			foreach($result as $row)
			{
				$output[]=$row['citationid'];
			}			
		}		

		return $output;
	}
	
    function search_count($filter=NULL)
    {
		//select columns for output
		$this->db->select('id');
		
		//allowed_fields
		$db_fields=array('title','subtitle','alt_title','authors','editors','translators','place_publication','publisher','url');
		
		//set where
		if ($filter)
		{			
			foreach($filter as $f)
			{
				//search only in the allowed fields
				if (in_array($f['field'],$db_fields))
				{
					$this->db->like($f['field'], $f['keywords']); 
				}
				else if ($f['field']=='all')
				{
					foreach($db_fields as $field)
					{
						$this->db->or_like($field, $f['keywords']); 
					}
				}
			}
		}
				
          return $this->db->count_all_results('citations');
    }
	
	function select_single($id)
	{	
		$this->db->where('id', $id); 
		
		//get citation row
		$row=$this->db->get('citations')->row_array();
		
		//get related survey
		$row['related_surveys']=$this->get_related_surveys($id);
		
		/*$row['authors']=unserialize($row['authors']);
		$row['editors']=unserialize($row['editors']);
		$row['translators']=unserialize($row['translators']);
		*/
		$row['authors']=$this->get_citation_authors($id,'author');
		$row['editors']=$this->get_citation_authors($id,'editor');
		$row['translators']=$this->get_citation_authors($id,'translator');
		
		return $row;
	}
		
	/**
	*
	* Get citations by Survey
	*
	**/	
	function get_citations_by_survey($surveyid)
	{
		//get citations by the surveyid
		$this->db->select('citations.*');
		$this->db->from('citations');
		$this->db->join('survey_citations', 'survey_citations.citationid = citations.id');
		$this->db->where('survey_citations.sid',$surveyid);
    	$this->db->order_by("authors, title", "ASC");
		$query=$this->db->get();

		if (!$query)
		{
			return FALSE;
		}
		
		$citations=$query->result_array();
		
		if ($citations)
		{
			foreach($citations as $key=>$row)
			{	
				$citations[$key]['authors']=$this->get_citation_authors($row['id'],'author');
				$citations[$key]['editors']=$this->get_citation_authors($row['id'],'editor');
				$citations[$key]['translators']=$this->get_citation_authors($row['id'],'translator');
			}	
			
			return $citations;
		}
		
		return FALSE;		
	}
	
	
	/**
	* Returns a list of related surveys by citation id
	*
	* @return	array
	*/
	function get_related_surveys($citationid)
	{
		$this->db->select('surveys.id,surveys.surveyid,surveys.titl, surveys.proddate, surveys.nation,surveys.authenty,, surveys.refno, citationid');
		$this->db->join('surveys', 'surveys.id= survey_citations.sid','inner');		
		$this->db->where('citationid', $citationid); 		
		return $this->db->get('survey_citations')->result_array();
	}
	
	/**
	* A list of all surveys that are not already linked to a citation
	*
	*/
	function get_all_surveys($citationid)
	{
		//$remove_list=$this->get_related_surveys($citationid);
		
		$this->db->select('surveys.id,surveys.surveyid,surveys.titl, surveys.proddate, surveys.nation');
		//$this->db->where('citationid!=', $citationid); 		
		return $this->db->get('surveys')->result_array();
	}


	/**
	*	Links the surveys to the citation
	*
	*	@surveys	array of survey id
	*
	*/
	function attach_related_surveys($citationid,$surveys)
	{
		$this->db->where('citationid', $citationid); 
		foreach($surveys as $survey)
		{
			$data=array(
					'sid'=>$survey,
					'citationid'=>$citationid
					);
			
			if (!$this->check_survey_exists($citationid,$survey))
			{
				$result=$this->db->insert('survey_citations', $data); 
				
				if ($result===FALSE)
				{
					return false;
				}
			}
		}
		return TRUE;
	}

	function check_survey_exists($citationid,$surveyid)
	{
		$this->db->where('sid', $surveyid); 
		$this->db->where('citationid', $citationid); 
		return $this->db->get('survey_citations')->result_array();
	}

	/**
	* Delete a single or all related surveys for a citation
	*
	**/
	function delete_related_survey($citationid,$surveyid=NULL)
	{
		if (is_numeric($surveyid))
		{
			$this->db->where('sid', $surveyid); 
		}	
		$this->db->where('citationid', $citationid); 
		return $this->db->delete('survey_citations');
	}
	
	
	function select_all($sort_by='title', $sort_order='ASC')
	{
		$this->db->select('id,title,authors,dcdate,changed');	
		$this->db->order_by($sort_by, $sort_order);
		$this->db->where('published', 1); 
		return $this->db->get('citations')->result_array();
	}
	
	
	/**
	* update 
	*
	*	id			int
	* 	options		array
	**/
	function update($id,$options)
	{
		//allowed fields
		$valid_fields=array(
				'title',
				'subtitle',
				'authors',
				'editors',
				'translators',
				'alt_title',
				'volume',
				'issue',
				'idnumber',
				'edition',
				'changed',
				'created',
				'published',
				'place_publication',
				'place_state',
				'publisher',
				'publication_medium',
				'url',
				'page_from',
				'page_to',
				'data_accessed',
				'pub_day',
				'pub_month',
				'pub_year',
				'organization',
				'ctype',
				'abstract'
			);
		
		$authors=array();
		$editors=array();
		$translators=array();
		
		if (isset($options['authors'])) {
			$authors=$options['authors'];
		}

		if (isset($options['editors'])) {		
			$editors=$options['editors'];
		}
				
		if (isset($options['translators'])) {
			$translators=$options['translators'];
		}
		
		
		//add date modified
		$options['changed']=date("U");
		$options['authors']=$this->authors_to_string($authors);
		$options['translators']=$this->authors_to_string($translators);
		$options['editors']=$this->authors_to_string($editors);
		
		//pk field name
		$key_field='id';
		
		$data=array();
		
		//build update statement
		foreach($options as $key=>$value)
		{
			if (in_array($key,$valid_fields) )
			{
				$data[$key]=$value;
			}
		}
		
		//update db
		$this->db->where($key_field, $id);
		
		$result=$this->db->update('citations', $data); 

		if (!$result)
		{
			throw new MY_Exception($this->db->_error_message());
		}
		
		//add authors
		$this->add_authors($id,$authors,"author");

		//add editors
		$this->add_authors($id,$editors,"editor");
				
		//add translators
		$this->add_authors($id,$translators,"translator");
			
		return $result;		
	}


	function authors_to_string($authors_array)
	{
		if (!is_array($authors_array))
		{
			return FALSE;
		}

		$output='';
		foreach($authors_array as $author)
		{
			$output.=$author['lname']. ' '. $author['initial']. ' '. $author['fname']. ' ';
		}
		
		return substr($output,0,254);
	}

	/**
	*
	* Add citation related authors, editors, translators
	*
	*	@type	author, editor, translator
	**/
	function add_authors($citationid,$authors_array,$type)
	{
		//first remove the existing rows
		$this->db->where('cid', $citationid); 
		$this->db->where('author_type', $type); 
		$deleted=$this->db->delete('citation_authors');	
		
		if (!is_array($authors_array))
		{
			return FALSE;
		}
		
		foreach($authors_array as $author)
		{
			$data=array(
					'fname'=>$author['fname'],
					'lname'=>$author['lname'],
					'initial'=>$author['initial'],
					'author_type'=>$type,
					'cid'=>$citationid,
					);
			$result=$this->db->insert('citation_authors', $data);
			
			if ($result===FALSE)
			{
				return FALSE;
			}
		}
		return TRUE;	
	}

	function get_citation_authors($citationid,$type)
	{
		$this->db->select('*'); 
		$this->db->where('cid', $citationid); 
		$this->db->where('author_type', $type); 
		$query=$this->db->get("citation_authors");
		
		if($query)
		{
			return $query->result_array();
		}
		return FALSE;
	}

	/**
	* add new citation
	*
	* 	options		array
	**/
	function insert($options)
	{
		//allowed fields
		$valid_fields=array(
				'title',
				'subtitle',
				'authors',
				'editors',
				'translators',
				'alt_title',
				'volume',
				'issue',
				'idnumber',
				'edition',
				'changed',
				'created',
				'published',
				'place_publication',
				'place_state',
				'publisher',
				'publication_medium',
				'url',
				'page_from',
				'page_to',
				'data_accessed',
				'pub_day',
				'pub_month',
				'pub_year',
				'organization',
				'ctype',
				'abstract'		
			);

		$authors=array();
		$editors=array();
		$translators=array();
		
		if (isset($options['authors'])) {
			$authors=$options['authors'];
		}

		if (isset($options['editors'])) {		
			$editors=$options['editors'];
		}
				
		if (isset($options['translators'])) {
			$translators=$options['translators'];
		}


		//add date modified and changed
		$options['changed']=date("U");
		$options['created']=date("U");
		
		$options['authors']=$this->authors_to_string($authors);
		$options['translators']=$this->authors_to_string($translators);
		$options['editors']=$this->authors_to_string($editors);
		
							
		$data=array();
		
		//build update statement
		foreach($options as $key=>$value)
		{
			if (in_array($key,$valid_fields) )
			{
				$data[$key]=$value;
			}
		}
		
		//insert record into db
		$result=$this->db->insert('citations', $data); 
		
		if (!$result)
		{
			throw new MY_Exception($this->db->_error_message());
		}
		
		//id for the new citation row
		$id=$this->db->insert_id();
		
		//add authors
		$this->add_authors($id,$authors,"author");

		//add editors
		$this->add_authors($id,$editors,"editor");
				
		//add translators
		$this->add_authors($id,$translators,"translator");
				
		//return the new record
		return $id;
	}
	
	/**
	*
	* Delete citation
	*
	*/
	function delete($id)
	{
		//delete from citations table
		$this->db->where('id', $id); 
		$deleted=$this->db->delete('citations');
		
		if (!$deleted)
		{
			return FALSE;
		}
		
		//delete survey citations table
		$this->db->where('citationid', $id); 
		$deleted=$this->db->delete('survey_citations');		

		if (!$deleted)
		{
			return FALSE;
		}

		return TRUE;
	}
	
	//TODO:REMOVE - moves authors from the citations table to citation_authors
	function move_citation_authors()
	{
		$this->db->select("*");
		$rows=$this->db->get("citations")->result_array();
		
		foreach($rows as $row)
		{
		/*	$data=array(
					'id'=>$row['id'],
					'authors'=>unserialize($row['authors']),
					);*/
			$this->add_authors($row['id'],unserialize($row['authors']),$type='author');
		}
	}
	
	function update_citation_author_array_tostring()
	{
		$this->db->select("*");
		$rows=$this->db->get("citations")->result_array();
		
		
		foreach($rows as $row)
		{
			$authors=unserialize($row['authors']);
			
			if(is_array($authors))
			{
				$author_string=$this->authors_to_string($authors);

				$data=array('authors'=>$author_string);
				$this->db->where('id',$row['id']);	
				$this->db->update('citations', $data);

			}
		}	
	}
}
?>