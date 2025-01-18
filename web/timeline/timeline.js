var dbPostsCount = -1;

async function initTimeline(users) {

    dbPostsCount = await API("/api/v1/GETPOSTS.php", {
        user: getCookie("username"),
        key: getCookie("token"),
        feed: users
    });

    for(let i = 0; i < dbPostsCount; i++) {
        let resp = await API("/api/v1/GETPOSTS.php", {
            user: getCookie("username"),
            key: getCookie("token"),
            feed: users,
            index: i
        });
        let post = await toHTML(JSON.parse(resp));
        document.getElementById("posts").appendChild(post);
    }
}

async function reply(_uuid) {
    console.log("reply for post uuid=" + _uuid);

    let resp = API("/api/v1/ACT.php", {
        user: getCookie("username"),
        key: getCookie("token"),
        action: "REPLY",
        uuid: _uuid,
        content: document.getElementById("reply-text-" + _uuid).value
    })

    window.location.reload();
}

async function renderReplies(uuid) {
    console.log("call to renderReplies");
    let replies = document.createElement("div");
    replies.classList.add("reply_container");

    let replyText = document.createElement("textarea");
    replyText.classList.add("reply_text");
    replyText.setAttribute("id", "reply-text-" + uuid);
    replies.appendChild(replyText);

    replies.appendChild(document.createElement("br"));

    let replyButton = document.createElement("button");
    replyButton.innerHTML = "Reply";
    replyButton.classList.add("post_button")
    replyButton.setAttribute("onclick", "reply(\"" + uuid + "\");");
    replies.appendChild(replyButton);
    replies.appendChild(document.createElement("br"))

    // now the individual replies

    let resp = await API("/api/v1/GETREPLIES.php", {
        user: getCookie("username"),
        key: getCookie("token"),
        parent: uuid
    });

    resp = JSON.parse(resp);
    for(let i = 0; i < resp.length; i++) {
        let html = await toHTML(resp[i], true);
        replies.appendChild(html);
    }
    return replies;
}

async function getFeed() {
    feed = await API("/api/v1/FEEDQUERY.php", {
        user: getCookie("username"),
        field: "follows"
    });
    return JSON.parse(feed);
}

async function toHTML(postobject, isReply=false) {
    let post = document.createElement("div");
    post.setAttribute("id", postobject.uuid);
    post.setAttribute("class", isReply ? "reply" : "post");

    // info banner

    let bannerDiv = document.createElement("div");
    bannerDiv.setAttribute("class", "postbanner"); 

    let authorlink = document.createElement("a");
    authorlink.setAttribute("href", "/user.php?name=" + postobject.author);
    authorlink.classList.add("postheader");
    // profile picture
    // i forget why i made tis a container div but there's probably a reason
    let iconDiv = document.createElement("div");
    iconDiv.style.display = "inline";

    icon = document.createElement("img");

    let resp = await API("/api/v1/GETPROFILE.php", {
        user: getCookie("username"),
        key: getCookie("token"),
        target: postobject.author,
        field: "pfp"
    })

    icon.setAttribute("src", resp);
    icon.setAttribute("width", "64");
    icon.setAttribute("height", "64");
    icon.setAttribute("class", "postico");
    iconDiv.appendChild(icon);

    authorlink.appendChild(iconDiv);
    
    // name
    let author = document.createElement("span");
    author.setAttribute("class", "author");
    author.innerHTML = await getUserField("name", postobject.author);
    authorlink.appendChild(author);

    // handle
    let handle = document.createElement("span");
    handle.setAttribute("class", "handle");
    handle.innerHTML = "@" + postobject.author;
    authorlink.appendChild(handle);

    // time

    let permalink = document.createElement("a");
    permalink.setAttribute("href", "/viewpost.php?id=" + postobject.uuid);
    permalink.classList.add("postheader");

    let date = document.createElement("span");
    date.setAttribute("class", "date");
    date.innerHTML = new Date(postobject.date * 1000).toLocaleString();
    permalink.appendChild(date);


    bannerDiv.appendChild(authorlink);
    bannerDiv.appendChild(permalink);

    // options (if the current user is the post author)
    optionsDiv = document.createElement("div");
    optionsDiv.style.display = "inline";
    optionsDiv.classList.add("optionsdiv");

    if(postobject.author == getCookie("username")) {
        options = document.createElement("img");
        options.setAttribute("src", "/resources/content/options.png");
        options.setAttribute("class", "postoptions");
        options.setAttribute("uuid", postobject.uuid);
        options.setAttribute("onclick", "menuClick(this)");
        options.setAttribute("id", "menubutton-" + postobject.uuid)

        optionsDiv.appendChild(options);
        optionsDiv.appendChild(document.createElement("br"));
        optionsDiv.appendChild(menuFactory(postobject.uuid));
        bannerDiv.appendChild(optionsDiv);
    }

    post.appendChild(bannerDiv);


    // content
    let content = document.createElement("p");
    content.innerHTML = postobject.content;
    content.classList.add("posttext");
    post.appendChild(content);

    
    if(!isReply) {
        replyContent = await renderReplies(postobject.uuid);
        post.appendChild(replyContent);
    }

    // first level replies
    // replies = document.createElement("div");
    
    return post;

}

// Called by viewpost.php
async function viewpost() {
    resp = await API("/api/v1/GETPOSTS.php", {
        user: getCookie("username"),
        key: getCookie("token"),
        uuid: getParam("id")
    });

    obj = await toHTML(JSON.parse(resp));
    document.getElementById("posts").appendChild(obj);
}

function menuClick(e) {
    let uuid = e.getAttribute("uuid");
    var currentDisplay = document.getElementById("options-" + uuid).style.display;
    document.getElementById("options-" + uuid).style.display =
    (currentDisplay == "inline") ? "none" : "inline";
}

// Hide menu if we've clicked anywhere else
document.addEventListener("click", function(e) {
    if(!e.target.classList.contains("postoptions")) {
        elems = document.getElementsByClassName("menuOptions");
        for(let i = 0; i < elems.length; i++) {
            elems[i].style.display = "none";
        }
    }
});


// Generate post options menu
function menuFactory(uuid) {

    menuDiv = document.createElement("div");
    menuDiv.setAttribute("class", "menuOptions");
    menuDiv.setAttribute("uuid", uuid);
    menuDiv.setAttribute("id", "options-" + uuid)

    menuDiv.style.display = "none";
    
    deleteBtn = document.createElement("a");
    deleteBtn.setAttribute("uuid", uuid);
    deleteBtn.setAttribute("href", "#");
    deleteBtn.setAttribute("onclick", "javascript:deletePost(this);");

    deleteText = document.createElement("span");
    deleteText.setAttribute("class", "deleteText");
    deleteText.innerHTML = "Delete";

    deleteBtn.appendChild(deleteText);

    menuDiv.appendChild(deleteBtn);
    return menuDiv;
}

function deletePost(e) {
    console.log(e);
    let uuid = e.getAttribute("uuid");
    API("/api/v1/DELETE.php", {
        user: getCookie("username"),
        key: getCookie("token"),
        post: uuid
    });

    document.getElementById(uuid).remove();
}