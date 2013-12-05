<?php
  include "_header.php";
  ini_set("display_errors", "on");
  error_reporting(E_ALL);
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

    <title>FoodNotBombs - Client Home</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/offcanvas.css" rel="stylesheet">

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

    <div class="container">
      <div class="row row-offcanvas row-offcanvas-right">

        <div class="col-xs-12 col-sm-9">
          <p class="pull-right visible-xs">
            <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle nav</button>
          </p>
          <div class="jumbotron">
            <h1>Hello, world!</h1>
            <!--we should probably put a username in here^ -->
            <p>We probably don't need text here.</p>
          </div>
          <div class="row">
            <table class="table table-bordered">
                <tr class="active">
                  <th>Product</th>
                  <th>Source</th>
                  <th>Quantity</th>
                </tr>
            </table>
          </div><!--/row-->

          <div class="row">
            <div class="span6">
                <div class="row-fluid">
                    <div class="span6">
                        <label>Product</label>
                        <div class="dropdown">
                            <?php echo makeProductDropDown(); ?>
                        </div>
                    </div>
                    <div class="span6">
                        <label>Source</label>
                    </div>
                    <div class="span6">
                        <label>Quantity</label>
                        <input type="text" class="span12" placeholder="">
                    </div>
                </div>
            </div>
          </div>

          <br /><br />
          
          <div class="row">
            <div class="span6">
                <div class="row-fluid">
                    <div class="span6">
                        <label>Product</label>
                        <div class="dropdown">
                            <?php echo makeProductDropDown(); ?>
                        </div>
                    </div>
                    <div class="span6">
                        <label>Source</label>
                    </div>
                    <div class="span6">
                        <label>Quantity</label>
                        <input type="text" class="span12" placeholder="">
                    </div>
                </div>
            </div>
          </div>

          <br /><br />

          <div class="row">
            <div class="span6">
                <div class="row-fluid">
                    <div class="span6">
                        <label>Product</label>
                        <div class="dropdown">
                            <?php echo makeProductDropDown(); ?>
                        </div>
                    </div>
                    <div class="span6">
                        <label>Source</label>
                    </div>
                    <div class="span6">
                        <label>Quantity</label>
                        <input type="text" class="span12" placeholder="">
                    </div>
                </div>
            </div>
          </div>

          <br /><br />

          <div class="row">
            <div class="span6">
                <div class="row-fluid">
                    <div class="span6">
                        <label>Product</label>
                        <div class="dropdown">
                            <?php echo makeProductDropDown(); ?>
                        </div>
                    </div>
                    <div class="span6">
                        <label>Source</label>
                    </div>
                    <div class="span6">
                        <label>Quantity</label>
                        <input type="text" class="span12" placeholder="">
                    </div>
                </div>
            </div>
          </div>

          <br /><br />
          
          <div class="row">
            <div class="span6">
                <div class="row-fluid">
                    <div class="span6">
                        <label>Product</label>
                        <!-- <div class="dropdown"> -->
                            <?php echo makeProductDropDown(); ?>
                        <!-- </div> -->
                    </div>
                    <div class="span6">
                        <label>Source</label>
                    </div>
                    <div class="span6">
                        <label>Quantity</label>
                        <input type="text" class="span12" placeholder="">
                    </div>
                </div>
            </div>
          </div>

        </div><!--/span-->

        <div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
          <div class="list-group">
            <a href="#" class="list-group-item active">Link</a>
            <a href="#" class="list-group-item">Link</a>
            <a href="#" class="list-group-item">Link</a>
            <a href="#" class="list-group-item">Link</a>
            <a href="#" class="list-group-item">Link</a>
            <a href="#" class="list-group-item">Link</a>
            <a href="#" class="list-group-item">Link</a>
            <a href="#" class="list-group-item">Link</a>
            <a href="#" class="list-group-item">Link</a>
            <a href="#" class="list-group-item">Link</a>
          </div>
        </div><!--/span-->
      </div><!--/row-->
      
      <hr>

      <footer>
        <p>&copy; Company 2013</p>
      </footer>

    </div><!--/.container-->



    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="../../dist/js/bootstrap.min.js"></script>
    <script src="offcanvas.js"></script>
  </body>
</html>
