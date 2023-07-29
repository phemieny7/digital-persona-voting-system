<?php
include('header.php');
include('dbconn2.php');
session_start();

if (!isset($_SESSION['username'])) {
	header('location: adminLogin.php');
}
?>

<!DOCTYPE html>
<html>

<head>
	<title>Voters - FVS</title>
	<link rel="icon" href="images/logo.png">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
	<div class="container">
		<div class="row" style="margin-top: 10px">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body bg-light text-danger">
						<p style="text-align: center; margin-bottom: 0px; font-weight: bold">
							Device(s) must be turned on while deleting fingerprints!!
						</p>
					</div>
				</div>
			</div>
			<div class="col-md-12" style="margin-top: 10px">
				<div class="card">
					<div class="card-body bg-light text-dark">
						<div class="form-group">
							<label style="font-weight: bold">Voter Area</label>
							<select type="text" name="voterarea" id="voterarea" class="form-control"></select>
							<span id="error_voterarea" class="text-danger"></span>
						</div>
						<div class="text-center" style="margin-top: 20px; margin-bottom: 0px">
							<button style="font-weight: bold; font-size: 15px" class="btn btn-sm btn-warning" onClick="loadVoters()">Show Voters</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="voter_records"></div>
		<div class="modal" id="updateModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Edit Voter</h4>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label>Voter Name:</label>
							<input type="text" name="newname" id="newname" class="form-control" placeholder="Enter Voter Name..">
						</div>
						<div class="form-group">
							<label>Age:</label>
							<input type="text" name="newage" id="newage" class="form-control" placeholder="Enter Age..">
						</div>
						<div class="form-group">
							<label>Address:</label>
							<input type="text" name="newaddress" id="newaddress" class="form-control" placeholder="Enter Address..">
						</div>
						<div class="form-group">
							<label>Mobile No.:</label>
							<input type="text" name="newmobile" id="newmobile" class="form-control" placeholder="Enter Mobile Number..">
						</div>
						<div class="form-group">
							<label>Voter Area:</label>
							<select type="text" name="newvoterarea" id="newvoterarea" class="form-control"></select>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-warning btn-sm" data-dismiss="modal" onclick="updateVoterDetails()">Update</button>
						<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
						<input type="hidden" id="hidden_voter_id">
					</div>
				</div>
			</div>
		</div>
		<!-- <div class="modal" id="deleteFingerprint">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Delete Fingerprint</h4>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label>Status:</label>
							<div id="status_area"></div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" style="font-weight: bold" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div> -->

		<section>
			<!--Verify Identity Section-->
			<div id="verifyIdentity" class="modal fade" data-backdrop="static" tabindex="-1" aria-labelledby="verifyIdentityTitle" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h3 class="modal-title my-text my-pri-color" id="verifyIdentityTitle">Identity Verification</h3>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearCapture()">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form action="#" onsubmit="return false">
								<div id="verifyIdentityStatusField" class="text-center">
									<!--verifyIdentity Status will be displayed Here-->
								</div>
								<div class="form-row mt-3">
									<div class="col mb-3 mb-md-0 text-center">
										<label for="verifyReaderSelect" class="my-text7 my-pri-color">Choose Fingerprint Reader</label>
										<select name="readerSelect" id="verifyReaderSelect" class="form-control" onclick="beginIdentification()">
											<option selected>Select Fingerprint Reader</option>
										</select>
									</div>
								</div>
								<div class="form-row mt-4">
									<div class="col mb-md-0 text-center">
										<label for="userIDVerify" class="my-text7 my-pri-color m-0">Specify UserID</label>
										<input type="text" id="userIDVerify" class="form-control mt-1" required>
									</div>
								</div>
								<div class="form-row mt-3">
									<div class="col text-center">
										<p class="my-text7 my-pri-color mt-1">Capture Verification Finger</p>
									</div>
								</div>
								<div id="verificationFingers" class="form-row justify-content-center">
									<div id="verificationFinger" class="col mb-md-0 text-center">
										<span class="icon icon-indexfinger-not-enrolled" title="not_enrolled"></span>
									</div>
								</div>
								<div class="form-row mt-3" id="userDetails">
									<!--this is where user details will be displayed-->
								</div>
								<div class="form-row m-3 mt-md-5 justify-content-center">
									<div class="col-4">
										<button class="btn btn-primary btn-block my-sec-bg my-text-button py-1" type="submit" onclick="captureForIdentify()">Start Capture</button>
									</div>
									<div class="col-4">
										<button class="btn btn-primary btn-block my-sec-bg my-text-button py-1" type="submit" onclick="serverIdentify()">Identify</button>
									</div>
									<div class="col-4">
										<button class="btn btn-secondary btn-outline-warning btn-block my-text-button py-1 border-0" type="button" onclick="clearCapture()">Clear</button>
									</div>
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<div class="form-row">
								<div class="col">
									<button class="btn btn-secondary my-text8 btn-outline-danger border-0" type="button" data-dismiss="modal" onclick="clearCapture()">Close</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
	<script type="text/javascript">
		$(document).ready(function() {
			loadVoterArea();
		});

		function loadVoters() {
			var loadvoters = "loadvoters";
			var voterarea = $('#voterarea').val();

			$.ajax({
				url: "voters_backend.php",
				type: "POST",
				data: {
					loadvoters: loadvoters,
					voterarea: voterarea
				},

				success: function(data) {
					$('#voter_records').html(data);
				}
			});
		}

		function loadVoterArea() {
			var loadvoterarea = "loadvoterarea";

			$.ajax({
				url: "candidates_backend.php",
				type: "POST",
				data: {
					loadvoterarea: loadvoterarea
				},

				success: function(data) {
					$('#voterarea').html(data);
					$('#newvoterarea').html(data);
				}
			});
		}

		function getVoterDetails(id) {
			$("#hidden_voter_id").val(id);

			$.post("voters_backend.php", {
					id: id
				},

				function(data) {
					var user = JSON.parse(data);
					$("#newname").val(user.name);
					$("#newage").val(user.age);
					$("#newaddress").val(user.address);
					$("#newmobile").val(user.mobile);
					$("#newvoterarea").val(user.voter_area);
				}
			);

			$("#updateModal").modal("show");
		}

		function updateVoterDetails() {
			var newnid = $("#newnid").val();
			var newname = $("#newname").val();
			var newage = $("#newage").val();
			var newaddress = $("#newaddress").val();
			var newmobile = $("#newmobile").val();
			var newvoterarea = $("#newvoterarea").val();
			var hidden_voter_id = $("#hidden_voter_id").val();

			$.post("voters_backend.php", {
					hidden_voter_id: hidden_voter_id,
					newnid: newnid,
					newname: newname,
					newage: newage,
					newaddress: newaddress,
					newmobile: newmobile,
					newvoterarea: newvoterarea
				},

				function(data) {
					$("#updateModal").modal("hide");
					loadVoters();
				}
			);
		}

		function deleteVoter(deleteid) {
			var set_mode = '3';
			var conf = confirm("Are you sure you want to delete this voter and associated fingerprint?");

			if (conf == true) {
				$('#verifyIdentity').modal("show");

				$.ajax({
					url: "voters_backend.php",
					type: 'POST',
					data: {
						deleteid: deleteid
					}
				});

				$.ajax({
					url: 'set_mode.php',
					type: 'POST',
					data: {
						set_mode: set_mode
					}
				});

				setInterval(function() {
					$('#status_area').load('get_msg.php', function(response) {
						if (response == "Deleted from device..") {
							$('#status_area').val("Deleted from device..");
							alert("Voter and associated fingerprint deleted successfully..");
							$('#deleteFingerprint').modal("hide");
							loadVoters();
						}
					});
				}, 100);
			}
		}
	</script>
</body>

</html>