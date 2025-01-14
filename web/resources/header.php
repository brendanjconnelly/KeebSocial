<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title>KeebSocial</title>
        <meta charset="UTF-8">
        <meta property="og:title" content="KeebSocial">
        <meta property="og:description" content="KeebSocial Social Networking Platform -- beta">
        <meta property="og:image" content="/resources/icon.png">
        <link rel="icon" type="image/x-icon" href="/resources/icon.png">
        <link rel="stylesheet" href="/resources/index.css">
        <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>

        <script src="/resources/user.js"></script>
        <script src="/timeline/timeline.js"></script>
        <script src="/resources/index.js"></script>
        <script src="/resources/auth.js"></script>
        <script src="/login/login.js"></script>
        <script src="/timeline/createpost.js"></script>

    </head>
    <body>
      <div>
        <div id="header">
          <a href="/" id="homecorner">
            <img src="/resources/keebler.png" id="keeblerlogo">
            <span id="platformname" class="navbarspan">KeebSocial</span>
          </a>
          <a href="/"><span id="home" class="navbarspan navlink">Home</span></a>
          <a href="/about"><span id="about" class="navbarspan navlink">About</span></a>

        </div>
        <a href="/login" id="login"><span class="navbarspan">Login</span></a>
        <div id="menu">
          <a href="javascript:void" id="profile"><img id="headerpfp" wdith="64" height="64" src="https://dummyimage.com/64/64/fff"></a>
          <br>
          <div id="menuoptions">
            <a href="/user.php"><p>Profile</p></a>
            <a href="/settings"><p>Settings</p></a>
            <a href="javascript:logout()"><p>Log out</p></a>
          </div>
        </div>
        <script>
            isLoggedIn().then(
              value => {
                if(value == '0') {
                  document.getElementById("login").style.display = 'none';
                  document.getElementById("profile").style.display = 'inline-block';
                } else {
                  document.getElementById("login").style.display = 'inline-block';
                  document.getElementById("profile").style.display = 'none';
                }

                setHeaderPFP();
              }
            );

            async function setHeaderPFP() {
                let resp = await API("/api/v1/GETPROFILE.php", {
                  user: getCookie("username"),
                  key: getCookie("token"),
                  target: getCookie("username"),
                  field: "pfp"
                })

                document.getElementById("headerpfp").setAttribute("src", resp);
              }
          </script>
      </div>