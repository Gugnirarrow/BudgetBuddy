<?php
require "../db.php";
$query = "select sum(amount) as total from transactions group by category_id order by category_id";
$exec = mysqli_query($conn,$query);
$data = array();
$num = 0;

while($fetch = mysqli_fetch_assoc($exec)){
	$data[$num] = $fetch['total'];
	$num++;
}

$dataPoints = array( 
	array("label"=>"Entertainment", "y"=>$data[0]),
	array("label"=>"Expense", "y"=>$data[1]),
)

?>
<!DOCTYPE HTML>
<html>
<head>
<script>
window.onload = function() {

var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	title: {
		text: "Usage Share of Desktop Browsers"
	},
	subtitles: [{
		text: "November 2017"
	}],
	data: [{
		type: "pie",
		yValueFormatString: "\"RM\"#,##0.00",
		indexLabel: "{label} ({y})",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();

}
</script>
</head>
<body>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</body>
</html>                              