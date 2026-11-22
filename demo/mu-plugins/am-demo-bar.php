<?php
/**
 * Plugin Name: Portfolio demo bar (not part of the theme)
 * Description: Adds the "concept demo" bar, a noindex tag and a stand-in for the quote form on the static demo export. Only used to build the public demo.
 *
 * @package AlderbendDemo
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'wp_head',
	function () {
		echo '<meta name="robots" content="noindex, nofollow">' . "\n";
	},
	1
);

add_action(
	'wp_body_open',
	function () {
		?>
	<div class="am-demo-bar" role="note" style="font:14px/1.45 system-ui,-apple-system,'Segoe UI',sans-serif;background:#0f0f0f;color:#f3f3ef;padding:10px 16px;display:flex;flex-wrap:wrap;gap:6px 18px;justify-content:space-between;align-items:center;position:relative;z-index:50"><span><b>Concept demo by Alex Mercer.</b> Alderbend Landscape Co. is made up. Static copy of a real WordPress theme running locally, example content, not client work. Forms do not send.</span><a href="https://alexmercerprojects.com/alderbend-wordpress-theme/" target="_top" style="color:#f3f3ef;text-decoration:underline;text-underline-offset:3px">Back to the write-up</a></div>
		<?php
	}
);

// The static export has no PHP, so the quote form can only explain itself.
add_action(
	'wp_footer',
	function () {
		?>
	<script>
	document.addEventListener('submit', function (e) {
		var f = e.target.closest('.ab-quote__form');
		if (!f) { return; }
		e.preventDefault();
		var box = f.parentNode.querySelector('.ab-quote__notice');
		if (!box) { box = document.createElement('p'); box.className = 'ab-quote__notice is-ok'; box.setAttribute('role', 'status'); f.parentNode.insertBefore(box, f); }
		box.textContent = 'Demo only: on a real WordPress site this form posts to admin-post.php, is checked and emailed. Nothing was sent here.';
		box.scrollIntoView({ block: 'center' });
	});
	</script>
		<?php
	},
	99
);
