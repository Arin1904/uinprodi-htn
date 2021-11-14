<div class="tab-pane" id="typography" role="tabpanel" aria-labelledby="typography-tab">

	<div class="manage-top-dash general-tab text"><?php echo esc_html('Typography','tc_caf'); ?> </div>

	<div id="tabs-panel">
	<div class="tab-panel filter-typography">

	<div class="tab-header" data-content="filter-typo"><i class="fa fa-align-center left" aria-hidden="true"></i> <?php echo esc_html('Filter Typography','tc_caf'); ?><i class="fa fa-angle-down" aria-hidden="true"></i></div>
	<div class="tab-content filter-typo">
	<!---- START ROW FORM GROUP POST TITLE ---->	
	<div class="form-group row-bottom">
    <label for="post-title-font" class="col-sm-12 col-form-label"><?php echo esc_html('Filter Font','tc_caf');?> <br/><span><?php echo esc_html('Select Properties for filter typo.','tc_caf');?></span></label>
	<!-- START SIDEBAR OF BAR AREA POST TITLE-->
	<div class="col-sm-12">
	<!-- START FIRST ROW OF POST TITLE SIDEBAR -->	
	<div class="row">
	<!-- START FONT FAMILY GROUP POST TITLE -->	
    <div class="col-sm-4">
	<span class='label-title'><?php echo esc_html('Font Family','tc_caf');?></span>
	<?php 
	$fonts=apply_filters('tc_caf_font_family',array($caf_admin_fliters,'tc_caf_font_family'));
     ?>
    <select class="form-control caf_import" id="caf-filter-font" name="caf-filter-font" data-import="caf_filter_fonts">
	<option value="inherit" <?php if($caf_filter_font=='inherit'){echo "selected";} ?>><?php echo esc_html('Default','tc_caf'); ?></option>
	<?php
     if(!class_exists("TC_CAF_PRO")) {
		if($fonts) {
		foreach($fonts as $font) {
			if($caf_filter_font==$font){$font_sel="selected";} else {$font_sel='';}
			echo '<option value="'.$font.'" '.$font_sel.'>'.$font.'</option>';
		}	
		}
     }
     else {
      foreach ( $fonts as $key=>$font ) {
     if($caf_filter_font==$key){$font_sel="selected";} else {$font_sel='';}
     ?>
         <option <?php echo $font_sel;?> value="<?php echo $key;?>" data-val="<?php echo $font['character_set'];?>" datat-type="<?php echo $font['type'];?>"><?php echo $key;?></option>   
         <?php 
      } 
     }
     ?>

    </select>
	</div>
	<!-- END FONT FAMILY GROUP POST TITLE -->
  <?php do_action("tc_caf_font_family_hidden");?>
	<!-- START TEXT TRANSFORM GROUP POST TITLE-->	
	<div class="col-sm-4">
	<span class='label-title'><?php echo esc_html('Text Transform','tc_caf'); ?></span>
    <select  class="form-control caf_import" data-import="caf_filter_transform" id="caf-filter-transform" name="caf-filter-transform">
	<option value="uppercase" <?php if($caf_filter_transform=='uppercase'){echo "selected";} ?>><?php echo esc_html('Uppercase','tc_caf');?></option>
	<option value="capitalize" <?php if($caf_filter_transform=='capitalize'){echo "selected";} ?>><?php echo esc_html('Capitalize','tc_caf');?></option>
	<option value="lowercase" <?php if($caf_filter_transform=='lowercase'){echo "selected";} ?>><?php echo esc_html('Lowercase','tc_caf');?></option>
    </select>
	</div>
	<!-- END TEXT TRANSFORM GROUP POST TITLE-->
		<!-- START FONT SIZE GROUP POST TITLE-->	
    <div class="col-sm-4">
	<span class='label-title'><?php echo esc_html('Font Size','tc_caf');?></span>
    <div class="input-group">
    <input type="number" class="form-control caf_import" data-import="caf_filter_font_size" placeholder="12" aria-label="font-size" aria-describedby="basic-addon2" name="caf-filter-font-size" value="<?php echo $caf_filter_font_size; ?>">
    <div class="input-group-append">
    <span class="input-group-text" id="basic-addon2"><?php echo esc_html('px','tc_caf'); ?></span>
    </div>
    </div>
	</div>
	<!---- END FONT SIZE GROUP POST TITLE ---->
	</div>	
	<!-- END FIRST ROW OF POST TITLE SIDEBAR -->	
		
	</div>
	<!---- END SIDEBAR OF BAR AREA POST TITLE ---->	
	</div>
 <!---- END ROW FORM GROUP POST TITLE ---->
	<?php do_action("tc_caf_after_caf_filter_typo_row"); ?>
	</div>

	</div>	
