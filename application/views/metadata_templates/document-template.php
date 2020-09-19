<?php
/*
 * DDI template - display metadata for DDI fields
 *
 * @metadata - array containing all metadata
 *
 * @id - survey id
 * @surveyid - IDNO
 * @ all survey table fields
 *
 *
 **/
?>


<?php 
    //rendered html for all sections
    $output=array();
?>

<?php /* ?>
<pre>
<?php
 var_dump($metadata);
?>
</pre>
<?php */?>

<?php
if(isset($metadata['resources'])){
    //replace files->file_uri with resource download link 
    foreach($metadata['metadata']['files'] as $file_idx => $file){
        if (array_key_exists($file['file_uri'], $metadata['resources'])){
            $resource=$metadata['resources'][$file['file_uri']];
            $metadata['metadata']['files'][$file_idx]['file_uri']=site_url("catalog/{$resource['survey_id']}/download/{$resource['resource_id']}/".rawurlencode($resource['filename']) );
        }
    }
}
?>

<?php
    //render files field
    $download_buttons=render_field(
        "download_buttons_array",
        "metadata.files",
        get_field_value('metadata.files',$metadata), 
        $options=array(
            'url_column'=>'file_uri',
            'title_column'=>'note'
        )
    );
?>    

<?php    
    $download_buttons_html=render_field(
        "literal",
        "",
        $download_buttons, 
        $options=array(
            'css_class'=>'float-md-right',
            'css_style'=>''
        )
    );
    $output['download_links']=$download_buttons_html;
?>


<!-- identification section -->
<?php $output['description']= render_group('description',
    $fields=array(
        "metadata.document_description.title_statement.idno"=>"text",
        "metadata.document_description.title_statement.title"=>"text",
        "metadata.document_description.title_statement.sub_title"=>"text",
        "metadata.document_description.title_statement.alternate_title"=>"text",
        "metadata.document_description.title_statement.abbreviated_title"=>"text",
        "metadata.document_description.type"=>"text",
        "metadata.document_description.description"=>"text",
        "metadata.document_description.toc"=>"text",
        "metadata.document_description.toc_structured"=>"array",
        "metadata.document_description.abstract"=>"text",
        "metadata.document_description.notes"=>"array",
        "metadata.document_description.scope"=>"text",
        "metadata.document_description.ref_country"=>"array",
        "metadata.document_description.spatial_coverage"=>"text",
        "metadata.document_description.temporal_coverage"=>"text",
        "metadata.document_description.date_created"=>"text",
        "metadata.document_description.date_available"=>"text",
        "metadata.document_description.date_modified"=>"text",
        "metadata.document_description.date_published"=>"text",
        
        //"metadata.document_description.id_numbers"=>"object",
        "metadata.document_description.id_numbers.type"=>"text",
        "metadata.document_description.id_numbers.value"=>"text",
        "metadata.document_description.publication_frequency"=>"text",
        "metadata.document_description.languages"=>"array",
        "metadata.document_description.license"=>"array",
        "metadata.document_description.bibliographic_citation"=>"text",
        "metadata.document_description.chapter"=>"text",
        "metadata.document_description.edition"=>"text",
        "metadata.document_description.institution"=>"text",
        "metadata.document_description.journal"=>"text",
        "metadata.document_description.volume"=>"text",
        "metadata.document_description.issue"=>"text",
        "metadata.document_description.pages"=>"text",
    ),
    $metadata);
    ?>

<?php $output['series']= render_group('series',
    $fields=array(
        "metadata.document_description.series"=>"text",
    ),
    $metadata);
    ?>

    <?php $output['authors']= render_group('authors',
        $fields=array(            
            "metadata.document_description.creator"=>"text",
            "metadata.document_description.authors"=>"array",
            "metadata.document_description.editors"=>"array",
            "metadata.document_description.translators"=>"array",
            "metadata.document_description.contributors"=>"array",
            "metadata.document_description.publisher"=>"text",
            "metadata.document_description.publisher_address"=>"text"
        ),
        $metadata);
        ?>
    
    <?php $output['copyrights']= render_group('copyrights',
        $fields=array(  
            "metadata.document_description.rights"=>"text",
            "metadata.document_description.copyright"=>"text",
            "metadata.document_description.usage_terms"=>"text",
            "metadata.document_description.security_classification"=>"text",
            "metadata.document_description.access_restrictions"=>"text"
        ),
        $metadata);
    ?>
    
    <?php $output['themes_topics']= render_group('themes_topics',
        $fields=array( 
        "metadata.document_description.sources.data_source"=>"array",
        "metadata.document_description.keywords"=>"",
        "metadata.document_description.keywords.name"=>"text",
        "metadata.document_description.keywords.vocabulary"=>"text",
        "metadata.document_description.keywords.uri"=>"text",
        "metadata.document_description.themes"=>"",
        "metadata.document_description.themes.name"=>"text",
        "metadata.document_description.themes.vocabulary"=>"text",
        "metadata.document_description.themes.uri"=>"text",
        "metadata.document_description.topics"=>"array",
        "metadata.document_description.disciplines"=>"array",
        "metadata.document_description.audience"=>"text",        
        "metadata.document_description.mandate"=>"text",
        "metadata.document_description.pricing"=>"text",
        "metadata.document_description.relations"=>"array",
        "metadata.tags"=>"array_comma",        
    ),
    $metadata);
    ?>    

<?php $output['lda_topics']= render_group('lda_topics',
        $fields=array(  
        "metadata.document_description.lda_topics"=>"lda_topics",
        "metadata.document_description.lda_topics.model_info"=>"array",        
        "metadata.document_description.lda_topics.topic_description"=>"array",
        "metadata.document_description.lda_topics.topic_description.topic_words.word"=>"text",
    ),
    $metadata);
?>



<!-- metadata_production -->
<?php $output['metadata_production']= render_group('metadata_production',
    $fields=array(
        "metadata.metadata_information.title"=>"text",
        "metadata.metadata_information.idno"=>"text",
        "metadata.metadata_information.producers"=>"array",
        "metadata.metadata_information.version"=>"text",
    ),
    $metadata);
?>

<?php 
    //items not to be included in the left side bar
    $exclude_sidebar_items=array('download_links');
    //renders html
    $this->load->view('metadata_templates/metadata_output', array('output'=>$output, 'exclude_sidebar_items'=>$exclude_sidebar_items));
?>