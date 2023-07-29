<?php

	namespace fingerprint;
	include('dbconn2.php');
	
	
	if(isset($_POST['loadvoterarea'])) {
		$data = '<option value="" selected="selected">Select Voter Area..</option>';
		$query = "SELECT voter_area_name FROM voter_area_list";
		$result = mysqli_query($conn, $query);

		if(mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_assoc($result)) {
				$data .= '<option value="'.$row['voter_area_name'].'">'.$row['voter_area_name'].'</option>';
			}
		}

		echo $data;
	}

	if(isset($_POST['loadvoters'])) {
		$voterarea = $_POST['voterarea'];
		$data = '<table style="margin-top: 10px" class="table table-bordered table-striped text-center">
					<tr class="bg-warning text-white">
						<th>Sl.</th>
						<th>Id</th>
						<th>Name</th>
						<th>Age</th>
						<th>Address</th>
						<th>Mobile</th>
						<th>Voter Area</th>
						<th>Operation(s)</th>
					</tr>';
		$query = "SELECT * FROM `voters` WHERE voter_area = '$voterarea'"; 
		$result = mysqli_query($conn, $query);

		if(mysqli_num_rows($result) > 0) {
			$serial = 1;
			
			while($row = mysqli_fetch_array($result)) {
				$data .= '<tr class="bg-light text-dark">
							<td>'.$serial.'</td>
							<td>'.$row['id'].'</td>
							<td>'.$row['name'].'</td>
							<td>'.$row['age'].'</td>
							<td>'.$row['address'].'</td>
							<td>'.$row['mobile'].'</td>
							<td>'.$row['voter_area'].'</td>
							<td>
								<button style="font-weight: bold" onclick="getVoterDetails('.$row['id'].')" class="btn btn-warning btn-sm">Edit</button>
								<button style="font-weight: bold" onclick="deleteVoter('.$row['id'].')" class="btn btn-danger btn-sm">Delete</button>
							</td>
    					</tr>';
    			$serial++;
			}
		}

		$data .= '</table>';
    	echo $data;
	}

	if(isset($_POST['deleteid'])) {
		$voterid = $_POST['deleteid'];
		$query = "DELETE FROM voters WHERE id = '$id'";
		mysqli_query($conn, $query);
	}
	
	if(isset($_POST['id']) && isset($_POST['id']) != "") {
    	$id = $_POST['id'];
    	$query = "SELECT * FROM voters WHERE id = '$id'";
    	
    	if(!$result = mysqli_query($conn, $query)) {
        	exit(mysqli_error());
    	}
    	
    	$response = array();
    	
    	if(mysqli_num_rows($result) > 0) {
    		while($row = mysqli_fetch_array($result)) {
            	$response = $row;
        	}
    	}
    	else {
        	$response['status'] = 200;
        	$response['message'] = "Data not found!";
    	}

    	echo json_encode($response);
	}
	else {
    	$response['status'] = 200;
    	$response['message'] = "Invalid request!";
	}

	if(isset($_POST['hidden_voter_id'])) {
		$hidden_voter_id = $_POST['hidden_voter_id'];
		$newnid = $_POST['newnid'];
		$newname = $_POST['newname'];
		$newage = $_POST['newage'];
		$newaddress = $_POST['newaddress'];
		$newmobile = $_POST['newmobile'];
		$newvoterarea = $_POST['newvoterarea'];

    	$query = "UPDATE voters SET id = '$newnid',  name = '$newname', age = '$newage', address = '$newaddress', mobile = '$newmobile', voter_area = '$newvoterarea' WHERE id = '$hidden_voter_id'";

    	if(!$result = mysqli_query($conn, $query)) {
    		exit(mysqli_error());
    	}
    }
?>