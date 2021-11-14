<div class="tab-pane tab-pad" id="shortcode" role="tabpanel" aria-labelledby="shortcode-tab">
	<div class="manage-top-dash shortcode-tab text"><?php echo esc_html('Shortcode','tc_caf');?></div>
	<div id="tabs-panel">
	<!---- START QUERY OPTIONS TOGGLE ---->	
	<div class="tab-panel shortcode">
		<div class="tab-header active" data-content="shortcode"><i class="fa fa-check-square-o left" aria-hidden="true"></i> <?php echo esc_html('Shortcode Options','tc_caf');?> <i class="fa fa-angle-down" aria-hidden="true"></i></div>
		<div class="tab-content shortcode active">
	<!---- START FULL ROW CUSTOM POST TYPE ----> 
	<div class='col-sm-12'>
<?php global $post;$pid=$post->ID;$sh_code="&lt;?php echo do_shortcode('[caf_filter id=&quot;".$pid."&quot;]'); ?&gt;"; ?>
	<!-- FORM GROUP -->
	<div class="form-group row">
    <label for="custom-post-type-select" class="col-sm-12 col-form-label"><?php echo esc_html('Shortcode For Page/Post','tc_caf');?><span class="info"><?php echo esc_html('Directly paste this shortcode in your page builder/classic editor','tc_caf');?></span></label>
    <div class="col-sm-12">
    <input type="text" readonly="" value="[caf_filter id='<?php echo $pid; ?>']" onfocus="this.select()" class="rd-shortcode">
	</div>
	</div>
    <!-- FORM GROUP -->
    </div>

	<div class='col-sm-12'>
	<!-- FORM GROUP -->
	<div class="form-group row">
    <label for="custom-post-type-select" class="col-sm-12 col-form-label"><?php echo esc_html('Shortcode For Page Template','tc_caf');?><span class="info"><?php echo esc_html('Directly paste this shortcode in your page template','tc_caf');?></span></label>
    <div class="col-sm-12">
   <?php global $post;$pid=$post->ID;$sh_code="&lt;?php echo do_shortcode('[caf_filter id=&quot;".$pid."&quot;]'); ?&gt;"; ?>
	<input type="text" readonly value="<?php echo $sh_code; ?>" onfocus="this.select()" class="rd-shortcode">	
	</div>
	</div>
    <!-- FORM GROUP -->
    </div>		
	<!---- END FULL ROW CUSTOM POST TYPE ----> 
   <?php do_action("tc_caf_after_caf_shortcode_row"); ?>
	</div>
	</div>
	<!---- END QUERY OPTIONS TOGGLE ---->
<?php do_action("tc_caf_after_caf_shortcode_tab"); ?>
	</div>

	</div>