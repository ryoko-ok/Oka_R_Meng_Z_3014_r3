<?php

function getUserLevelMap()
{
    return array(
        '0'=>'Web Editor',
        '1'=>'Web Admin',
        '2'=>'Web Super Admin',
    );
}

function getCurrentUserLevel()
{
    $user_level_map = getUserLevelMap();

    if (isset($_SESSION['user_level']) && array_key_exists($_SESSION['user_level'], $user_level_map)) {
        return $user_level_map[$_SESSION['user_level']];
    } else {
        return "Unrecognized";
    }
}

function createUser($user_data)
{

    if(empty($user_data['username']) || isUsernameExists($user_data['username'])){
        return 'Username is invalid!!';
    }

    ## 1. Run the proper SQL query to insert user
    $pdo = Database::getInstance()->getConnection();

    $create_user_query = 'INSERT INTO tbl_user(user_fname, user_name, user_pass, user_email, user_level)';
    $create_user_query .= ' VALUES(:fname, :username, :password, :email, :user_level)';
    
    $create_user_set = $pdo->prepare($create_user_query);
    $create_user_result = $create_user_set->execute(
        array(
            ':fname'=>$user_data['fname'],
            ':username'=>$user_data['username'],
            ':password'=>$user_data['password'],
            ':email'=>$user_data['email'],
            ':user_level'=>$user_data['user_level'],
        )
    );

    ## 2. Redirect to index.php if create user successfully (*maybe with some message???),
    # otherwise, showing the error message

    if ($create_user_result) {
        redirect_to('index.php');
    } else {
        return 'The user did not go through!!';
    }
}

function getSingleUser($id){
    $pdo = Database::getInstance()->getConnection();

    ## TODO: finish the following SQL query so that it can fetch all data about that user with user_id = $id
    $get_user_query = 'SELECT * FROM tbl_user WHERE user_id = :id';
    $get_user_set =$pdo->prepare($get_user_query);
    $results = $get_user_set->execute(
        array(
            ':id'=>$id,
        )
    );

    if($results && $get_user_set->rowCount()){
        return $get_user_set;
    }else{
        return false;
    }
}

function editUser($user_data){
    if(empty($user_data['username']) || isUsernameExists($user_data['username'])){
        return 'Username is invalid!!';
    }
    
    $pdo = Database::getInstance()->getConnection();

    ## TODO: finish the following lines, so that your user profile is updated
    $update_user_query = 'UPDATE tbl_user SET user_fname = :fname, user_name=:username, user_pass=:password, user_email=:email, user_level=:level WHERE user_id=:id';
    $update_user_set = $pdo->prepare($update_user_query);
    $update_user_result = $update_user_set->execute(
        array(
            ':fname'=>$user_data['fname'],
            ':username'=>$user_data['username'],
            ':password'=>$user_data['password'],
            ':email'=>$user_data['email'],
            ':level'=>$user_data['user_level'],
            ':id'=>$user_data['id']
        )
    );
    // $update_user_set->debugDumpParams();
    // exit;

    if($update_user_result){
        $_SESSION['user_level'] = $user_data['user_level'];
        redirect_to('index.php');
    }else{
        return 'Guess you got canned....';
    }
}

function isCurrentUserAdminAbove(){
    return !empty($_SESSION['user_level']);
}

function isUsernameExists($username){
    $pdo = Database::getInstance()->getConnection();
    ## TODO: finish the following lines to check if there is another row in the tbl_user that has the given username
    $user_exists_query = 'SELECT COUNT(*) FROM tbl_user WHERE user_name = :username';
    $user_exists_set = $pdo->prepare($user_exists_query);
    $user_exists_result = $user_exists_set->execute(
        array(
            ':username'=>$username
        )
    );

    return !$user_exists_result || $user_exists_set->fetchColumn()>0;
}