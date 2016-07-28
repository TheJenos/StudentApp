<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>submit demo</title>
  <style>
  p {
    margin: 0;
    color: blue;
  }
  div,p {
    margin-left: 10px;
  }
  span {
    color: red;
  }
  </style>
  <script type="text/javascript" src="js/jquery-2.1.1.min.js"></script>
</head>
<body>
 
<p>Type 'correct' to validate.</p>
<form action="index.php">
  <div>
    <input type="text">
    <input type="submit">
  </div>
</form>
<span></span>
 
<script>
$( "form" ).submit(function( event ) {
  if ( $( "input:first" ).val() === "correct" ) {
    $( "span" ).text( "Validated..." ).show();
    return;
  }
 
  $( "span" ).text( "Not valid!" ).show().fadeOut( 1000 );
  event.preventDefault();
});
</script>
 
</body>
</html>