<?php do_action("tc_caf_after_caf_filter_typo_tab"); ?>
	<div class="tab-panel post-typography">

	<div class="tab-header" data-content="post-typo"><i class="fa fa-align-center left" aria-hidden="true"></i> <?php echo esc_html('Post Typography','tc_caf');?><i class="fa fa-angle-down" aria-hidden="true"></i></div>
	<div class="tab-content post-typo">
	<!---- START ROW FORM GROUP POST TITLE ---->	
	<div class="form-group row-bottom">
    <label for="post-title-font" class="col-sm-12 col-form-label"><?php echo esc_html('Post Title Font ','tc_caf');?><br/><span><?php echo esc_html('Select Properties for post title.','tc_caf');?></span></label>
	<!-- START SIDEBAR OF BAR AREA POST TITLE-->
	<div class="col-sm-12">
	<!-- START FIRST ROW OF POST TITLE SIDEBAR -->	
	<div class="row">
	<!-- START FONT FAMILY GROUP POST TITLE -->	
    <div class="col-sm-4">
	<span class='label-title'><?php echo esc_html('Font Family','tc_caf');?></span>
    <select  class="form-control caf_import" data-import="caf_post_fonts" id="caf-post-font" name="caf-post-font">
	<option value="inherit" <?php if($caf_post_font=='inherit'){echo "selected";} ?>><?php echo esc_html('Default','tc_caf'); ?></option>
	<?php 
		if(!class_exists("TC_CAF_PRO")) {
		if($fonts) {
		foreach($fonts as $font) {
			if($caf_post_font==$font){$font_sel="selected";} else {$font_sel='';}
			echo '<option value="'.$font.'" '.$font_sel.'>'.$font.'</option>';
		}	
		}
     }
     else {
       foreach ( $fonts as $key=>$font ) {
     if($caf_post_font==$key){$font_sel="selected";} else {$font_sel='';}
     ?>
         <option <?php echo $font_sel;?> value="<?php echo $key;?>" data-val="<?php echo $font['character_set'];?>" datat-type="<?php echo $font['type'];?>"><?php echo $key;?></option>   
         <?php 
      } 
     }
     ?>
    </select>
	</div>
	<!-- END FONT FAMILY GROUP POST TITLE -->
	<!-- START TEXT TRANSFORM GROUP POST TITLE-->	
	<div class="col-sm-4">
	<span class='label-title'><?php echo esc_html('Text Transform','tc_caf'); ?></span>
    <select  class="form-control caf_import" data-import="caf_post_title_transform" id="caf-post-title-transform" name="caf-post-title-transform">
	<option value="uppercase" <?php if($caf_post_title_transform=='uppercase'){echo "selected";} ?>><?php echo esc_html('Uppercase','tc_caf');?></option>
	<option value="capitalize" <?php if($caf_post_title_transform=='capitalize'){echo "selected";} ?>><?php echo esc_html('Capitalize','tc_caf'); ?></option>
	<option value="lowercase" <?php if($caf_post_title_transform=='lowercase'){echo "selected";} ?>><?php echo esc_html('Lowercase','tc_caf');?></option>
    </select>
	</div>
	<!-- END TEXT TRANSFORM GROUP POST TITLE-->
		<!-- START FONT SIZE GROUP POST TITLE-->	
    <div class="col-sm-4">
	<span class='label-title'><?php echo esc_html('Font Size','tc_caf');?></span>
    <div class="input-group">
    <input type="number" class="form-control caf_import" data-import="caf_post_title_font_size" placeholder="12" aria-label="font-size" aria-describedby="basic-addon2" name="caf-post-title-font-size" value="<?php echo $caf_post_title_font_size; ?>">
    <div class="input-group-append">
    <span class="input-group-text" id="basic-addon2"><?php echo esc_html('px','tc_caf');?></span>
    </div>
    </div>
	</div>
	<!---- END FONT SIZE GROUP POST TITLE ---->
	</div>	
	<!-- END FIRST ROW OF POST TITLE SIDEBAR -->	
		
	</div>
	<!---- END SIDEBAR OF BAR AREA POST TITLE ---->	
	</div>
    <!---- END ROW FORM GROUP POST TITLE ---->
	<?php do_action("tc_caf_after_caf_post_typo_row"); ?>
	</div>

	</div>
<?php do_action("tc_caf_after_caf_post_typo_tab"); ?>

	</div>

</div>