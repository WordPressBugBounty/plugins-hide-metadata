<?php

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

?>
<div class="catchp-widget-sidebar" id="sidebar-container">
	<div id="sidebar">
		<div class="catch-sidebar-title highlighted"><?php esc_html_e('Recommendations', 'hide-metadata'); ?></div>

		<div class="catchp-sidebar-spot-wrapper">
			<div class="catchp-sidebar-spot">
				<div class="sidebar-spot-inner">
					<h3><?php esc_html_e('Premium Themes', 'hide-metadata'); ?></h3>
					<p><?php esc_html_e('Check out our simple, clean and responsive Premium WordPress Themes that come with an array of crucial features with a superior functionality.', 'hide-metadata'); ?> <a target="_blank" href="https://catchthemes.com/themes/category/premium/"><?php esc_html_e('Check our premium themes.', 'hide-metadata'); ?></a></p>
					<a class="catch-banner-link" target="_blank" href="https://catchthemes.com/themes/category/premium/"><img class="catch-banner-image" src="<?php echo esc_url(plugins_url('images/premium-theme-246x175.jpg', dirname(__FILE__))); ?>" alt="catch" width="246" height="175"></a>
				</div>
			</div>
			<div class="catchp-sidebar-spot">
				<div class="sidebar-spot-inner">
					<h3><?php esc_html_e('Free Themes', 'hide-metadata'); ?></h3>
					<p><?php esc_html_e('Check out our collection of Free WordPress Themes that are clean, simple and feature-rich.', 'hide-metadata'); ?> <a target="_blank" href="https://catchthemes.com/themes/category/free/"><?php esc_html_e('Check our free themes.', 'hide-metadata'); ?></a></p>
					<a class="catch-banner-link" target="_blank" href="https://catchthemes.com/themes/category/free/"><img class="catch-banner-image" src="<?php echo esc_url(plugins_url('images/free-theme-246x175.jpg', dirname(__FILE__))); ?>" alt="catch" width="246" height="175"></a>
				</div>
			</div>
			<div class="catchp-sidebar-spot">
				<div class="sidebar-spot-inner">
					<h3><?php esc_html_e('Premium Plugins', 'hide-metadata'); ?></h3>
					<p><?php esc_html_e('Check out our secured Premium WordPress Plugins to enhance and add extra functionalities to your website. An exceptional way to get the most out of WordPress!', 'hide-metadata'); ?> <a target="_blank" href="https://catchplugins.com/plugins/tag/pro/"><?php esc_html_e('Check our premium plugins.', 'hide-metadata'); ?></a></p>
					<a class="catch-banner-link" target="_blank" href="https://catchplugins.com/plugins/tag/pro/">
						<img class="catch-banner-image" src="<?php echo esc_url(plugins_url('images/premium-plugin-246x116.png', dirname(__FILE__))); ?>" alt="catch" width="246" height="116"></a>
				</div>
			</div>

			<div class="catchp-sidebar-spot">
				<div class="sidebar-spot-inner">
					<h3><?php esc_html_e('Free Plugins', 'hide-metadata'); ?></h3>
					<p><?php esc_html_e('Check out our Free WordPress Plugins and get the taste of our service without affecting your wallet.', 'hide-metadata'); ?>
						<a target="_blank" href="https://catchplugins.com/plugins/tag/free/"><?php esc_html_e('check our free plugins.', 'hide-metadata'); ?></a>
					</p>
					<a class="catch-banner-link" target="_blank" href="https://catchplugins.com/plugins/tag/free/">
						<img class="catch-banner-image" src="<?php echo esc_url(plugins_url('images/free-plugin-246x116.png', dirname(__FILE__))); ?>" alt="catch" width="246" height="116"></a>
				</div>
			</div>
		</div><!-- .catchp-sidebar-spot-wrapper -->
	</div>
</div>