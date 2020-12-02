<html>
    <head>
    </head>

    <body>

        <h1>Contact Us</h1>

        <form action="/contact_us.php">

            <div class="input-field">
                <label for="name">Name</label>
                <input id="name" type="text" name="name" required />
            </div>

            <div class="input-field">
                <label for="phone">Phone</label>
                <input id="phone" type="text" name="phone" required />
            </div>

            <div class="input-field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" required />
            </div>

            <div class="input-field">
                <label for="message">Message</label>
                <textarea id="message" name="message" required></textarea>
            </div>

            <div class="input-field">
                <label for="subscribed">Subscribe?</label>
                <input id="subscribed" type="checkbox" name="name" value="1" />
            </div>

            <input type="submit" />

        </form>

    </body>
</html>