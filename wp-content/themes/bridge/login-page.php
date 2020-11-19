
<?php
/* 
    Template Name: Login Page
*/


?>


<?php get_header();?>

<div class="single_post_breadcrumb" <?php print bridge_qode_get_module_part( $bridge_qode_page_title_breadcrumbs_animation_data ); ?>> <?php bridge_qode_custom_breadcrumbs(); ?></div>


<div class="login_container">
    <div class="login_title">
        Welcome to Dima!
    </div>

    <div class="login_column_wrapper">
        <div class="login_column_register">
            <span class="login_column_title">NEW COSTUMER</span>
            <span class="login_subtitle">Praesent tincidunt urna quis nisi consequat cursus. Morbi eu euismod nulla. Proin a rutrum justo. Duis lacus ipsum, suscipit vel purus et, dapibus ultricies justo. Integer id ante diam. Suspendisse at quam</span>
            <a href="/register" class="custom_blog_button" >Create an Account</a>
        </div>
        <div class="login_column_login">
            <span class="login_column_title">LOGIN</span>
            <span class="login_subtitle">If you have an account with us, please log in.</span>
            <label for="username" >USERNAME OR E-MAIL</label>
            <input type="text" id="username" placeholder="Enter Username or Email">
            <label for="password" >PASSWORD</label>
            <input type="password" id="password" placeholder="Enter Password">
            <input type="submit" class="custom_blog_button" value="Login">
            <a href="#">Lost your password?</a>
        </div>
    </div>



</div>

<?php 
get_footer();
?>

<script>
</script>