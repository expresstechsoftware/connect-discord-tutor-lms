
<div class="contact-form ">
	<form method="post" action="<?php echo esc_attr( get_site_url() ) . '/wp-admin/admin-post.php'; ?>">
		
	  <div class="ets-container">
		<div class="top-logo-title">
		  <img src="<?php echo esc_url( CONNECT_DISCORD_TUTOR_LMS_PLUGIN_DIR_URL . 'admin/images/ets-logo.png' ); ?>" class="img-fluid company-logo" alt="">
		  <h1><?php esc_html_e( 'ExpressTech Softwares Solutions Pvt. Ltd.', 'connect-discord-tutor-lms' ); ?></h1>
		  <p><?php esc_html_e( 'ExpressTech Software Solution Pvt. Ltd. is the leading Enterprise WordPress development company.', 'connect-discord-tutor-lms' ); ?><br>
		  <?php esc_html_e( 'Contact us for any WordPress Related development projects.', 'connect-discord-tutor-lms' ); ?></p>
		</div>
		<ul style="text-align: left;">
			<li class="mp-icon mp-icon-right-big"><?php esc_html_e( 'If you encounter any issues or errors, please report them on our support forum for the Connect Tutor LMS to Discord plugin. Our community will be happy to help you troubleshoot and resolve the issue.', 'connect-discord-tutor-lms' ); ?></li>
			<li class="mp-icon mp-icon-right-big">
			<?php
			echo wp_kses(
				'<a href="https://wordpress.org/support/plugin/connect-tutorlms-to-discord/">Support » Plugin: Connect Tutor LMS to Discord</a>',
				array(
					'a' => array(
						'href' => array(),
					),
				)
			);
			?>
 </li>
		</ul>

	  </div>
  </form>
</div>
