<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Getting started with the php Database class.">
    <meta name="author" content="Shane T. Luna">
    <!--<link rel="icon" href="../../favicon.ico">-->

    <title>dbClass</title>

    <!-- Bootstrap core CSS -->
    <link href="./bootstrap-4.0.0-dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="./assets/css/main-style.css" rel="stylesheet">
  </head>

  <body>
    <div class="container">

      <h1>Quick Start to PHP Database class</h1>
      <p class="lead">Quick walkthrough on using the PHP Database class.<br>
        Click <a href="http://dbclass.dcloudweb.sempra.com/docs/classes/Database.html" target="_blank">here</a> to see full documentation on database class methods.</p>
        
        <hr>
        
    <h3>FULL EXAMPLE</h3>
      <p>Code below can be used for quick reference. Output is below code block.</p>
        
      <div class="row">
        <div class="col-sm-12">
            <code style="display:block;"><span class="o">include_once</span><span class="w">(</span><span class="b">$_SERVER</span><span class="w">[</span><span class="g">"DOCUMENT_ROOT"</span><span class="w">] . </span><span class="g">"/exampleDir/database.class.php"</span><span class="w">);</span><br>
            <span class="b">$database</span> <span class="w">=</span> <span class="o">new</span> <span class="lb">Database</span><span class="w">(</span><span class="g">"sqlsrv"</span><span class="w">,</span> <span class="g">"sq-entxx-dxx\dev"</span><span class="w">,</span> <span class="g">"HR_Repository"</span><span class="w">);</span><br>
            <span class="b">$database</span><span class="w">-></span><span class="lb">query</span><span class="w">(</span><span class="g">"SELECT first_name, last_name FROM myTable WHERE EmpNo = :empid AND Job_Title = 'intern'"</span><span class="w">);</span><br>
            <span class="b">$database</span><span class="w">-></span><span class="lb">bindValue</span><span class="w">(</span><span class="g">":empid"</span><span class="w">,</span> <span class="g">"86487"</span><span class="w">);</span><br>
            <span class="o">echo</span> <span class="b">$database</span><span class="w">-></span><span class="lb">toJSON</span><span class="w">(</span><span class="b">$database</span><span class="w">-></span><span class="lb">XAGsingle</span><span class="w">());</span><br>
            <span class="b">$database</span><span class="w">-></span><span class="lb">disconnect</span><span class="w">();</span></code>
        </div>
        <div>
            <p>
                <?php 
                    include_once($_SERVER["DOCUMENT_ROOT"] . "/database.class.php");
                    $database = new Database("sqlsrv", "sq-ent12-d01\dev", "HR_Repository");
                    $database->query("SELECT FIRST_NAME, LAST_NAME FROM resource_denorm WHERE EmpNo = :empid AND Resource_Status =  'A'");
                    $database->bindValue(":empid", "86487");
                    echo $database->toJSON($database->XAGsingle());
                    $database->disconnect();
                ?>
            </p>
        </div>
      </div>
        
        <hr>

      <h3>1. Include database.class.php File</h3>
      <p>Use <strong>$_SERVER["DOCUMENT_ROOT"]</strong> or <strong>dirname(__FILE__)</strong> to solve any php relative path problems.</p>

      <div class="row">
        <div class="col-sm-12">
            <code><span class="o">include_once</span><span class="w">(</span><span class="b">$_SERVER</span><span class="w">[</span><span class="g">"DOCUMENT_ROOT"</span><span class="w">] . </span><span class="g">"/exampleDir/database.class.php"</span><span class="w">);</span></code>
        </div>
        <div class="col-sm-12">
            <code><span class="o">include_once</span><span class="w">(</span><span class="b">dirname</span><span class="w">(</span><span class="tl">__FILE__</span><span class="w">) . </span><span class="g">"/../exampleDir/database.class.php"</span><span class="w">);</span></code>
        </div>
      </div>
        
        <hr>

      <h3>2. Create Instance of Database Object</h3>
      <p>You can pass credentials or omit and use Windows Authentication via Custom Application Pool Identity within Azure.</p>
      <div class="row">
        <div class="col-sm-12">
            <code><span class="b">$database</span> <span class="w">=</span> <span class="o">new</span> <span class="lb">Database</span><span class="w">(</span><span class="g">"dsn"</span><span class="w">,</span> <span class="g">"server"</span><span class="w">,</span> <span class="g">"database"</span><span class="w">,</span> <span class="g">"user"</span><span class="w">,</span> <span class="g">"pass"</span><span class="w">);</span></code>
        </div>
        <div class="col-sm-12">
            <code><span class="b">$database</span> <span class="w">=</span> <span class="o">new</span> <span class="lb">Database</span><span class="w">(</span><span class="g">"mysql"</span><span class="w">,</span> <span class="g">"sq-azmgmt-dxxx"</span><span class="w">,</span> <span class="g">"testDB"</span><span class="w">,</span> <span class="g">"sluna"</span><span class="w">,</span> <span class="g">"pass123"</span><span class="w">);</span></code>
        </div>
        <div class="col-sm-12">
            <code><span class="b">$database</span> <span class="w">=</span> <span class="o">new</span> <span class="lb">Database</span><span class="w">(</span><span class="g">"sqlsrv"</span><span class="w">,</span> <span class="g">"sq-entxx-dxx\dev"</span><span class="w">,</span> <span class="g">"HR_Repository"</span><span class="w">);</span></code>
        </div>
      </div>
        
        <hr>

      <h3>3. Query &amp; Bind</h3>
      <p>Query method takes query string and prepares statment for you. Have options to bind value or parameter.<br>
      If you omit type within bind, type will be determined automatically for you.</p>
      <div class="row">
        <div class="col-sm-12">
            <code><span class="b">$database</span><span class="w">-></span><span class="lb">query</span><span class="w">(</span><span class="g">"SELECT * FROM myTable WHERE EmpNo = :empid AND Job_Title = 'intern'"</span><span class="w">);</span></code>
        </div>
        <div class="col-sm-12">
            <code><span class="b">$database</span><span class="w">-></span><span class="lb">bindValue</span><span class="w">(</span><span class="g">":empid"</span><span class="w">,</span> <span class="g">"86487"</span><span class="w">,</span> <span class="lb">PDO</span><span class="w">::</span><span class="lb">PARAM_STR</span><span class="w">);</span><br>&nbsp;<span class="b">$database</span><span class="w">-></span><span class="lb">bindValue</span><span class="w">(</span><span class="g">":empid"</span><span class="w">,</span> <span class="g">"86487"</span><span class="w">);</span></code>
        </div>
        <div class="col-sm-12">
            <code><span class="b">$database</span><span class="w">-></span><span class="lb">bindParam</span><span class="w">(</span><span class="g">":empid"</span><span class="w">,</span> <span class="b">$myID</span><span class="w">,</span> <span class="lb">PDO</span><span class="w">::</span><span class="lb">PARAM_STR</span><span class="w">);</span><br>&nbsp;<span class="b">$database</span><span class="w">-></span><span class="lb">bindParam</span><span class="w">(</span><span class="g">":empid"</span><span class="w">,</span> <span class="g">$myID</span><span class="w">);</span></code>
        </div>
      </div>
        
        <hr>
        
      <h3>4. Execute/eXecute And Get</h3>
      <p>Execute method will execute your prepared statement. XAG methods will eXecute And Get.<br>
        XAGsingle gets the single/first row returned. XAGresultSet gets all the rows.<br>
        Use toJSON method to get an organized/readable return for better use.</p>
      <div class="row">
        <div class="col-sm-12">
            <code><span class="b">$database</span><span class="w">-></span><span class="lb">execute</span><span class="w">();</span></code>
        </div>
        <div class="col-sm-12">
            <code><span class="o">echo</span> <span class="b">$database</span><span class="w">-></span><span class="lb">toJSON</span><span class="w">(</span><span class="b">$database</span><span class="w">-></span><span class="lb">XAGsingle</span><span class="w">());</span></code>
        </div>
        <div class="col-sm-12">
            <code><span class="o">echo</span> <span class="b">$database</span><span class="w">-></span><span class="lb">toJSON</span><span class="w">(</span><span class="b">$database</span><span class="w">-></span><span class="lb">XAGresultset</span><span class="w">());</span></code>
        </div>
      </div>

    </div> <!-- /container -->

    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <!-- Bootstrap core JavaScript -->
    <script src="./assets/js/clipboard.min.js"></script>
    <!-- ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="./assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
