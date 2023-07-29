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
	<title>New Voter Registration - FVS</title>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="icon" href="images/logo.png">
	<link rel="stylesheet" href="./css/bootstrap.css">
	<link rel="stylesheet" href="./css/custom.css">
	<link href="https://fonts.googleapis.com/css?family=Ubuntu&display=swap" rel="stylesheet">

	<script src="./js/jquery-3.5.0.min.js"></script>
	<script src="./js/bootstrap.bundle.js"></script>
	<script src="./js/es6-shim.js"></script>
	<script src="./js/websdk.client.bundle.min.js"></script>
	<script src="./js/fingerprint.sdk.min.js"></script>
	<script src="./js/custom.js"></script>

	<style type="text/css">
		body {
			font-family: 'Ubuntu', sans-serif;
		}
	</style>
</head>

<body>
	<div class="container" style="margin-top: 50px">
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<div class="card">
					<div class="card-header bg-warning text-white font-weight-bold" style="text-align: center; font-size: 20px">Registration Form</div>
					<div class="card-body bg-light">
						<form method="POST" id="registration_form">
							<div class="form-group">
								<label style="font-weight: bold">Full Name</label>
								<input type="text" name="fullname" id="fullname" placeholder="Enter Full Name.." class="form-control" require />
								<span id="error_fullname" class="text-danger"></span>
							</div>
							<div class="form-group">
								<label style="font-weight: bold">Age</label>
								<input type="text" name="age" id="age" placeholder="Enter Age.." class="form-control" require />
								<span id="error_age" class="text-danger"></span>
							</div>
							<div class="form-group">
								<label style="font-weight: bold">Address</label>
								<input type="text" name="address" id="address" placeholder="Enter Address.." class="form-control" require />
								<span id="error_address" class="text-danger"></span>
							</div>
							<div class="form-group">
								<label style="font-weight: bold">Mobile No.</label>
								<input type="text" name="mobile" id="mobile" placeholder="Enter Mobile Number.." class="form-control" require />
								<span id="error_mobile" class="text-danger"></span>
							</div>
							<div class="form-group">
								<label style="font-weight: bold">Voter Area</label>
								<select type="text" name="voterarea" id="voterarea" class="form-control" require></select>
								<span id="error_voterarea" class="text-danger"></span>
							</div>
							<div id="controls" class="form-group col text-center" style="margin-top: 20px; margin-bottom: 0px">
								<!-- <button type="button" data-toggle="modal" data-target="#createEnrollment" class="btn btn-sm btn-warning" style="font-weight: bold; font-size: 15px" onclick="beginEnrollment()" id="createEnrollmentButton">Add Fingerprint</button> -->
								<button id="createEnrollmentButton" type="button" class="btn btn-primary btn-block" onclick="beginEnrollment()">Create Enrollment</button>
							</div>

						</form>
					</div>
				</div>
			</div>
			<div class="col-md-2"></div>
		</div>
	</div>


</body>


<section>
	<!--Create Enrolment Section-->
	<div class="modal fade" id="createEnrollment" data-backdrop="static" tabindex="-1" aria-labelledby="createEnrollmentTitle" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title my-text my-pri-color" id="createEnrollmentTitle">Create Enrollment</h3>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clearCapture()">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="#" onsubmit="return false">
						<div id="enrollmentStatusField" class="text-center">
							<!--Enrollment Status will be displayed Here-->
						</div>
						<div class="form-row mt-3">
							<div class="col mb-3 mb-md-0 text-center">
								<label for="enrollReaderSelect" class="my-text7 my-pri-color">Choose Fingerprint Reader</label>
								<select name="readerSelect" id="enrollReaderSelect" class="form-control" onclick="beginEnrollment()">
									<option selected>Select Fingerprint Reader</option>
								</select>
							</div>
						</div>
						<div class="form-row mt-2">
							<div class="col mb-3 mb-md-0 text-center">
								<label for="userID" class="my-text7 my-pri-color">Specify UserID</label>
								<input id="userID" type="text" class="form-control" required>
							</div>
						</div>
						<div class="form-row mt-1">
							<div class="col text-center">
								<p class="my-text7 my-pri-color mt-3">Capture Index Finger</p>
							</div>
						</div>
						<div id="indexFingers" class="form-row justify-content-center">
							<div id="indexfinger1" class="col mb-3 mb-md-0 text-center">
								<span class="icon icon-indexfinger-not-enrolled" title="not_enrolled"></span>
							</div>
							<div id="indexfinger2" class="col mb-3 mb-md-0 text-center">
								<span class="icon icon-indexfinger-not-enrolled" title="not_enrolled"></span>
							</div>
							<div id="indexfinger3" class="col mb-3 mb-md-0 text-center">
								<span class="icon icon-indexfinger-not-enrolled" title="not_enrolled"></span>
							</div>
							<div id="indexfinger4" class="col mb-3 mb-md-0 text-center">
								<span class="icon icon-indexfinger-not-enrolled" title="not_enrolled"></span>
							</div>
						</div>
						<div class="form-row mt-1">
							<div class="col text-center">
								<p class="my-text7 my-pri-color mt-5">Capture Middle Finger</p>
							</div>
						</div>
						<div id="middleFingers" class="form-row justify-content-center">
							<div id="middleFinger1" class="col mb-3 mb-md-0 text-center">
								<span class="icon icon-middlefinger-not-enrolled" title="not_enrolled"></span>
							</div>
							<div id="middleFinger2" class="col mb-3 mb-md-0 text-center">
								<span class="icon icon-middlefinger-not-enrolled" title="not_enrolled"></span>
							</div>
							<div id="middleFinger3" class="col mb-3 mb-md-0 text-center">
								<span class="icon icon-middlefinger-not-enrolled" title="not_enrolled"></span>
							</div>
							<div id="middleFinger4" class="col mb-3 mb-md-0 text-center" value="true">
								<span class="icon icon-middlefinger-not-enrolled" title="not_enrolled"></span>
							</div>
						</div>
						<div class="form-row m-3 mt-md-5 justify-content-center">
							<div class="col-4">
								<button class="btn btn-primary btn-block my-sec-bg my-text-button py-1" type="submit" onclick="beginCapture()">Start Capture</button>
							</div>
							<div class="col-4">
								<button class="btn btn-primary btn-block my-sec-bg my-text-button py-1" type="submit" onclick="serverEnroll()">Enroll</button>
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

</html>
<script>
	$(document).ready(function() {
		$('#reg').attr('disabled', 'disabled');
		loadVoterArea();
	});

	document.getElementById('createEnrollmentButton').addEventListener('click', function() {
		let name = document.getElementById("fullname").value
		let age = document.getElementById("age").value
		let address = document.getElementById("address").value;
		let voterarea = document.getElementById("voterarea").value;
		let mobile = document.getElementById("mobile").value;
		// Check if the input is empty or null
		if (!name || !age || !address || !voterarea || !mobile) {
			alert('Please enter all value before you enrolling the fingerprint.');
			return;
		}
		// If the input is valid, open the modal
		$('#createEnrollment').modal('show');
	});

	function loadVoterArea() {
		var loadvoterarea = "loadvoterarea";

		$.ajax({
			url: "registration_backend.php",
			type: "POST",
			data: {
				loadvoterarea: loadvoterarea
			},

			success: function(data) {
				$('#voterarea').html(data);
			}
		});
	}
</script>