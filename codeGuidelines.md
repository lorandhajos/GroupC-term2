# Code guidelines

## General 

Use camelCase when writing code and naming files<br>
Examples for camelCase: 
* $variableOne = "foo";
* functionDoSomething();
* exampleFileName.php

Indentation: 2 spaces<br>
Charset: UTF-8

Don't comment out code, remove it if you are not using it.

Make sure to leave an empty new line at the end of every page!

## HTML

* Make sure that you follow the rules and add all required attributes:
```<img src="" alt="">```

* Use the following validator to validate your HTML: https://validator.w3.org/#validate_by_input

Use this scheme to leave comments in HTML files

``` HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <?php include "head.php" ?>
  <link href="styles/login.css" rel="stylesheet">
  <title>Login</title>
</head>
<body class="text-center">
   <main>
   </main>
</body>
</html>
```

### Example
``` HTML
<main>
  <!-- heroBanner --> 
  <section class="heroBanner">

  </section>
  <!-- findUs -->
  <section class="findUs">
      
  </section>
</main>
```
 
## PHP

* Use comments to summarize blocks of code.

### Example
```
<?php
  session_start();

  // check if the user is already logged in
  if(isset($_SESSION["name"])){
    header("location: home.php");
    exit();
  }

  // include database connection
  include_once('config.php');
?>
```

## CSS

* Our CSS is in groups, we have **media queries**, **HTML tags**, **classes**, **IDs**

* Use the following validator to validate your CSS:  https://jigsaw.w3.org/css-validator/#validate_by_input

### Example
```CSS
/* Media queries */
@font-face {
   font-family: CircularStd-Black;
   src: url(fonts/CircularStd/CircularStd-Black.otf);
}

/* HTML Tags */
header {
   background-color: blueviolet;
   grid-column: 2/7;
}

main {
   background-color: aqua;
   grid-column: 2/7;
}

footer {
   background-color: yellowgreen;
   grid-column: 2/7;
}

/* Classes */
.gridContainer {
   display: grid;
   grid-template-columns: repeat(7,1fr);
}

/* IDs */
#logo {
   height: 300px;
}
```
