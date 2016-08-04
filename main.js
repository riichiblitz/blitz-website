
var delay = 5000;
var status = "";
var knownStatuses = ["reg"];

function setNewStatus() {
	$(".info").show(500);
	$("#open_form").hide(500);
	$(".register_form").hide(500);
	$(".footer").show(500);
	$(".registration_end").show(500);
	$(".registration_message_ok").hide(500);
	$(".registration_message_error").hide(500);
	updateApplications();
}

function setRegStatus() {
	delay = 20000;
	$(".info").show(500);
	$("#open_form").show(500);
	$(".register_form").hide(500);
	$(".footer").show(500);
	$(".registration_end").hide(500);
	$(".registration_message_ok").hide(500);
	$(".registration_message_error").hide(500);
	updateApplications();
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
						case "new": setNewStatus(); break;
					}
				}
			} else {
				switch (status) {
					case "reg": updateApplications(); break;
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

$(document).ready(function() {

	$(".info").hide(0);
	$("#open_form").hide(0);
	$(".register_form").hide(0);
	$(".applications").hide(0);
	$(".footer").hide(0);
	$(".registration_end").hide(0);
	$(".registration_message_ok").hide(0);
	$(".registration_message_error").hide(0);


	$("#open_form").click(function(){
		$("#open_form").hide(500);
		$(".register_form").show(500);
	});

	$("#submit_form").click(apply);

	updateStatus();
});
