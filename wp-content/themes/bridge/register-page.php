
<?php
/* 
    Template Name: Registration Page
*/
$passType = "password";

function togglePass(){
    $this->passType = $this->passType =="password" ? "text" : "password";
    return $this->passType;
}
?>

<?php get_header();?>

<div class="single_post_breadcrumb" <?php print bridge_qode_get_module_part( $bridge_qode_page_title_breadcrumbs_animation_data ); ?>> <?php bridge_qode_custom_breadcrumbs(); ?></div>


<div class="login_container">
    <div class="login_title">
        Welcome to Dima!
    </div>
    <div class="register_column_wrapper">
        <span class="login_column_title">REGISTER</span>
        <span class="login_subtitle">Please fill up the fields to register.</span>

        <div class="register_column_left">
            <label for="reg_username" >USERNAME</label>
            <input autocomplete="off" type="text" id="reg_username" placeholder="Enter username" required>
            <label for="reg_firstname" >FIRST NAME</label>
            <input autocomplete="off" type="text" id="reg_firstname" placeholder="Enter first name" >
            <label for="reg_webiste" >WEBSITE</label>
            <input autocomplete="off" type="url" id="reg_webiste" placeholder="Enter website" >
        </div>
        <div class="register_column_right">
            <label for="reg_email" >E-MAIL</label>
            <input autocomplete="off" type="email" id="reg_email" placeholder="Enter e-mail" required>
            <label for="reg_lastname" >LAST NAME</label>
            <input autocomplete="off" type="text" id="reg_lastname" placeholder="Enter last name" >
            <label for="reg_password" >PASSWORD</label>
            <input autocomplete="off" type="password" id="reg_password" value="<?php echo randomPassword(20)?>" >
            <span class="password_icon">
                <i id="open_eye" class="fa fa-eye"  onclick="togglePass()" style="display:''"></i>
                <i id="slashed_eye" class="fa fa-eye-slash"  onclick="togglePass()" style="display:none"></i>
            </span>
        </div>
        <input type="submit" class="custom_blog_button" value="Register">
    </div>
    <!-- <div class="login_column_wrapper">
        <div class="login_column_register">
            <span class="login_column_title">NEW COSTUMER</span>
            <span class="login_subtitle">Praesent tincidunt urna quis nisi consequat cursus. Morbi eu euismod nulla. Proin a rutrum justo. Duis lacus ipsum, suscipit vel purus et, dapibus ultricies justo. Integer id ante diam. Suspendisse at quam</span>
            <input type="submit" class="custom_blog_button" value="Create an Account">
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
    </div> -->



</div>

<?php 
get_footer();
?>


<script>

function togglePass(){
    var input_type = document.getElementById('reg_password').type;  
    var icon_1 = document.getElementById('open_eye');  
    var icon_2 = document.getElementById('slashed_eye');
    type = input_type == "password" ? "text" : "password";
    document.getElementById('reg_password').type = type;
    icon_1.style.display = icon_1.style.display ==  '' ? 'none' : '';
    icon_2.style.display = icon_2.style.display ==  '' ? 'none' : '';

}
</script>