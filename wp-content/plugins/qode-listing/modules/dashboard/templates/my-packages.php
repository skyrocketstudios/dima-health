<?php
$user_id = get_current_user_id();
$listing_packages = wc_paid_listings_get_user_packages($user_id);
?>

<div class="qode-membership-dashboard-page">
	<div class="qode-membership-dashboard-page-content">
		<h5 class="qode-membership-dashboard-page-title">
			<?php
				esc_html_e( 'My Packages', 'qode-listing' );
			?>
		</h5>
		<?php
		    $user_id = get_current_user_id();
			$listing_packages = wc_paid_listings_get_user_packages($user_id);

			if(is_array($listing_packages) && count($listing_packages)){
				$params = array();
				?>

				<ul class="qode-user-package-holder">
                    <?php foreach($listing_packages as $package){
						$params['package'] = $package;
						echo qode_listing_get_listing_module_template_part('modules/dashboard', 'package', '', $params);
					}?>
				</ul>

			<?php }
			else{
				echo qode_listing_get_listing_module_template_part('modules/dashboard', 'package-not-found', '', $params);
            }
		?>
	</div>
</div>