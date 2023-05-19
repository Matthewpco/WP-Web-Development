<?php 

// The shortcode function
function wp_plugin_github_function() { 
  
    // Advertisement code pasted inside a variable
    $string = '';
    $string .= '

    <!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="preconnect" href="https://fonts.gstatic.com" />
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&family=Oswald:wght@500&display=swap"
            rel="stylesheet" />
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
            integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p"
            crossorigin="anonymous" />
    </head>

    <body class="bodyClass">
        <main class="container">
            <div class="badge">
                <div class="circle"></div>
            </div>
            <h1 class="h1Class"><i class="fab fa-github-alt"></i> GitHub Repo Gallery</h1>
            <section class="intro">
                <div class="overview"></div>
            </section>

            <section class="repos">
                <input type="text" class="filter-repos" placeholder="Search by name" />
                <ul class="repo-list ulClass"></ul>
            </section>
            <section class="repo-data hide"></section>
            <div class="button-container">
                <button class="view-repos git-back-button hide">Back to Repo Gallery</button>
            </div>
        </main>
    </body>

</html>

    ';
      
	
    // Code returned
    return $string; 
    }