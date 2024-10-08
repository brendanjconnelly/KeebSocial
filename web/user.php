<?php echo file_get_contents(getenv("PHP_ROOT") . "/resources/header.php"); ?>

<?php
    if(!isset($_GET['name'])) {
        if(isset($_COOKIE['username'])) {
            header("Location: /user.php?name=" . $_COOKIE['username']);
            exit();
        } else {
            header("Location: /");
            exit();
        }
    }
?>

<link rel="stylesheet" href="/resources/user.css">
<link rel="stylesheet" href="/timeline/timeline.css">
<br>
<div id="profile_banner">
    <div id="profile_id">
        <h1 id="profile_username"></h1><span id="profile_handle"></span>
    </div>
    <img id="profile_image" src="https://dummyimage.com/128/128/fff">
    <div id="profile_bio"></div>
    <button onclick="follow();" id="profile_follow">Follow</button>
    <button onclick="unfollow();" id="profile_unfollow">Unfollow</button>
</div>
<br><br><br>
<?php
if(strcmp($_GET['name'], $_COOKIE['username']) == 0) {
    echo file_get_contents(getenv("PHP_ROOT") . "/timeline/createpost.php");
}
?>

<div id="posts">

</div>

<script>
    init();
    initTimeline([getParam("name")]);
</script>
<?php echo file_get_contents(getenv("PHP_ROOT") . "/resources/footer.php"); ?>