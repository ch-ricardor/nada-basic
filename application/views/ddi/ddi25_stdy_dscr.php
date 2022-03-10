<?php
//var_dump($survey);die();
$ddi=$this->ddi_writer;
?>
  <stdyDscr>
    <citation>
      <titlStmt>
        <titl><?php $ddi->el('study_desc/title_statement/title');?></titl>       
        <subTitl><?php $ddi->el('study_desc/title_statement/sub_title');?></subTitl>
        <altTitl><?php $ddi->el('study_desc/title_statement/alt_title');?></altTitl>
        <parTitl><?php $ddi->el('study_desc/title_statement/translated_title');?></parTitl>
        <IDNo><?php $ddi->el('study_desc/title_statement/idno');?></IDNo>
      </titlStmt>      
      <rspStmt>
        <?php $authoring_entities=$ddi->get_el('study_desc/authoring_entity');?>
        <?php foreach($authoring_entities as $auth_entity):?>
        <AuthEnty affiliation="<?php echo $ddi->attr_val($auth_entity,'affiliation');?>"><?php echo $ddi->el_Val($auth_entity,'name');?></AuthEnty>
        <?php endforeach;?>

        <?php //othID - [email attribute is not supported by DDI] ?>
        <?php $oth_ids=(array)$ddi->get_el('study_desc/oth_id');?>        
        <?php foreach($oth_ids as $oth_id):?>        
        <othId role="<?php echo $ddi->attr_val($oth_id,'role');?>" affiliation="<?php echo $ddi->attr_val($oth_id,'affiliation');?>" email="<?php echo $ddi->attr_val($oth_id,'email');?>">
          <p><?php echo $ddi->el_val($oth_id,'name');?></p>
        </othId>
        <?php endforeach;?>
      </rspStmt>          
      <prodStmt>
        <?php //producers ?>
        <?php $producers=(array)$ddi->get_el('study_desc/production_statement/producers');?>  
        <?php foreach($producers as $producer):?>    
        <producer abbr="<?php echo $ddi->attr_val($producer,'abbreviation');?>" affiliation="<?php echo $ddi->attr_val($producer,'affiliation');?>" role="<?php echo $ddi->attr_val($producer,'role');?>"><?php echo $ddi->el_val($producer,'name');?></producer>
        <?php endforeach;?>
        
        <copyright><?php $ddi->el('study_desc/production_statement/copyright');?></copyright>
        <software version="5.0" date="<?php echo date("Y-m-d");?>">NADA</software>

        <?php //funding agencies ?>
        <?php $fundags=(array)$ddi->get_el('study_desc/production_statement/funding_agencies');?>  
        <?php foreach($fundags as $fundag):?>   
					<fundAg abbr="<?php echo $ddi->attr_val($fundag,'abbreviation');?>" role="<?php echo $ddi->attr_val($fundag,'role');?>"><?php echo $ddi->el_val($fundag,'name');?></fundAg>
        <?php endforeach;?>
        <grantNo><?php $ddi->el('study_desc/production_statement/grant_no');?></grantNo>
      </prodStmt>

      <distStmt>
        <?php //distributor ?>
        <?php $distributors=(array)$ddi->get_el('study_desc/distribution_statement/distributors');?>
        <?php foreach($distributors as $distributor):?>   
          <distrbtr abbr="<?php echo $ddi->attr_val($distributor,'abbreviation');?>" affiliation="<?php echo $ddi->attr_val($distributor,'affiliation');?>" URI="<?php echo $ddi->attr_val($distributor,'uri');?>"><?php echo $ddi->el_val($distributor,'name');?></distrbtr>
        <?php endforeach;?>

        <?php //contacts ?>
        <?php $contacts=(array)$ddi->get_el('study_desc/distribution_statement/contact');?>
        <?php foreach($contacts as $contact):?>   
          <contact affiliation="<?php echo $ddi->attr_val($contact,'affiliation');?>" URI="<?php echo $ddi->attr_val($contact,'uri');?>" email="<?php echo $ddi->attr_val($contact,'email');?>"><?php echo $ddi->el_val($contact,'name');?></contact>
        <?php endforeach;?>
        
        <?php //depositor ?>
        <?php $depositors=(array)$ddi->get_el('study_desc/distribution_statement/depositor');?>
        <?php foreach($depositors as $contact):?>   
          <depositr abbr="<?php echo $ddi->attr_val($contact,'abbreviation');?>"  affiliation="<?php echo $ddi->attr_val($contact,'affiliation');?>"><?php echo $ddi->el_val($contact,'name');?></depositr>
        <?php endforeach;?>
        
        <depDate date="<?php $ddi->el('study_desc/distribution_statement/deposit_date');?>" />
        <distDate date="<?php $ddi->el('study_desc/distribution_statement/distribution_date');?>" />
     </distStmt>

     <serStmt>
        <serName><?php $ddi->el('study_desc/series_statement/series_name');?></serName>
        <serInfo><![CDATA[<?php $ddi->el('study_desc/series_statement/series_info');?>]]></serInfo>
     </serStmt>

     <verStmt>
        <version date="<?php $ddi->el('study_desc/version_statement/version_date');?>" ><?php $ddi->el('study_desc/version_statement/version');?></version>
        <verResp><?php $ddi->el('study_desc/version_statement/version_resp');?></verResp>
        <notes><![CDATA[<?php $ddi->el('study_desc/version_statement/version_notes');?>]]></notes>
     </verStmt>
     
     <biblCit format="<?php $ddi->el('study_desc/bib_citation_format');?>"><![CDATA[<?php $ddi->el('study_desc/bib_citation');?>]]></biblCit>

      <?php //holdings ?>
      <?php $holdings=(array)$ddi->get_el('study_desc/holdings');?>
      <?php foreach($holdings as $holding):?>   
        <holdings location="<?php echo $ddi->attr_val($holding,'location');?>" callno="<?php echo $ddi->attr_val($holding,'callno');?>" URI="<?php echo $ddi->attr_val($holding,'uri');?>"><?php echo $ddi->el_val($holding,'name');?></holdings>
      <?php endforeach;?>
      <notes><![CDATA[<?php $ddi->el('study_desc/study_notes');?>]]></notes>
  </citation>
  
  <?php  /*
  <!-- studyAuthorization - DDI2.5 
    Provides structured information on the agency that authorized the study, the date of authorization, 
    and an authorization statement.
  -->
  <studyAuthorization date="2010-11-04"> 
    <authorizingAgency affiliation="University of Georgia" abbr="HSO">Human Subjects Office</authorizingAgency> 
    <authorizationStatement>Statement of authorization issued bu OUHS on 2010-11-04</authorizationStatement>
  </studyAuthorization>
  */ ?>
  <stdyInfo>
    <?php /*
     <!-- studyBudget - ddi2.5 
     Describe the budget of the project in as much detail as needed. Use XHTML structure elements to identify 
     discrete pieces of information in a way that facilitates direct transfer of information on the study budget 
     between DDI 2 and DDI 3 structures.
     --> 
     */?>
     <studyBudget><![CDATA[<?php $ddi->el('study_desc/study_info/study_budget');?>]]></studyBudget>
     <subject>
      <?php //keywords ?>
      <?php $keywords=(array)$ddi->get_el('study_desc/study_info/keywords');?>
      <?php foreach($keywords as $keyword):?>
        <keyword vocab="<?php echo $ddi->attr_val($keyword,'vocab');?>" vocabURI="<?php echo $ddi->attr_val($keyword,'uri');?>"><?php echo $ddi->attr_val($keyword,'keyword');?></keyword>
      <?php endforeach;?>

      <?php //topics ?>
      <?php $topics=(array)$ddi->get_el('study_desc/study_info/topics');?>
      <?php foreach($topics as $topic):?>
        <topcClas vocab="<?php echo $ddi->attr_val($topic,'vocab');?>" vocabURI="<?php echo $ddi->attr_val($topic,'uri');?>"><?php echo $ddi->el_val($topic,'topic');?></topcClas>
      <?php endforeach;?>

    </subject>
     <abstract><![CDATA[<?php $ddi->el('study_desc/study_info/abstract');?>]]></abstract>
     <sumDscr>
        <?php //time periods ?>
        <?php $time_periods=(array)$ddi->get_el('study_desc/study_info/time_periods');?>
        <?php foreach($time_periods as $time_period):?>
          <timePrd date="<?php echo $ddi->attr_val($time_period,'start');?>" event="start" cycle="<?php echo $ddi->attr_val($time_period,'cycle');?>" />
          <timePrd date="<?php echo $ddi->attr_val($time_period,'end');?>" event="end" cycle="<?php echo $ddi->attr_val($time_period,'cycle');?>" />
        <?php endforeach;?>

        <?php //collection dates?>  
        <?php $time_periods=(array)$ddi->get_el('study_desc/study_info/coll_dates');?>
        <?php foreach($time_periods as $time_period):?>
          <collDate date="<?php echo $ddi->attr_val($time_period,'start');?>" event="start" cycle="<?php echo $ddi->attr_val($time_period,'cycle');?>" />
          <collDate date="<?php echo $ddi->attr_val($time_period,'end');?>" event="end" cycle="<?php echo $ddi->attr_val($time_period,'cycle');?>" />
        <?php endforeach;?>

        <?php //nation?>  
        <?php $nations=(array)$ddi->get_el('study_desc/study_info/nation');?>
        <?php foreach($nations as $nation):?>
          <nation abbr="<?php echo $ddi->attr_val($nation,'abbreviation');?>"><?php echo $ddi->el_val($nation,'name');?></nation>
        <?php endforeach;?>

        <geogCover><?php $ddi->el('study_desc/study_info/geog_coverage');?></geogCover>
        <geogUnit><?php $ddi->el('study_desc/study_info/geog_unit');?></geogUnit>

        <?php //bounding box?>  
        <?php $bbox=(array)$ddi->get_el('study_desc/study_info/bbox');?>
        <?php foreach($bbox as $bound):?>
        <geoBndBox>
          <westBL><?php echo $ddi->attr_val($bound,'west');?></westBL>
          <eastBL><?php echo $ddi->attr_val($bound,'east');?></eastBL>
          <southBL><?php echo $ddi->attr_val($bound,'south');?></southBL>
          <northBL><?php echo $ddi->attr_val($bound,'north');?></northBL>
        </geoBndBox>
        <?php endforeach;?>

        <?php /*
        <!-- 
        boundPoly - not supported
        -->
        <boundPoly>
            <polygon>
                <point><gringLat>42.002207</gringLat><gringLon>-120.005729004</gringLon></point>
                <point><gringLat>42.002207</gringLat><gringLon>-114.039663</gringLon></point>
                <point><gringLat>35.9</gringLat><gringLon>-114.039663</gringLon></point>
                <point><gringLat>36.080</gringLat><gringLon>-114.544</gringLon></point>
                <point><gringLat>35.133</gringLat><gringLon>-114.542</gringLon></point>
                <point><gringLat>35.00208499998</gringLat><gringLon>-114.63288</gringLon></point>
                <point><gringLat>35.00208499998</gringLat><gringLon>-114.63323</gringLon></point>
                <point><gringLat>38.999</gringLat><gringLon>-120.005729004</gringLon></point>
                <point><gringLat>42.002207</gringLat><gringLon>-120.005729004</gringLon></point>
            </polygon>
        </boundPoly>
        */ ?>  

        <anlyUnit><![CDATA[<?php $ddi->el('study_desc/study_info/analysis_unit');?>]]></anlyUnit>
        <universe><![CDATA[<?php $ddi->el('study_desc/study_info/universe');?>]]></universe>
        <dataKind><?php $ddi->el('study_desc/study_info/data_kind');?></dataKind>
     </sumDscr>
     <?php /*
     <!-- qualityStatement - ddi2.5 - complex type
     
     This structure consists of two parts, standardsCompliance and otherQualityStatements. 
     In standardsCompliance list all specific standards complied with during the execution of this 
     study. Note the standard name and producer and how the study complied with the standard. 
     Enter any additional quality statements in otherQualityStatements.
     
     -->
     */?>
     <qualityStatement>
        <standardsCompliance>
          <standard> 
            <standardName><?php $ddi->el('study_desc/study_info/quality_statement/standard_name');?></standardName>
            <producer><?php $ddi->el('study_desc/study_info/quality_statement/standard_producer');?></producer>
          </standard> 
          <complianceDescription><?php $ddi->el('study_desc/study_info/quality_statement/standard_compliance_desc');?></complianceDescription> 
        </standardsCompliance>
        <otherQualityStatement><?php $ddi->el('study_desc/study_info/quality_statement/other_quality_statement');?></otherQualityStatement>
     </qualityStatement> 
     
     <notes><![CDATA[<?php $ddi->el('study_desc/study_info/notes');?>]]></notes>

    <?php /* <!-- exPostEvaluation ddi2.5
      Use this section to describe evaluation procedures not address in data evaluation processes. 
      These may include issues such as timing of the study, sequencing issues, cost/budget issues, 
      relevance, instituional or legal arrangments etc. of the study. 
      
      The completionDate attribute holds the date the evaluation was completed. 
      The type attribute is an optional type to identify the type of evaluation with or without 
      the use of a controlled vocabulary.
    --> */ ?>
    <exPostEvaluation completionDate="<?php $ddi->el('study_desc/study_info/ex_post_evaluation/completion_date');?>" type="<?php $ddi->el('study_desc/study_info/ex_post_evaluation/type');?>"> 
        <?php //evaluators?>  
        <?php $evals=(array)$ddi->get_el('study_desc/study_info/ex_post_evaluation/evaluator');?>
        <?php foreach($evals as $eval):?>
          <evaluator affiliation="<?php echo $ddi->attr_val($eval,'affiliation');?>" abbr="<?php echo $ddi->attr_val($eval,'abbr');?>" role="<?php echo $ddi->attr_val($eval,'role');?>"><?php echo $ddi->el_val($eval,'name');?></evaluator> 
        <?php endforeach;?>  
      <evaluationProcess><?php $ddi->el('study_desc/study_info/ex_post_evaluation/evaluation_process');?></evaluationProcess>
      <outcomes><?php $ddi->el('study_desc/study_info/ex_post_evaluation/outcomes');?></outcomes> 
    </exPostEvaluation>

  </stdyInfo>

  <?php /*
  <!--studyDevelopment - DDI2.5 
    Study Development
  
   Describe the process of study development as a series of development activities. These activities can be typed using
   a controlled vocabulary. Describe the activity, listing participants with their role and affiliation, 
   resources used (sources of information), and the outcome of the development activity.
  
    Example
    This would allow you to provide inputs for a number of development activities you wanted to
    capture using separate entry screens and tagged storage of developmentActivity using the 
    type attribute. For example if there was an activity related to data availability the developmentActivity 
    might be as follows:
  -->
  <studyDevelopment>
      <developmentActivity type="checkDataAvailability"> 
        <description>A number of potential sources were evaluated for content, consistency and quality</description> 
        <participant affiliation="NSO" role="statistician">John Doe</participant> 
        <resource> 
          <dataSrc>Study S</dataSrc> 
          <srcOrig>Collected in 1970 using unknown sampling method</srcOrig> 
          <srcChar>Information incomplete missing X province</srcChar> 
        </resource> 
        <outcome>Due to quality issues this was determined not to be a viable source of data for the study</outcome> 
      </developmentActivity>
  </studyDevelopment>
*/?>

  <method>
     <dataColl>
        <timeMeth><?php $ddi->el('study_desc/method/data_collection/time_method');?></timeMeth>
        
        <?php $collectors=(array)$ddi->get_el('study_desc/method/data_collection/data_collectors');?>
        <?php foreach($collectors as $collector):?>
          <dataCollector abbr="<?php echo $ddi->attr_val($collector,'abbreviation');?>" affiliation="<?php echo $ddi->attr_val($collector,'affiliation');?>"><?php echo $ddi->el_val($collector,'name');?></dataCollector>
        <?php endforeach;?> 
        
        <!-- collectorTraining - DDI2.5
        
        Collector Training

        Describes the training provided to data collectors including internviewer training, process testing, 
        compliance with standards etc. This is repeatable for language and to capture different aspects of the 
        training process. The type attribute allows specification of the type of training being described.
        
        -->        
        <collectorTraining type="<?php $ddi->el('study_desc/method/data_collection/collector_training/type');?>"><?php $ddi->el('study_desc/method/data_collection/collector_training/training');?></collectorTraining>

        <frequenc><?php $ddi->el('study_desc/method/data_collection/frequency');?></frequenc>
        <sampProc><![CDATA[<?php $ddi->el('study_desc/method/data_collection/sampling_procedure');?>]]></sampProc>
        
        <sampleFrame>
          <sampleFrameName><?php $ddi->el('study_desc/method/data_collection/sample_frame/name');?></sampleFrameName>
          
          <?php $valid_periods=(array)$ddi->get_el('study_desc/method/data_collection/sample_frame/valid_period');?>
          <?php foreach($valid_periods as $period):?>
            <validPeriod event="<?php echo $ddi->attr_val($period,'event');?>"><?php echo $ddi->attr_val($period,'date');?></validPeriod>
          <?php endforeach;?>
          
          <custodian><?php $ddi->el('study_desc/method/data_collection/sample_frame/custodian');?></custodian>
          <universe><?php $ddi->el('study_desc/method/data_collection/sample_frame/universe');?></universe>
          <frameUnit isPrimary="<?php $ddi->el('study_desc/method/data_collection/sample_frame/frame_unit/is_primary');?>">
            <unitType numberOfUnits="<?php $ddi->el('study_desc/method/data_collection/sample_frame/num_of_units');?>"><?php $ddi->el('study_desc/method/data_collection/sample_frame/unit_type');?></unitType>
          </frameUnit>
          
          <?php $ref_periods=(array)$ddi->get_el('study_desc/method/data_collection/sample_frame/reference_period');?>
          <?php foreach($ref_periods as $period):?>
            <referencePeriod event="<?php echo $ddi->attr_val($period,'event');?>"><?php echo $ddi->el_val($period,'date');?></referencePeriod>
          <?php endforeach;?>
          
          <updateProcedure><?php $ddi->el('study_desc/method/data_collection/sample_frame/update_procedure');?></updateProcedure>
        </sampleFrame>

        <deviat><?php $ddi->el('study_desc/method/data_collection/sampling_deviation');?></deviat>
        <?php $coll_mode_arr=(array)$ddi->get_el('study_desc/method/data_collection/coll_mode');?>
        <?php foreach($coll_mode_arr as $coll_mode):?>
        <collMode><?php echo $coll_mode;?></collMode>
        <?php endforeach;?>
        <resInstru><![CDATA[<?php $ddi->el('study_desc/method/data_collection/research_instrument');?>]]></resInstru>

        <?php /*
        <!-- instrumentDevelopment - DDI2.5             
        Describe any development work on the data collection instrument. Type attribute allows for the optional use of a defined development type with or without use of a controlled vocabulary.
        -->
        */?>
        <instrumentDevelopment type="<?php $ddi->el('study_desc/method/data_collection/instru_development_type');?>"><?php $ddi->el('study_desc/method/data_collection/instru_development');?></instrumentDevelopment>

        <?php /*
        <!-- sources - DD2.5 - complex type 
        
        NOTE: WON'T be supported by IHSN schemas
        -->
        <sources>
            <dataSrc>data sources [repeatabled]- NOT used by IHSN**  Used to list the book(s), article(s), serial(s), and/or machine-readable data file(s)--if any--that served as the source(s) of the data collection.</dataSrc>
            <dataSrc>data sources [repeatabled]- NOT used by IHSN**  Used to list the book(s), article(s), serial(s), and/or machine-readable data file(s)--if any--that served as the source(s) of the data collection.</dataSrc>
            <!-- sourceCitation - uses citation type element - WONT BE SUPPORTED BY IHSN -->
            <sourceCitation><titlStmt><titl>sourceCitation WONT-SUPPORT</titl></titlStmt></sourceCitation>
            <srcOrig>!NESSTAR** -  Origins of Sources - For historical materials, information about the origin(s) of the sources and the rules followed in establishing the sources should be specified. May not be relevant to survey data.</srcOrig>
            <srcChar>!NESSTAR** -  Characteristics of Source Noted</srcChar>
            <srcDocu>!NESSTAR** - Documentation and Access to Sources</srcDocu>
        </sources>
        */?>

        <collSitu><![CDATA[<?php $ddi->el('study_desc/method/data_collection/coll_situation');?>]]></collSitu>
        <actMin><![CDATA[<?php $ddi->el('study_desc/method/data_collection/act_min');?>]]></actMin>
        <ConOps><![CDATA[<?php $ddi->el('study_desc/method/data_collection/control_operations');?>]]></ConOps>
        <weight><![CDATA[<?php $ddi->el('study_desc/method/data_collection/weight');?>]]></weight>
        <cleanOps><![CDATA[<?php $ddi->el('study_desc/method/data_collection/cleaning_operations');?>]]></cleanOps>
     </dataColl>
     <notes><![CDATA[<?php $ddi->el('study_desc/method/method_notes');?>]]></notes>
     <anlyInfo>
        <respRate><![CDATA[<?php $ddi->el('study_desc/method/analysis_info/response_rate');?>]]></respRate>
        <EstSmpErr><![CDATA[<?php $ddi->el('study_desc/method/analysis_info/sampling_error_estimates');?>]]></EstSmpErr>
        <dataAppr><![CDATA[<?php $ddi->el('study_desc/method/analysis_info/data_appraisal');?>]]></dataAppr>
     </anlyInfo>
     <stdyClas><![CDATA[<?php $ddi->el('study_desc/method/study_class');?>]]></stdyClas>

     <dataProcessing type="<?php $ddi->el('study_desc/method/data_processing_type');?>"><?php $ddi->el('study_desc/method/data_processing');?></dataProcessing>

     <codingInstructions relatedProcesses="<?php $ddi->el('study_desc/method/coding_instructions/related_processes');?>" type="<?php $ddi->el('study_desc/method/coding_instructions/type');?>"> 
        <txt><?php $ddi->el('study_desc/method/coding_instructions/txt');?></txt> 
        <command formalLanguage="<?php $ddi->el('study_desc/method/coding_instructions/command_language');?>"><?php $ddi->el('study_desc/method/coding_instructions/command');?></command> 
     </codingInstructions>
  </method>



  <dataAccs>
     <setAvail>
        <?php /*
        //nada schema does not support repeated values
        <accsPlac URI="URI">data collection location name</accsPlac>
        <accsPlac URI="URI">data collectino location 2 name</accsPlac>
        */?>

        <accsPlac URI="<?php $ddi->el('study_desc/data_access/dataset_availability/access_place_uri');?>"><?php $ddi->el('study_desc/data_access/dataset_availability/access_place');?></accsPlac>        
        <origArch><?php $ddi->el('study_desc/data_access/dataset_availability/original_archive');?></origArch>
        <avlStatus><?php $ddi->el('study_desc/data_access/dataset_availability/status');?></avlStatus>
        <collSize><?php $ddi->el('study_desc/data_access/dataset_availability/coll_size');?></collSize>
        <complete><?php $ddi->el('study_desc/data_access/dataset_availability/complete');?></complete>
        <fileQnty><?php $ddi->el('study_desc/data_access/dataset_availability/file_quantity');?></fileQnty>
        <notes><![CDATA[<?php $ddi->el('study_desc/data_access/dataset_availability/notes');?>]]></notes>
     </setAvail>


     <useStmt>
        
        <?php //confdec -- TODO - schema is missing the attributes ?>  
        <?php $confdec_arr=(array)$ddi->get_el('study_desc/data_access/dataset_use/conf_dec');?>
        <?php foreach($confdec_arr as $conf):?>
        <confDec required="<?php echo $ddi->attr_val($conf,'required');?>" formNo="<?php echo $ddi->attr_val($conf,'form_no');?>" URI="<?php echo $ddi->attr_val($conf,'uri');?>"><?php echo $ddi->el_val($conf,'txt');?></confDec>
        <?php endforeach;?>

        <?php $spec_perms=(array)$ddi->get_el('study_desc/data_access/dataset_use/spec_perm');?>
        <?php foreach($spec_perms as $perm):?>
        <specPerm required="<?php echo $ddi->attr_val($perm,'required');?>" formNo="<?php echo $ddi->attr_val($perm,'form_no');?>" URI="<?php echo $ddi->attr_val($perm,'uri');?>"><?php echo $ddi->el_val($perm,'txt');?></specPerm>
        <?php endforeach;?>
        
        <restrctn><?php $ddi->el('study_desc/data_access/dataset_use/restrictions');?></restrctn>
        
        <?php $contacts=(array)$ddi->get_el('study_desc/data_access/dataset_use/contact');?>
        <?php foreach($contacts as $contact):?>
          <contact affiliation="<?php echo $ddi->attr_val($contact,'affiliation');?>" URI="<?php echo $ddi->attr_val($contact,'uri');?>" email="<?php echo $ddi->attr_val($contact,'email');?>"><?php echo $ddi->el_val($contact,'name');?></contact>
        <?php endforeach;?>
        
        <citReq><![CDATA[<?php $ddi->el('study_desc/data_access/dataset_use/cit_req');?>]]></citReq>
        <deposReq><![CDATA[<?php $ddi->el('study_desc/data_access/dataset_use/deposit_req');?>]]></deposReq>
        <conditions><![CDATA[<?php $ddi->el('study_desc/data_access/dataset_use/conditions');?>]]></conditions>
        <disclaimer><![CDATA[<?php $ddi->el('study_desc/data_access/dataset_use/disclaimer');?>]]></disclaimer>
     </useStmt>
     <notes><![CDATA[<?php $ddi->el('study_desc/data_access/notes');?>]]></notes>
  </dataAccs>
  <notes><![CDATA[<?php $ddi->el('study_desc/notes');?>]]></notes>      
</stdyDscr>