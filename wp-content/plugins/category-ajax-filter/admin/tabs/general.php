<div class="tab-pane tab-pad active" id="general" role="tabpanel" aria-labelledby="general-tab">
	<div class="manage-top-dash general-tab text">
		<?php echo esc_html('General Settings','tc_caf'); ?></div>
	<div id="tabs-panel">
	<!---- START QUERY OPTIONS TOGGLE ---->	
	<div class="tab-panel query">
		<div class="tab-header active" data-content="query"><i class="fa fa-check-square-o left" aria-hidden="true"></i> <?php echo esc_html('Query Options','tc_caf'); ?> <i class="fa fa-angle-down" aria-hidden="true"></i></div>
		<div class="tab-content query active">
	<!---- START FULL ROW CUSTOM POST TYPE ----> 
	<div class='col-sm-12 row-bottom'>
	<!-- FORM GROUP -->
	<div class="form-group row">
    <label for="custom-post-type-select" class="col-sm-12 col-form-label"><?php echo esc_html('Custom Post Type','tc_caf'); ?><span class="info"><?php echo esc_html('Select your post type to filter. Deaflut: Post','tc_caf'); ?></span></label>
    <div class="col-sm-12">
    <select class="form-control tc_caf_object_field tc_caf_select" data-field-type='select' id="custom-post-type-select" name="custom-post-type-select">
	<option value="post"><?php echo esc_html('Post','tc_caf'); ?></option>
    <?php
	foreach($cpost_types as $cpost_type) {
		if($select==$cpost_type) {$sel='selected';} else {$sel='';}
		echo "<option value='".$cpost_type."' $sel>".$cpost_type."</option>";
	} 
		?>
    </select>
	</div>
	</div>
    <!-- FORM GROUP -->
  
    </div>
	<!---- END FULL ROW CUSTOM POST TYPE ----> 
  <?php do_action("tc_caf_after_caf_post_type_row"); ?>
   
	<!---- START FULL ROW TAXONOMY ----> 
	<div class='col-sm-12 row-bottom'>
	<!---- FORM GROUP ---->
  <?php
  if(class_exists("TC_CAF_PRO") && TC_CAF_PRO_PLUGIN_VERSION>'4.4.1') {
   $taxo=get_object_taxonomies($select);
   do_action("tc_caf_post_multiple_taxonomies",$taxo,$tax,$select);
   
   
   
  }
  else {
   ?>
	<div class="form-group row">
    <label for="caf-taxonomy" class="col-sm-12 col-form-label"><?php echo esc_html('Taxonomy','tc_caf'); ?><span class="info"><?php echo esc_html('Select your taxonomy from dropdown. Deaflut: Category','tc_caf'); ?></span></label>
    <div class="col-sm-12">
    <select class="form-control tc_caf_object_field tc_caf_select" data-field-type='select' id="caf-taxonomy" name="caf-taxonomy">
	<?php
	$taxo=get_object_taxonomies($select);
		if($taxo){
		foreach($taxo as $tax1) {
		if($tax==$tax1) { $sl="selected";} else {$sl="";}
		echo "<option value='".$tax1."' id='hide' $sl>".$tax1."</option>";	
		}	
		}
	?>
    </select>
     
     
     
     
	</div>
	</div>
  <?php
  }
  ?>
    <!---- FORM GROUP ---->
 </div>
	<!---- END FULL ROW TAXONOMY ----> 
<?php do_action("tc_caf_after_caf_post_tax_row"); ?>
	<!---- START FULL ROW TERMS ----> 
  <?php
   $terms=get_terms(array('taxonomy' => $tax,'hide_empty' => false));
  if(class_exists("TC_CAF_PRO") && TC_CAF_PRO_PLUGIN_VERSION>'4.4.1') {
   $taxo=get_object_taxonomies($select);
   do_action("tc_caf_post_multiple_taxonomies_terms",$tax,$terms_sel);
  }
  else {
   ?>
	<div class='col-sm-12 row-bottom'>
	<!---- FORM GROUP TERMS ---->
	<div class="form-group row">
    <label for="caf-terms" class="col-sm-12 col-form-label"><?php echo esc_html('Terms','tc_caf'); ?><span class="info"><?php echo esc_html('Select Terms that you want to show on frontend. Deaflut: 5/ASC ORDER','tc_caf'); ?></span></label>
    <div class="col-sm-12 ">
	<ul class="category-lists">
	<li id="all-cat"><input name='all-select' class='category-list-all check' id='category-all-btn' type='checkbox' onClick="selectAllCats(event)"><label for='category-all-btn' class='category-list-all-label'><?php echo esc_html('All','tc_caf'); ?></label></li>
	<?php
  //var_dump($tax);
	$terms=get_terms(array('taxonomy' => $tax,'hide_empty' => false));
	//var_dump($terms);
	if($terms) {
	foreach($terms as $term) {
		$sl1='';
	if(is_array($terms_sel)) {

	if(count($terms_sel)>0) {	
//var_dump($terms_sel);
	if (in_array($term->term_id, $terms_sel)) {$sl1='checked';}else{$sl1='';}}}	

	echo "<li><input name='category-list[]' class='category-list check' id='category-list-id$term->term_id' type='checkbox' value='".$term->term_id."' $sl1><label for='category-list-id$term->term_id' class='category-list-label'>".$term->name."</label></li>";	
	}
	}
	?>
	</ul>	
	</div>
	</div>
    <!---- FORM GROUP ---->
    </div>
   <?php
  }
  ?>
	<!---- END FULL ROW TERMS ---->
   <?php do_action("tc_caf_after_caf_post_terms_row",$terms); ?>
	</div>
  
  
	</div>
  <?php do_action("tc_caf_after_caf_query_tab"); ?>
	<!---- END QUERY OPTIONS TOGGLE ---->
	
	</div>
	</div>