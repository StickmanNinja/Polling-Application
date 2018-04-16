<html>
	<head>
		<style>
			html,body {
				width: 100%;
				height: 100%;
			}
			.poll {
				width: 600px;
				margin: auto;
				text-align: center;
			}
			h1 {
				color: #404040;
				font-size: 30px;
			}
			.nextButton {
				width: 100%;
				padding: 15px;
				background-color: #8c0101;
				color: #fff;
				border: none;
				font-size: 1.25rem;
				margin-bottom: 10px;
				width: 450px;
				font-size: 30px;
			}
			
			ul.pl_poll__answers-list{padding-left:10%;}.nextButton,button.pl_submit-form__btn{font-size:2.25rem;padding:10px;}#pl_quiz_email{font-size:2rem;width:100%;height:3.5rem;padding:15px;}.pl_petition-form__input{width:100%;}label.pl_petition_newsletter{text-align:left;font-size:1.25rem;}label.pl_petition_newsletter>span{margin-left:5px;}@media(min-width:1024px){.pl_poll-app-wrapper,.petition-progress{width:75%;margin:auto;}}
		</style>
		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	</head>
<body>
    
<div class = "poll">
	<h1>POLL: Should John McCain Resign?</h1>
	<form action="poll.php" method="post">
	<input type="radio" name="vote" value="yes"> Yes.<br><br>
	<input type="radio" name="vote" value="no"> No.<br><br>
	<input type="submit" class = "nextButton">
	</form>
</div>
<div id='piechart' style='width: 600px; height: 500px; margin: auto; text-align: center;'></div>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



$servername = "";		  // Use your servername.
$username = "";			  // Use your username.
$password = "";		      // Use your password.
$dbname = "";             // Choose your database.
$pollname = "JohnMcCain"; // Name it something.

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

if (isset($_POST["vote"])) {
	if ($_POST["vote"] == "yes") {
		addYes();
		echo "<script>alert('Give me your email address')</script>";
		printResultChart();
		
	}
	if ($_POST["vote"] == "no") {
		addNo();
		printResultChart();
	}
} 	
	
function addDefaultTable($name) {
	global $conn;
	$sql = "CREATE TABLE IF NOT EXISTS `$name` (
	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	name text NOT NULL,
	yes int(100) NOT NULL,
	no int(100) NOT NULL
	)";

	if ($conn->query($sql) === TRUE) {
    	echo "Table created successfully";
	} else {
	    echo "Error creating table: " . $conn->error;
	}

}

// The default value for yes and no is 0
function addPoll() {
	global $pollname;
	global $conn;
	$sql = "INSERT INTO `polls` (name, yes, no) VALUES ('$pollname', 0, 0)";
	if ($conn->query($sql) === TRUE) {
    	echo "Poll Added <br>";
	} else {
	    echo "Error creating table: " . $conn->error;
	}
}

function addYes() {
	global $conn;
	global $pollname;
	$sql = "UPDATE polls SET yes = yes + 1 WHERE name = '$pollname'";
	$query = $conn->query($sql);
	if ($query === TRUE) {
    	echo "";
	} else {
	    echo "Error creating voting: " . $conn->error;
	}
}

function addNo() {
	global $conn;
	global $pollname;
	$sql = "UPDATE polls SET no = no + 1 WHERE name = '$pollname'";
	$query = $conn->query($sql);
	if ($query === TRUE) {
    	echo "";
	} else {
	    echo "Error creating voting: " . $conn->error;
	}
}



function getallvotes() {
	global $conn;
	global $pollname;
	$sql = "SELECT * FROM polls WHERE name = '" . $pollname . "'";
	$result = $conn->query($sql);
	$pollnumber = mysqli_fetch_array($result);
	return [$pollnumber["yes"], $pollnumber["no"]];
}
	
function printResultChart() {
	$votes = getallvotes();
	$stringy = "['Yes'," . intval($votes[0]) . "],['No'," . intval($votes[1]) . "]";
	$boringjscode = "<script type='text/javascript'>
	async function asyncCall() {
	google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Answer', 'Votes'],
          " . $stringy . "
        ]);

        var options = {
          title: 'Results So Far...'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
	  }
	  asyncCall();
	  </script>";
	echo $boringjscode;
}

?>

</body>
</html>