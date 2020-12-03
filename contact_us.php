<?php

require_once 'ConnectDb.php';
require 'ContactValidator.php';
require 'User.php';

$user = new User();
$validator = new ContactValidator($_POST);

$errors = $validator->validate();

if($errors !== true) {
    die(var_dump($errors));
    // @TODO redirect back with errors, refilling form fields
}

$user = new User;
$user->createIfNotExists($_POST);



// @TODO Redirect to success page