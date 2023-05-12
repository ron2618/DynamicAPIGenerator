<?php
include('config.php');

//make get with dinamis table from url get and column in split get('kolom')
if($_GET['type'] == 'get'){
    //make if innerjoin true in get url get join to table 2 and key1 and key 2
    if(isset($_GET['innerjoin'])){
        //make if where and split if isset comma with multiple where
        if(isset($_GET['where'])){
            $where = explode(',',$_GET['where']);
            $query = "SELECT * FROM ".$_GET['table']." INNER JOIN ".$_GET['table2']." ON ".$_GET['key1']." = ".$_GET['key2']." WHERE ".$where[0]." = '".$where[1]."'";
        }else{
            $query = "SELECT * FROM ".$_GET['table']." INNER JOIN ".$_GET['table2']." ON ".$_GET['key1']." = ".$_GET['key2'];
        }
    }else{
        //make if where and split if isset comma with multiple where
        if(isset($_GET['where'])){
            $where = explode(',',$_GET['where']);
            $query = "SELECT * FROM ".$_GET['table']." WHERE ".$where[0]." = '".$where[1]."'";
        }else{
            $query = "SELECT * FROM ".$_GET['table'];
        }
    }
    $result = mysqli_query($con, $query);
    $array = array();
    while($row = mysqli_fetch_assoc($result)){
        $array[] = $row;
    }
    //cek if array < 1
    if(count($array) < 1){
        $array = array('status' => 'error', 'message' => 'data not found');
    }else{
       //set array status and message
        $array = array('status' => 'success', 'message' => 'data found', 'data' => $array);
    }
    echo json_encode($array);
}

//make get with dinamis table from url get and column in split get('kolom')
if($_GET['type'] == 'login'){
   //make login md5 post
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $query = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($con, $query);
    $array = array();
    while($row = mysqli_fetch_assoc($result)){
        $array[] = $row;
    }
    //cek if array < 1
    if(count($array) < 1){
        $array = array('status' => 'error', 'message' => 'data not found');
    }else{
       //set array status and message
        $array = array('status' => 'success', 'message' => 'data found', 'data' => $array);
    }
    echo json_encode($array);
}

if($_GET['type'] == 'add'){
    //make add with dinamis table from url get and column in split post('kolom') and key post to key sql
    //make if isset $files add first to db
    if(isset($_FILES['image'])){
        $image = $_FILES['image']['name'];
        //explode key
        $key = explode(',',$_POST['key']);
        //explode value
        $value = explode(',',$_POST['value']);
        //move if $image
        move_uploaded_file($_FILES['image']['tmp_name'], 'images/'.$image);
        //add files to value 
        array_push($value, $image);
          //foreach key to make query column
        $column ="";
        $values= "";
        foreach($key as $k){
           //cek if last without comma
            if($k == end($key)){
                $column .= $k;
            }else{
                $column .= $k.", ";
            }
        }
        //foreach value to make query value
        foreach($value as $v){
           //cek if last wihout comma
            if($v == end($value)){
                $values .= "'".$v."'";
            }else{
                $values .= "'".$v."', ";
            }
        }
        //make query
        $query = "INSERT INTO ".$_GET['table']." (".($column).") VALUES (".($values).")";
    }else{
        //explode key
        $key = explode(',',$_POST['key']);
        //explode value
        $value = explode(',',$_POST['value']);
        //foreach key to make query column
        $column ="";
        $values= "";
        foreach($key as $k){
           //cek if last without comma
            if($k == end($key)){
                $column .= $k;
            }else{
                $column .= $k.", ";
            }
        }
        //foreach value to make query value
        foreach($value as $v){
           //cek if last wihout comma
            if($v == end($value)){
                $values .= "'".$v."'";
            }else{
                $values .= "'".$v."', ";
            }
        }
        //make query
        $query = "INSERT INTO ".$_GET['table']." (".($column).") VALUES (".($values).")";
    }
    $result = mysqli_query($con, $query);
    //cek if result true
    if($result){
        $array = array('status' => 'success', 'message' => 'data added');
    }else{
        $array = array('status' => 'error', 'message' => $query);
    }
    echo json_encode($array);

        
}else if    ($_GET['type'] == 'edit'){
    //make edit with dinamis table from url get and column in split post('kolom') and key post to key sql
    //make if isset $files add first to db
    if(isset($_FILES['image'])){
        $image = $_FILES['image']['name'];
        //explode key
        $key = explode(',',$_POST['key']);
        //explode value
        $value = explode(',',$_POST['value']);
        //move if $image
        move_uploaded_file($_FILES['image']['tmp_name'], 'images/'.$image);
        //add files to value 
        array_push($value, $image);
        //foreach key to make query column
        $column ="";
        $values= "";
        foreach($key as $k){
           //cek if last without comma
            if($k == end($key)){
                $column .= $k." = '".$value[array_search($k, $key)]."'";
            }else{
                $column .= $k." = '".$value[array_search($k, $key)]."', ";
            }
        }
        //make query
        $query = "UPDATE ".$_GET['table']." SET ".($column)." WHERE id = '".$_GET['id']."'";
    }else{
        //explode key
        $key = explode(',',$_POST['key']);
        //explode value
        $value = explode(',',$_POST['value']);
        //foreach key to make query column
        $column ="";
        $values= "";
        foreach($key as $k){
           //cek if last without comma
            if($k == end($key)){
                $column .= $k." = '".$value[array_search($k, $key)]."'";
            }else{
                $column .= $k." = '".$value[array_search($k, $key)]."', ";
            }
        }
        //make query
        $query = "UPDATE ".$_GET['table']." SET ".($column)." WHERE id = '".$_GET['id']."'";
    }
    $result = mysqli_query($con, $query);
    //cek if result true
    if($result){
        $array = array('status' => 'success', 'message' => 'data updated');
    }else{
        $array = array('status' => 'error', 'message' => $query);
    }
    echo    json_encode($array);
}else if($_GET['type'] == 'delete'){
    //make delete with dinamis table from url get and column in split post('kolom') and key post to key sql
    $query = "DELETE FROM ".$_GET['table']." WHERE id = '".$_GET['id']."'";
    $result = mysqli_query($con, $query);
    //cek if result true
    if($result){
        $array = array('status' => 'success', 'message' => 'data deleted');
    }else{
        $array = array('status' => 'error', 'message' => $query);
    }
    echo    json_encode($array);    
}
?>