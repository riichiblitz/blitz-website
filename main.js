
var delay = 5000;
var status = "";
var knownStatuses = ["reg", "confirm", "pause", "play", "end"];

var maxRounds = 2;
var playerPerTable = 4;

function setRegStatus() {
	delay = 20000;
	$(".confirm").hide(500);
	$('.results_table').hide(0);
	//$(".common").show(500);
	$(".info").show(500);
	$("#open_form").show(500);
	$(".register_form").hide(500);
	$(".footer").show(500);
	$(".registration_end").hide(500);
	$(".registration_message_ok").hide(500);
	$(".registration_message_error").hide(500);
	$(".report_form").hide(0);
	$(".message_ok").hide(0);
	$(".end").hide(0);
	updateApplications();
}

function setConfirmStatus() {
	delay = 5000;
	$(".common").show(0);
	$('.results_table').hide(0);
	updateConfirmations();
	$(".info").hide(500);
	$("#open_form").hide(500);
	$(".register_form").hide(500);
	$(".footer").show(500);
	$(".registration_end").show(500);
	$(".registration_message_ok").hide(500);
	$(".registration_message_error").hide(500);
	$('.applications').hide(500);
	$('.confirmations').hide(500);
	$(".confirm").show(500);
	$(".report_form").hide(0);
	$(".message_ok").hide(0);
	$(".end").hide(0);
}

function setRunningStatus() {
	delay = 10000;
	$(".results_table").show(500);
	$(".confirm").hide(0);
	$(".reg").hide(0);
	$(".common").show(0);
	$(".lobby").show(0);
	$(".report_form").hide(0);
	$(".message_ok").hide(0);
	$(".end").hide(0);
	updateResults();
}

function setEndStatus() {
	delay = 1000000000;
$(".common").show(0);
$(".results_table").show(500);
$(".confirm").hide(0);
$(".report_form").hide(0);
$(".message_ok").hide(0);
$(".end").show(500);
updateTotals();
}

function updateApplications() {
	$.ajax({
		url: "../api/players"
	}).done(function(data) {
		if (data.status === "ok") {
			$('.applications_count').text(data.data.count);
			$('.applicant_names').text("");
			if (data.data.names.length > 0) {
				data.data.names.forEach(function(item, i, arr) {
					$('.applicant_names').append(item);
					if (i < arr.length - 1) {
						$('.applicant_names').append(' ');
					}
				});
			}
			$('.applications').show(500);
		}
	});
}

function updateConfirmations() {
	$.ajax({
		url: "../api/confirmed"
	}).done(function(data) {
		if (data.status === "ok") {
			$('.confirmations_count').text(data.data.count);
			$('.confirmations_names').text("");
			if (data.data.names.length > 0) {
				data.data.names.forEach(function(item, i, arr) {
					$('.confirmations_names').append(item);
					if (i < arr.length - 1) {
						$('.confirmations_names').append(' ');
					}
				});
			}
			$('.confirmations').show(500);
		}
	});
}

function updateResults() {
	$.ajax({
		url: "../api/results"
	}).done(function(data) {
		if (data.status === "ok") {
			$('.results_table tr').remove();
			//$item->player, $item->state, $item->place, $item->score, $item->id
			var playersPerRound = data.data.length / maxRounds;
			var html = "<tr>";
			for (var i = 0; i < maxRounds; i++) {
				html+="<td><table class=\"round_table\"><tr><td>";
				html+=(i+1);
			  html+="</td></tr>";
				console.log(playersPerRound);
				for (var j = 0; j < playersPerRound; j+=playerPerTable) {
					html+="<tr><td><table border=\"1\">";
					for (var k = 0; k < playerPerTable; k++) {
						html+="<tr>";
						if (k == 0) {
							html+="<td rowspan=\"5\">1</td>";
						}
						html+="<td>"+(k+1)+"</td><td>";
						var values = data.data[i*playersPerRound + j*playerPerTable + k];
						var name = values[0];
						var state = values[1];
						var score = values[3];
						if (state == "no") {
							html += "<font color=\"#FF0000\">"
						}
						html += name;
						if (state == "no") {
							html += "</font>"
						}
						html+="</td><td>" + (score == null ? "—" : score) + "</td>";
						html+="</tr>";
					}
					html+="<tr><td colspan=\"3\">No replay</td></tr>";
					html+="</table></td></tr>";
				}
				html+="</table></td>";
			}
			html+="</tr>";
			console.error(html);
			$(".results_table > tbody").append(html);
		}
	});
}

