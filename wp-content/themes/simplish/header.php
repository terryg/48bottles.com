<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>" />
	<title><?php if(is_404()): ?><?php _e('Error 404 - Not Found', 'simplish'); ?> - <?php elseif(is_search()): ?><?php the_search_query() ?> - <?php else: ?><?php wp_title('-',true,'right'); ?><?php endif ?><?php bloginfo('name') ?><?php if(is_search()): ?> <?php _e('Search', 'simplish'); ?><?php endif ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="all" title="Simplish" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php
/* 
 * Comment threads script loads on post, page,
 * or attachment when option is true.
 */
if(is_singular() && get_option('thread_comments')):
	wp_enqueue_script('comment-reply');
endif;
?>

<?php wp_head(); // DO NOT REMOVE - for plugin api ?>

</head>

<body <?php body_class(); ?>>
<div id="container">
	<div id="header" role="banner">
		<h1><span><a href="<?php bloginfo('url'); ?>" rel="home"><?php bloginfo('name'); ?></a></span></h1>
		<h2><?php bloginfo('description'); ?></h2>
	</div>
	<div id="content-wrapper">
<!-- goto ^(archive image index page search single ...).php:/^div#content -->
