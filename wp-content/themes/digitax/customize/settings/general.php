<?php 
$general_css = '';


/* Primary Font */
$default_primary_font = json_decode( digitax_default_primary_font() );
$primary_font = json_decode( get_theme_mod( 'primary_font' ) ) ? json_decode( get_theme_mod( 'primary_font' ) ) : $default_primary_font;
$primary_font_family = $primary_font->font;


/* General Typo */
$general_font_size = get_theme_mod( 'general_font_size', '16px' );
$general_line_height = get_theme_mod( 'general_line_height', '26px' );
$general_letter_space = get_theme_mod( 'general_letter_space', '0px' );
$general_color = get_theme_mod( 'general_color', '#43464b' );



/* Primary Color */
$primary_color = get_theme_mod( 'primary_color', '#f16334' );

$general_css .= <<<CSS

body{
	font-family: {$primary_font_family};
	font-weight: 400;
	font-size: {$general_font_size};
	line-height: {$general_line_height};
	letter-spacing: {$general_letter_space};
	color: {$general_color};
}
p{
	color: {$general_color};
	line-height: {$general_line_height};
}
.sidebar .widget ul li .rpwwt-post-title,
article.post-wrap .post-title h2.post-title a:hover,
article.post-wrap .post-meta .post-meta-content .right,
article.post-wrap .post-meta .post-meta-content .post-author a, article.post-wrap .post-meta .post-meta-content .categories a,
article.post-wrap.single-post .post-meta .post-meta-content .right,
article.post-wrap.single-post .tag-share .social li a:hover,
.ova-blog .single-blog-dig .card .card-body .readmore:hover i,
.ova-blog .single-blog-dig .card .card-body h4 a:hover,
.ova-blog .single-blog-dig h6 a:hover,
.ova-blog .single-blog-dig .card .card-body .readmore:hover{
	color: {$primary_color}!important;
}

.single-case-dig .card-body .btn,
.single-blog-dig .card .card-body .btn,
body .ova_about_team .single-team-dig .author:hover,
body .ova_feature .inside-service-dig .content .title_brand a:hover{
	color: {$primary_color};	
}

article.post-wrap .post-footer .post-readmore a,
.content_comments .comments .wrap_comment_form .comment-form .form-submit input[type=submit],
.ova_testimonial .testimo_ver_1 .owl-carousel .owl-nav button,
.footer-input .blog-newsletter .form-mail button[type="submit"],
.ova_testimonial .testimo_ver_2.home2 .owl-carousel .owl-dots button.owl-dot.active,
.ova_testimonial .testimo_ver_2.home2 .owl-carousel .owl-dots button.owl-dot:hover
{
	background-color: {$primary_color}!important;	
}
.ova_testimonial .testimo_ver_2.home2 .owl-carousel .owl-dots button.owl-dot{
	border-color: {$primary_color}!important;	
}
.form_style1 .blog-newsletter .form-mail button,
.form_home_1 .submit,
body .ova_banner_2 .hero-banner-dig.home2 .seo-form-in .blog-newsletter p.form-mail button{
	background-color: {$primary_color};		
}
.ova_testimonial .testimo_ver_1 .owl-carousel .owl-nav button:hover{
	background-color: #221a1a!important;	
}

.sidebar .widget ul li a, .sidebar .widget a,
.sidebar .widget.widget_rss ul li a.rsswidget{
	color: {$primary_color};		
}
.sidebar .widget ul li a:hover, .sidebar .widget a:hover{
	color: #343434;
}

CSS;



return $general_css;


