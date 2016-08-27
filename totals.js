
function setEndStatus() {
	delay = 1000000000;
	$(".results").hide(0);
	$(".pause").hide(0);
$(".common").show(0);
$(".results_table").show(500);
$(".confirm").hide(0);
$(".report_form").hide(0);
$(".message_ok").hide(0);
$(".end").show(500);
updateTotals();
}

function updateTotals() {
	$.ajax({
		url: "../api/totals"
	}).done(function(data) {
		if (data.status === "ok") {
			$('.results_table tr').remove();
			//$item->player, $item->total,  $item->score, $item->place / $maxRounds
			var playersPerRound = data.data.length / maxRounds;
			var html = "<tr><td><table class=\"round_table\" border=\"1\">";
			for (var i = 0; i < data.data.length; i++) {
				var values = data.data[i];
				var name = values[0];
				var total = values[1];
				var score = values[2];
				var place = values[3];
				html+="<tr><td>";
				html+=(i+1);
			  html+="</td><td>" + name + "</td><td>" + (total == null ? "—" : total) + "</td><td>" + (score == null ? "—" : score) + "</td><td>" + (place == null ? "—" : place) + "</td></tr>";
			}
			html+="</table></td></tr>";
			console.error(html);
			$(".results_table > tbody").append(html);
		}
	});
}
