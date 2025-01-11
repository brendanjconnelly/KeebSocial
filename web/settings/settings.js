async function submit_bio() {
    req_gen("bio");
}

async function submit_name() {
    req_gen("name");
}

async function req_gen(_field) {
    let resp = await API("/api/v1/SETPROFILE.php", {
        user: getCookie("username"),
        key: getCookie("token"),
        field: _field,
        content: document.getElementById("new_" + _field).value
    });
    if(resp == "0") {
        document.getElementById(_field + "_res").innerHTML = "Success";
    } else {
        document.getElementById(_field + "_res").innerHTML = "An error occurred.";
    }
}

async function submit_passwd() {
    if(document.getElementById("new_pw1").value != document.getElementById("new_pw2").value) {
        document.getElementById("pw_res").innerHTML = "Passwords do not match.";
        return;
    }

    let resp = await API("/api/v1/PASSWD.php", {
        user: getCookie("username"),
        key: getCookie("token"),
        old: document.getElementById("old_pw").value,
        new: document.getElementById("new_pw1").value
    });
    console.log(resp);
    if(resp == "0") {
        document.getElementById("pw_res").innerHTML = "Success";
    } else {
        document.getElementById("pw_res").innerHTML = "An error occurred.";
    }
}
