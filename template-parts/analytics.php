<?php
require_once get_template_directory() . '/inc/constants.php';

$enableGA = ATL_DSA_get_theme_option(SETTINGS_ENABLE_GA_KEY);
$gaUA = ATL_DSA_get_theme_option(SETTINGS_GA_UA_KEY);
?>

<?php if ($enableGA && $gaUA): ?>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= $gaUA ?>"></script>
<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', '<?= $gaUA ?>');
</script>

<?php endif; ?>
