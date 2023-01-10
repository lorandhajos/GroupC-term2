var btnContainer = document.getElementsByClassName("nav")[0];

var btns = btnContainer.getElementsByClassName("nav-link");

// remove active class from all buttons
for (var i = 0; i < btns.length; i++) {
   btns[i].classList.remove("active");
}

// add active class to the button that matches the current page
if (document.title == "Home Page") {
   btns[0].classList.add("active");
} else {
   btns[1].classList.add("active");
}
