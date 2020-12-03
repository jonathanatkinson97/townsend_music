<?php

if(isset($_POST['submitted'])) {

    require_once 'ConnectDb.php';
    require 'ContactValidator.php';
    require 'User.php';
    require 'Message.php';

    $user = new User();
    $validator = new ContactValidator($_POST);

    $errors = $validator->validate();

    if(empty($errors)) {

        $user = new User;
        $user->createIfNotExists($_POST);

        $message = new Message;
        $message->create($_POST, $user->getId());
        $message->mail();
    }
}

?>


<html>
    <head>
    </head>

    <body>

        <h1>Contact Us</h1>

        <form action="/townsend_music/index.php" method="POST">

            <input type="hidden" name="submitted" value="true"/>

            <div class="input-field">
                <label for="name">Name</label>
                <input id="name" type="text" name="name" value="<?= $_POST['name'] ?? '' ?>" required />
                <p class="error"><?= $errors['name'] ?? '' ?></p>
            </div>

            <div class="input-field">
                <label for="phone">Phone</label>
                <input id="phone" type="text" name="phone" value="<?= $_POST['phone'] ?? '' ?>" pattern="^[\s0-9]{10,}$" required />
                <p class="error"><?= $errors['phone'] ?? '' ?></p>
            </div>

            <div class="input-field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="<?= $_POST['email'] ?? '' ?>" required />
                <p class="error"><?= $errors['email'] ?? '' ?></p>
            </div>

            <div class="input-field">
                <label for="message">Message</label>
                <textarea id="message" name="message" value="<?= $_POST['message'] ?? '' ?>" required></textarea>
                <p class="error"><?= $errors['message'] ?? '' ?></p>
            </div>

            <div class="input-field">
                <label for="subscribed">Subscribe?</label>
                <input id="subscribed" type="checkbox" name="subscribed" checked="<?= $_POST['subscribed'] === 'true' ?>" value="1" />
            </div>

            <input type="submit" />

        </form>

    </body>
</html>