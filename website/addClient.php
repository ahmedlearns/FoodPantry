<?php
  ini_set("display_errors", "on");
            error_reporting(E_ALL);
            include "_header.php";
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- <link rel="shortcut icon" href="../../docs-assets/ico/favicon.png"> -->

    <title>FoodNotBombs - Add Client</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link type="text/css" href="css/genForm.css" rel="stylesheet">


    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../docs-assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
    <div class="navbar navbar-fixed-top navbar-inverse" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="home.html">FoodNotBombs</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="home.html">Home</a></li>
            <li><a href="about.html">About</a></li>
            <li><a href="contact.html">Contact</a></li>
          </ul>
        </div><!-- /.nav-collapse -->
      </div><!-- /.container -->
    </div><!-- /.navbar -->

   <!-- <center><img class="img-header" src="img/header/title.png" /></center> -->
    <div class="container">
      <div class="container">


      <div class="row row-offcanvas row-offcanvas-right">

        <div class="col-xs-12 col-sm-9">
          <p class="pull-right visible-xs">
            <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle nav</button>
          </p>

           <form class="form-genForm" method="post" action = "addClientHandler.php">
                <fieldset>

                <!-- Form Name -->
                <legend>Form Name</legend>

                <!-- Text input-->
                <div class="control-group">
                  <label class="control-label" for="textinput">First Name</label>
                    <input id="textinput" type="text" placeholder="First Name" name = "fname" class="input-xlarge">
                </div>

                <!-- Text input-->
                <div class="control-group">
                  <label class="control-label" for="textinput">Last Name</label>
                    <input id="textinput" type="text" placeholder="Last Name" name = "lname" class="input-xlarge">
                </div>

                <!-- Text input-->
                <div class="control-group">
                  <label class="control-label" for="textinput">Phone Number (1234567890)</label>
                    <input id="textinput" type="text" placeholder="Phone Number" name = "phone" class="input-xlarge">
                </div>

                <!-- Multiple Radios (inline) -->
                <div class="control-group">
                  <label class="control-label" for="radios">Gender</label>
                    <label class="radio inline" for="radios-0">
                      <input type="radio" name="gender" id="radios-0" value="m" checked="checked">
                      Male
                    </label>
                    <label class="radio inline" for="radios-1">
                      <input type="radio" name="gender" id="radios-1" value="f">
                      Female
                    </label>
                </div>

                <!-- Text input-->
                <div class="control-group">
                  <label class="control-label" for="textinput">Date of Birth</label>
                    <input id="textinput" type="date" placeholder="Date of Birth" name = "dob" class="input-xlarge">
                </div>

                <!-- Select Basic -->
                <div class="control-group">
                  <label class="control-label" for="selectbasic">Pick Up Day</label>
                    <select id="selectbasic" name="pday" class="input-xlarge">
                      <option>1</option>
                      <option>2</option>
                      <option>3</option>
                      <option>5</option>
                      <option>6</option>
                      <option>7</option>
                      <option>8</option>
                      <option>9</option>
                      <option>10</option>
                      <option>11</option>
                      <option>12</option>
                      <option>13</option>
                      <option>15</option>
                      <option>16</option>
                      <option>17</option>
                      <option>18</option>
                      <option>19</option>
                      <option>20</option>
                      <option>21</option>
                      <option>22</option>
                      <option>23</option>
                      <option>25</option>
                      <option>26</option>
                      <option>27</option>
                      <option>28</option>
                      <option>29</option>
                      <option>30</option>
                    </select>
                </div>

                <!-- Select Basic -->
                <div class="control-group">
                  <label class="control-label" for="selectbasic">Bag Type</label>
                  <?php
                    echo makeBagDropDown();
                  ?>
                </div>

                <!-- Text input-->
                <div class="control-group">
                  <label class="control-label" for="textinput">Street</label>
                    <input id="textinput" type="text" placeholder="Street" name = "street" class="input-xlarge">
                </div>

                <!-- Text input-->
                <div class="control-group">
                  <label class="control-label" for="textinput">City</label>
                    <input id="textinput" type="text" placeholder="City" name = "city" class="input-xlarge">
                </div>

                <!-- Text input-->
                <div class="control-group">
                  <label class="control-label" for="textinput">State (GA)</label>
                    <input id="textinput" type="text" placeholder="State" name = "state" class="input-xlarge">
                </div>

                <!-- Text input-->
                <div class="control-group">
                  <label class="control-label" for="textinput">Zip Code</label>
                    <input id="textinput" type="text" placeholder="Zip Code" name = "zip" class="input-xlarge">
                </div>

                <!-- Select Multiple -->
                <div class="control-group">
                  <label class="control-label" for="selectmultiple">Select Financial Aid (hold ctrl for multiple)</label>
                    <?php
                      echo makeFinaidList();
                    ?>
                </div>


                <!-- Button -->
                <div class="control-group">
                  <label class="control-label" for="singlebutton"></label>
                    <button id="singlebutton" name="singlebutton" class="btn btn-primary">Save</button>
                </div>

                </fieldset>
                </form>


        </div><!--/span-->
      </div><!--/row-->

      <hr>

      <footer>
        <p>&copy; Company 2013</p>
      </footer>

      

    
     
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
  </body>
</html>
