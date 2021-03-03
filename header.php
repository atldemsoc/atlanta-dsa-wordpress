<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package _s
 */

$chapterName = ATL_DSA_get_theme_option(SETTINGS_CHAPTER_NAME_KEY);
$chapterName = $chapterName ? $chapterName : 'Metro Atlanta';
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport"
				content="width=device-width, initial-scale=1">
	<script src="https://kit.fontawesome.com/77d892d14a.js" crossorigin="anonymous"></script>

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div class="site">
	<nav class="navbar is-primary"
			 role="navigation"
			 aria-label="main navigation">
		<div class="container">
			<div class="navbar-brand">
				<a class="navbar-item"
					 href="<?php echo home_url(); ?>">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/dsa-rose-mark.svg"
							 class="site-nav__logo"
							 width="35"
							 height="35"
							 alt="DSA Rose"
					/>
					<span class="site-nav__name"><?= $chapterName ?> <span class="site-nav__name-dsa">DSA</span></span>
				</a>

				<a role="button"
					 class="navbar-burger burger"
					 aria-label="menu"
					 aria-expanded="false"
				>
					<span aria-hidden="true"></span>
					<span aria-hidden="true"></span>
					<span aria-hidden="true"></span>
				</a>
			</div>
			<div id="primary-menu"
					 class="navbar-menu">
				<div class="navbar-end">
					<?php wp_nav_menu(array(
						'theme-location' => 'header-menu', //change it according to your register_nav_menus() function
						'depth' => 2,
						'menu' => 'NewNav',
						'container' => '',
						'menu_class' => '',
						'items_wrap' => '%3$s',
						'walker' => new Bulma_NavWalker(),
						'fallback_cb' => 'Bulma_NavWalker::fallback'
					));
					?>
					<a
						class="site__search-trigger navbar-item"
					>
						<span class="icon is-medium">
							<i class="fas fa-search"></i>
						</span>
					</a>
				</div>
			</div>
		</div>
		<div class="site__search-box is-inactive">
			<div class="site__search-box__inner container">
				<form
					class="form"
					method="get"
					action="/"
				>
					<div class="field has-addons">
						<div class="control is-expanded">
							<input
								class="input is-dark site__search-input"
								type="text"
								name="s"
							/>
						</div>
						<div class="control">
							<button class="button is-dark">
								Search
							</button>
						</div>
						<div class="control">
							<a class="button is-white site__search-trigger">
								<span class="icon is-medium">
									<i class="fas fa-times"></i>
								</span>
							</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</nav>

	<div class="site__content">
