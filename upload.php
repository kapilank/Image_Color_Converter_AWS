<html>
<head>
<title>Uploaded Image</title>
<style>
</style>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
<div class="jumbotron" ><h1><center>Upload</center></h1></div>
<div>
  <h1><center> Please select an option</center></h1>
</div>
<div class><center>
<h2><span class="glyphicon glyphicon-home"></span> <a class="active" href="/index.php">Home</a></h2>
<h2><span class="glyphicon glyphicon-camera"></span> <a href="/gallery.php">Gallery</a></h2>
<h2><span class="glyphicon glyphicon-cloud-upload"></span> <a href="/upload.php">Upload</a></h2>
<h2><span class="glyphicons glyphicons-dashboard"></span><a href="/DatabaseUI.php">Database Dashboard</a></h2>
<h2><span class="glyphicons glyphicons-dashboard"><a href="/SQSDashboardUI.php">SQS Dashboard</a></h2>
<h2><span class="glyphicons glyphicons-dashboard"><a href="/CPUUtilizationUI.php">CPU Utilization Dashboard</a></h2>
</center>
</div>
<div>
<form action="image-processing.php" method="post" enctype="multipart/form-data">
   <center> <h3>Please select an image to upload:</h3>
    <br>
    <br>
    <input type="file" class="btn btn-primary btn-md" name="fileToUpload[]" id="fileToUpload" multiple="multiple">
    <br>
    <br>
    <input class="button btn btn-success btn-lg" type="submit" value="Upload Image" name="submit">
</center>
</form>
</div>
</div>
</body>
</html>
