<?php

namespace fingerprint;
require_once("./Database.php");


function setUserFmds(
    $user_id,
    $enrolled_index_finger_fmd_string,
    $enrolled_middle_finger_fmd_string,
    $fullname,
    $age,
    $address,
    $mobile,
    $voterarea
) {
    $myDatabase = new Database();
    $sql_query = "INSERT INTO voters (id, name, age, address, mobile, voter_area, indexfinger, middlefinger) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $param_type = "ssssssss"; // Assuming id is an integer and the rest are strings
    $param_array = [$user_id, $fullname, $age, $address, $mobile, $voterarea, $enrolled_index_finger_fmd_string, $enrolled_middle_finger_fmd_string];
    $affected_rows = $myDatabase->insert($sql_query, $param_type, $param_array);

    if ($affected_rows > 0) {
        return "success";
    } else {
        return "failed in querydb";
    }
}


function getUserFmds($user_id){
    $myDatabase = new Database();
    $sql_query = "select indexfinger, middlefinger from voters where id=?";
    $param_type = "i";
    $param_array = [$user_id];
    $fmds = $myDatabase->select($sql_query, $param_type, $param_array);
    return json_encode($fmds);
}

function getUserDetails($user_id){
    $myDatabase = new Database();
    $sql_query = "select name, from voters where id=?";
    $param_type = "i";
    $param_array = [$user_id];
    $user_info = $myDatabase->select($sql_query, $param_type, $param_array);
    return json_encode($user_info);
}

function getAllFmds(){
    $myDatabase = new Database();
    $sql_query = "select indexfinger, middlefinger from voters where indexfinger != ''";
    $allFmds = $myDatabase->select($sql_query);
    return json_encode($allFmds);
}