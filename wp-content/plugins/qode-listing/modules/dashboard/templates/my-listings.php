<div class="qode-membership-dashboard-page">
	<div class="qode-membership-dashboard-page-content">
		<h5 class="qode-membership-dashboard-page-title">
			<?php
			    esc_html_e( 'My listings', 'qode-listing' );
			?>
		</h5>
		<?php
			echo bridge_qode_execute_shortcode('job_dashboard', '');
		?>
	</div>
</div>