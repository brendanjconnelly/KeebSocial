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


async function submit_pfp() {
    newpfp = document.getElementById("new_pfp").files[0];
    formdata = new FormData();
    formdata.append("user", getCookie("username"));
    formdata.append("key", getCookie("token"));
    formdata.append("pfp", newpfp, "pfp.png");
    console.log(formdata);
    let resp = await fetch("/api/v1/UPLOADPFP.php", {
        method: 'POST',
        body: formdata
    });

    console.log(await resp.text());
}

// Fill in bio and name settings

async function prefill() {
    let bio = await API("/api/v1/GETPROFILE.php", {
        user: getCookie("username"),
        key: getCookie("token"),
        target: getCookie("username"),
        field: "bio"
    })

    document.getElementById("new_bio").value = bio;

    let name = await API("/api/v1/GETPROFILE.php", {
        user: getCookie("username"),
        key: getCookie("token"),
        target: getCookie("username"),
        field: "name"
    })

    document.getElementById("new_name").value = name;
}

prefill();