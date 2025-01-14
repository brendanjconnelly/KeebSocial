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



async function getFeed() {
    feed = await API("/api/v1/FEEDQUERY.php", {
        user: getCookie("username"),
        field: "follows"
    });
    return JSON.parse(feed);
}


async function toHTML(postobject) {
    post = document.createElement("div");
    post.setAttribute("id", postobject.uuid);
    post.setAttribute("class", "post");


    // info banner
    

    bannerDiv = document.createElement("div");
    bannerDiv.setAttribute("class", "postbanner"); 

    authorlink = document.createElement("a");
    authorlink.setAttribute("href", "/user.php?name=" + postobject.author);
    authorlink.classList.add("postheader");

    // profile picture
    // i forget why i made tis a container div but there's probably a reason
    iconDiv = document.createElement("div");
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
    author = document.createElement("span");
    author.setAttribute("class", "author");
    author.innerHTML = await getUserField("name", postobject.author);
    authorlink.appendChild(author);

    // handle
    handle = document.createElement("span");
    handle.setAttribute("class", "handle");
    handle.innerHTML = "@" + postobject.author;
    authorlink.appendChild(handle);

    // time

    permalink = document.createElement("a");
    permalink.setAttribute("href", "/viewpost.php?id=" + postobject.uuid);
    permalink.classList.add("postheader");

    date = document.createElement("span");
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

        optionsDiv.appendChild(options);
        optionsDiv.appendChild(document.createElement("br"));
        optionsDiv.appendChild(menuFactory(postobject.uuid));
        bannerDiv.appendChild(optionsDiv);
    }

    post.appendChild(bannerDiv);


    // content
    content = document.createElement("p");
    content.innerHTML = postobject.content;
    content.classList.add("posttext");
    post.appendChild(content);

    // first level replies
    replies = document.createElement("div");
    
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
    var currentDisplay = document.getElementById(uuid).children[0].children[2].children[2].style.display;
    document.getElementById(uuid).children[0].children[2].children[2].style.display =
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