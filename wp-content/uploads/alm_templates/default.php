<li class="list-berita" style="list-style-type:none;">
<div class="baris">
  <div class="column1">
  
  <p class="hari" >
   <?php the_time("d"); ?>
   <p style="margin-left:5.5em">
   <p style="color:black">
   <p style="font-family:georgia">
   </p><br>
  
  <p class="meta-details" >
   <?php the_time("F Y"); ?>
   <p style="margin-left:5.5em">
   <p style="color:black">
   <p style="font-family:georgia">
   </p>
</div>
  <div class="column2">
  <h3><a href="<?php the_permalink(); ?>" class= "judul-berita"><?php the_title(); ?>
   <a style="margin-left:5.5em">
   <a style="color:black">
   <a style="font-family:georgia">
   </a></h3>
   <?php the_excerpt(); ?>
</div>
  <div class="column3<?php if (! has_post_thumbnail() ) { echo ' no-img'; } ?>">
   <?php if ( has_post_thumbnail() ) {
      the_post_thumbnail('alm-thumbnail');
   }?>
</div>
</div>
  </li>