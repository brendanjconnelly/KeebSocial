async function registerPost() {
    API("/api/v1/ACT.php", {
        user: getParam("name"),
        key: getCookie("token"),
        action: "POST",
        content: document.getElementById("postbody").value
    })
    window.location.reload();
}