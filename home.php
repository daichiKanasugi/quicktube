<?php

     session_start();
    
    if(!isset($_SESSION['userId'])){
        header("Location:login.html");
    }
?>

<!DOCTYPE html>
<html>

<head>
    <title>QuickTube</title>
    <link rel="shortcut icon" href="quicktubeLogo.ico">
    <link href="styles/styles.css" rel="stylesheet" type="text/css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <style>
        .heading, #random, #search, #login, #keyword, #commit, #login, #signin, #searchResults, #hist, .mostPopular, #popularResults, .popularResults{
        display: flex;
        margin: auto;
        align-items: center;
        justify-content: center; 
}
    </style>
</head>

<body class="body" onload="loadClient()">
    
    <div class="heading" >
        <div class = "left">
            <div id="logo">
                <img src="quicktubeLogo.png" width="50" height="50" alt="qt"></img>
            </div>
        </div>
        <div class = "center">
            <div id="search">
                <input type="text" id="keyword">&nbsp</input>
                <button type="button" class="btn btn-primary" id="commit">Search</button>
            </div>
        </div>
        <div class ="right">
            <div id="login">
                <button type="button" class="btn btn-primary" id="logoutButton">Log Out</button>
            </div>
            &nbsp
            <div id="account">
                <button type="button" class="btn btn-primary" id="account">Account setting</button>
            </div>
        </div>
    </div>
    <h1 id="searchdisplay">Search Result</h1>
    <div id="searchResults"></div>
        
    <h1>Most Popular</h1>
    <div id="popularResults"></div>
        
<script src="https://apis.google.com/js/api.js"></script>


    <script>
        function loadClient() {
            gapi.client.setApiKey("AIzaSyAWtu8p_Ve4s6hbXbj4GM3ErXEgB1fkMZY");
            return gapi.client.load("https://www.googleapis.com/discovery/v1/apis/youtube/v3/rest")
                .then(function() { console.log("GAPI client loaded for API");
                hidden();
                execute_Popular();
                },
                    function(err) { console.error("Error loading GAPI client for API", err); });
        }
        
        function execute_Search() {
            var $searchQuery = document.getElementById("keyword").value;
            return gapi.client.youtube.search.list({
                    "part": "snippet",
                    "maxResults": 5,
                    "q": $searchQuery
                })
                .then(function(response) {
                        document.getElementById("searchResults").innerHTML += "<br>";
                        $("#searchResults").html("");
                        response.result.items.forEach(showURL);
                        visible();
                    },
                    function(err) { console.error("Execute error", err); });
        }
        
        function showURL(item) {
             $("[id=searchResults]").append(`<div class="col s3">
          <iframe width="100%" height="auto" src="https://www.youtube.com/embed/${item.id.videoId}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
          <button type="button" class="addPlaylist" id="https://www.youtube.com/embed/${item.id.videoId}">Add to playlist</button></div>`);
        }
        
        gapi.load("client");
        
          function execute_Popular() {
            return gapi.client.youtube.videos.list({
              "part": "snippet",
              "chart": "mostPopular",
              "regionCode": "US"
            })
                .then(function(response) {
                        response.result.items.forEach(showPopular);
                      },
                      function(err) { console.error("Execute error", err); });
          }
          
          function showPopular(item) { 
                     $("[id=popularResults]").append(`<div class="col s3">
                    <iframe width="100%" height="auto" src="https://www.youtube.com/embed/${item.id}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                    <button type="button" class="addPlaylist" id="https://www.youtube.com/embed/${item.id}">Add to playlist</button></div>` );
                }
                
          
                function visible(){
                    document.getElementById("searchdisplay").style.visibility = "visible";
                }
                function hidden(){
                    document.getElementById("searchdisplay").style.visibility='hidden';
                }
                
                $(document).on('click','.addPlaylist',function(){
                    $.ajax({
                        type: "POST",
                        url: "api/addPlaylist.php",
                        dataType: "json",
                        data: {
                            "url": $(this).attr("id")
                        }
                    });
                });
                
                $(document).on('click','#commit',function(){
                    $.ajax({
                        type: "POST",
                        url: "api/addHistory.php",
                        dataType: "json",
                        data: {
                            'search': $("#keyword").val(),
                        },
                        success: function(data){
                            execute_Search();
                        },
                        error: function(data){
                        }
                    });
                });
                
                $("#logoutButton").on('click', function() {
                    window.location = "logout.php";
                });
               

        document.getElementById("account").onclick = function(){
            location.href = "account.php";
        }

    </script>
    

</body>
    
<footer><br>QuickTube&copy by Antonio, Daichi, Maximillian, and Ricky</footer>

</html>