<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    
    <!-- Style -->
    <link rel="stylesheet" href="css/style.css" />
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500' rel='stylesheet' type='text/css'>
    
    <!-- Meta + Title -->
    <title>Quentin Brunet | Pickl – Start a campaign</title>
</head>
<body>
  <script src="js/jquery.js"></script>
  <script src="js/script.js"></script>
    
  
  
   <nav>
      <div id="logo"><a href="index.php"><img src="assets/img/logo.png"/></a></div>
       
       <ul class="animnav">
           <li><a href="explore.php">Explore</a></li>
           <li><a href="how-it-works.php">How it works</a></li>
           <li id="campaignstart"><a href="start-a-campaign.php">Start a campaign</a></li>
           <div id="search"><img src="assets/img/search.png"/><input type="text" placeholder="Search"></div>
       </ul>
       
       <ul id="rightnav" class="animnav">
          <li><a href="sign-up.php">Sign Up</a></li>
          <li><a href="mon-compte.php">Log In</a></li>
       </ul>
       <div id="navrank">
           <div class="rankbuttons" id="reward"><a href="reward.php"><img src="assets/img/reward.png"/></a></div>
           <div class="rankbuttons" id="statistics"><a href="statistics.php"><img src="assets/img/statistics.png"/></a></div>
       </div>
   </nav>
   
   <div id="navtwo">
        <div id="navprofil">
            <h1>NEW PROJECT ?</h1>
            <div class="sep"></div>
        </div>
        <ul>
            <li><a href="#">VIDEO TUTORIAL</a></li>
            <li><a href="#">HOW IT WORKS</a></li>
            <li><a href="#">SUMMARY</a></li>
            <li><a href="#activity">1. ACTIVITY</a></li>
            <li><a href="#projects">2. MY PROJECTS</a></li>
            <li><a href="#contributions">3. MY CONTRIBUTIONS</a></li>
            <li><a href="#likes">4. MY LIKES</a></li>
        </ul>
    </div><!--
   
    --><div id="content">
    <div id="contentprofil">
       <div id="compteimgtop">
           <h1>Welcome !</h1>
           <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facilis, consequatur! Quae non voluptatibus a laudantium, perspiciatis, doloremque ullam temporibus aspernatur illum rem recusandae nesciunt perferendis iste nemo ab nulla lorem ipsum dolor sit amet.<br><br> consectetur adipisicing elit. Eius amet quis ut dolorum, itaque, dolore harum, eaque totam beatae asperiores quasi rem earum hic, sunt ea et necessitatibus atque.</p>
       </div>
       
       <div id="activity" class="titleprofil">
           <h2>1. BASIC INFORMATION // <strong>Lorem ipsum dolor sit amet.</strong></h2>
           <div class="sep"></div>
       </div>
       
       <form method="get" action="traitement.php" class="startform">
           <ul>
              <div class="sizeform">
                <li><p>Project title</p><div class="formdouble"><input type="text" name="" value ="" class="longinput"></div></li>
               <li><p>Requested Amount</p><div class="formdouble"><input type="text" name="" value ="" class="smallinput"></div></li>
               <li><p>Tiny Description</p><div class="formdouble"><textarea rows="4" cols="50" class="largeinput" placeholder="150 characters max."></textarea></div></li>
               <li><p>Campaign Duration</p><div class="formdouble"><input type="text" name="" value ="" class="smallinput"></div></li>
               <li><p>Country</p><div class="formdouble"><input type="text" name="" value ="" class="smallinput"></div></li>
              </div>
              <div class="titleprofil">
           <h2>2. PITCH // <strong>Lorem ipsum dolor sit amet.</strong></h2>
           <div class="sep"></div>
       </div>
              <div class="sizeform">
                  <li><p>Pitch</p><div class="formdouble"><textarea rows="20" cols="50" class="largeinput"></textarea></div></li>
               </div>
               <div class="titleprofil">
           <h2>3. SELECT CATEGORY // <strong>Lorem ipsum dolor sit amet.</strong></h2>
           <div class="sep"></div>
       </div>
                              <div class="sizeform">
               <li><p>Category</p><div class="formdouble"><select name="categorie">
                   <option>test</option>
                   <option>test</option>
                   <option>test</option>
                   <option>test</option>
                   <option>test</option>
                   <option>test</option>
                   </select></div></li>
               </div>
               <div class="titleprofil">
           <h2>4. COUNTERPARTS // <strong>Lorem ipsum dolor sit amet.</strong></h2>
           <div class="sep"></div>
       </div>
              <div class="sizeform">
                   <li><p>Title Contrepartie 1</p><div class="formdouble"><input type="text" name="" value ="" class="longinput"></div></li>
                   <li><p>Montant</p><div class="formdouble"><input type="text" name="" value ="" class="longinput"></div></li>
                   <li><p>Résumé</p><div class="formdouble"><textarea rows="10" cols="50" class="largeinput"></textarea></div></li>
                   <li><button>More</button></li>
               </div>
           </ul>
       </form>
       
       
       
       
    </div>
       
       
       
       
    </div>
    
   
   <div id="sponsorscontent">
     <div id="sponsors">
      <h2>SPONSORS</h2>
      
      <div class="sponsor"><a href="http://www.nike.com/" target="_blank"><img src="assets/img/sponsor1.png"/></a></div>
      <div class="sponsor"><a href="http://www.canon.fr/" target="_blank"><img src="assets/img/sponsor2.png"/></a></div>
      <div class="sponsor"><a href="http://www.lemonde.fr/" target="_blank"><img src="assets/img/sponsor3.png"/></a></div>
      <div class="sponsor"><a href="http://www.estac.fr/" target="_blank"><img src="assets/img/sponsor4.png"/></a></div>
      <div class="sponsor"><a href="http://www.kelloggs.fr/" target="_blank"><img src="assets/img/sponsor5.png"/></a></div>
       
   </div>
        </div>
   
   <div id="create">
       <a href="start-a-campaign.php">CREATE YOUR OWN PROJECT !</a>
   </div>
   
   <footer>
       <div id="footercontainer">
           <div class="linkfooter">
               <a href="search.php">SEARCH</a><br>
               <a href="faq.php">FAQ</a><br>
               <a href="about.php">ABOUT</a><br>
               <a href="rewards.php">REWARDS</a><br>
               <a href="awards.php">AWARDS</a><br>
               <a href="ranking.php">RANKING</a>
           </div>
           
           <div class="linkfooter">
               <a href="how-it-works.php">HOW IT WORKS</a><br>
               <a href="start-a-project.php">START A PROJECT</a><br>
               <a href="explore.php">EXPLORE</a><br>
               <a href="my-account.php">MY ACCOUNT</a>
           </div>
           
           <div id="socialfooter">
              <h4>Subscribe to the newsletter</h4>
              <form>
                  <input id="subfooter" type="text" placeholder="Mail...">
                  <input id="subvalid" type="submit" name="Subscribe" value="Subscribe">
              </form>
              
              <div id="socialcontainer">
              <h4>Follow us</h4>
              <div id="socialbutton">
                 <a href="http://facebook.com"><img src="assets/img/fb.png"/></a>
                 <a href="http://twitter.com"><img src="assets/img/twitter.png"/></a>
                 <a href="http://youtube.com"><img src="assets/img/yt.png"/></a>
                 <a href="http://instagram.com"><img src="assets/img/insta.png"/></a>
                  
              </div>
               </div>
           </div>
           
           
           
       </div>
       <div id="footerlogo">
           <img src="assets/img/logo.png"/>
       </div>
   </footer>
   
   
    </div>
   
   
   
</body>
</html>