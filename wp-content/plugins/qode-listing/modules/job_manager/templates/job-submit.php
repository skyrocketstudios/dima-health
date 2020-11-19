<?php
/**
 * Job Submission Form
 */
if ( ! defined( 'ABSPATH' ) ) exit;

global $job_manager;
?>
<form action="<?php echo esc_url( $action ); ?>" method="post" id="submit-job-form" class="job-manager-form" enctype="multipart/form-data">
    
        <h5 class="qode-membership-dashboard-page-title">
            <?php
                esc_html_e( 'Add new listing', 'qode-listing' );
            ?>
        </h5>

        <?php do_action( 'submit_job_form_start' ); ?>

        <?php if ( apply_filters( 'submit_job_form_show_signin', true ) ) : ?>

            <?php get_job_manager_template( 'account-signin.php' ); ?>

        <?php endif; ?>

        <?php if ( job_manager_user_can_post_job() || job_manager_user_can_edit_job( $job_id ) ) : ?>

            <div class="qode-ls-field-holder-wrapper clearfix">

                <div class="qode-ls-field-holder">
                    <!-- Job Information Fields -->
                    <?php do_action( 'submit_job_form_job_fields_start' ); ?>

                    <?php

                    $counter = 0;
                    $limit  = count($job_fields);
                    $limiter = round($limit/2);
                    foreach ( $job_fields as $key => $field) {
                        $counter++;
                        ?>
                        <fieldset class="fieldset-<?php echo esc_attr( $key ); ?>">
                            <label for="<?php echo esc_attr( $key ); ?>"><?php echo wp_kses_post($field['label']) . apply_filters( 'submit_job_form_required_label', $field['required'] ? '' : ' <small>' . __( '(optional)', 'qode-listing' ) . '</small>', $field ); ?></label>
                            <div class="field <?php echo wp_kses_post($field['required']) ? 'required-field' : ''; ?>">
    			                <?php get_job_manager_template( 'form-fields/' . $field['type'] . '-field.php', array( 'key' => $key, 'field' => $field ) ); ?>
                            </div>
                        </fieldset>
                        <?php
                        if($counter == $limiter && $counter !== $limit){ ?>
                            </div>
                            <div class="qode-ls-field-holder">
                        <?php }

                    }  ?>

                    <?php do_action( 'submit_job_form_job_fields_end' ); ?>

                </div> <!--close qode-ls-field-holder-->

            </div>

            <!-- Company Information Fields -->
            <div class="qode-ls-field-holder-full-width">
                <?php if ( $company_fields ) : ?>
                <h5 class="qode-membership-dashboard-page-title">
                    <?php esc_html_e( 'Company Details', 'qode-listing' ); ?>
                </h5>

                    <?php do_action( 'submit_job_form_company_fields_start' ); ?>
                    <?php foreach ( $company_fields as $key => $field ) : ?>
                        <fieldset class="fieldset-<?php echo esc_attr( $key ); ?>">
                            <label for="<?php echo esc_attr( $key ); ?>"><?php echo wp_kses_post($field['label']) . apply_filters( 'submit_job_form_required_label', $field['required'] ? '' : '', $field ); ?></label>
                            <div class="field <?php echo wp_kses_post($field['required']) ? 'required-field' : ''; ?>">
                                <?php get_job_manager_template( 'form-fields/' . $field['type'] . '-field.php', array( 'key' => $key, 'field' => $field ) ); ?>
                            </div>
                        </fieldset>
                    <?php endforeach; ?>

                    <?php do_action( 'submit_job_form_company_fields_end' ); ?>
                <?php endif; ?>
            </div>

            <?php do_action( 'submit_job_form_end' ); ?>

            <p>
                <input type="hidden" name="job_manager_form" value="<?php echo wp_kses_post($form); ?>" />
                <input type="hidden" name="job_id" value="<?php echo esc_attr( $job_id ); ?>" />
                <input type="hidden" name="step" value="<?php echo esc_attr( $step ); ?>" />
                <input type="submit" name="submit_job" class="qbutton default" value="<?php echo esc_html__( 'Save Changes', 'qode-listing' )?>">
            </p>

	<?php else : ?>

		<?php do_action( 'submit_job_form_disabled' ); ?>

	<?php endif; ?>
        
    
</form>