function updateTotals() {
	$.ajax({
		url: "../api/totals"
	}).done(function(data) {
		if (data.status === "ok") {
			$('.results_table tr').remove();
			//$item->player, $item->score
			var playersPerRound = data.data.length / maxRounds;
			var html = "<tr><td><table class=\"round_table\">";
			for (var i = 0; i < data.data.length; i++) {
				var values = data.data[i];
				var name = values[0];
				var score = values[1];
				html+="<tr><td>";
				html+=(i+1);
			  html+="</td><td>" + name + "</td><td>" + score + "</td></tr>";
			}
			html+="</table></td></tr>";
			console.error(html);
			$(".results_table > tbody").append(html);
		}
	});
}

function updateStatus() {
	$.ajax({
		url: "../api/status"
	}).done(function(data) {
		if (data.status === "ok") {
			if (status !== data.data.status) {
				status = data.data.status;
				if (!knownStatuses.includes(status)) {
					window.location.reload(true);
				} else {
					switch (status) {
						case "reg": setRegStatus(); break;
						case "confirm": setConfirmStatus(); break;
						case "pause":
						case "play": setRunningStatus(); break;
						case "end": setEndStatus(); break;
					}
				}
			} else {
				switch (status) {
					case "reg": updateApplications(); break;
					case "confirm": updateConfirmations(); break;
					case "pause":
					case "play": updateResults(); break;
					case "end": updateTotals(); break;
				}
			}
		}
	}).always(function() {
		setTimeout(updateStatus, delay);
	});
}

function apply() {
	$.ajax({
		type: "POST",
		data: JSON.stringify({ name: $('#tenhou_nick').val(), contacts: $('#contacts').val(), notify: $('#notify').is(':checked') ? 1 : 0, anonymous: $('#anonymous').is(':checked') ? 1 : 0 }),
		url: "../api/apply"
	}).done(function(data) {
		if (data.status === "ok") {
			$(".registration_message_ok").show(500);
			$(".registration_message_error").hide(500);
			$(".register_form").hide(500);
			updateApplications();
		} else {
			$(".registration_message_error").show(500);
		}
	});
}

function report() {
	$.ajax({
		type: "POST",
		data: JSON.stringify({ name: $('#who_am_i').val(), message: $('#message').val() }),
		url: "../api/report"
	}).done(function(data) {
		if (data.status === "ok") {
			$(".message_ok").show(500);
		  $(".report_form").hide(500);
		}
	});
}

$(document).ready(function() {
	$(".reg").hide(0);
	$(".confirm").hide(0);
	//$(".common").hide(0);
	$(".report_form").hide(0);

	// $(".info").hide(0);
	// $("#open_form").hide(0);
	// $(".register_form").hide(0);
	// $(".applications").hide(0);
	// $(".footer").hide(0);
	// $(".registration_end").hide(0);
	// $(".registration_message_ok").hide(0);
	// $(".registration_message_error").hide(0);
	$('.confirmations').hide(0);
	$('.results_table').hide(0);
	$(".end").hide(0);


	$("#open_form").click(function(){
		$("#open_form").hide(500);
		$(".register_form").show(500);
	});

	$("#open_report").click(function(){
		$(".report_form").show(500);
		$(".message_ok").hide(500);
	});

	$("#submit_form").click(apply);
	$("#submit_report").click(report);

	updateStatus();
});
