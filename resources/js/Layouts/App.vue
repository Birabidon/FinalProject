<script setup>
import {Link} from '@inertiajs/vue3'

// To run your project on another PC using Git, follow these steps:
//     Clone the Repository: Clone the repository to your local machine.
//     git clone git@github.com:Birabidon/FinalProject.git
// cd FinalProject
// Install Dependencies: Install the necessary dependencies using Composer and npm.
//     composer install
// npm install
// Environment Configuration: Copy the .env.example file to .env and configure it with your environment settings.
//     cp .env.example .env
// Generate Application Key: Generate the application key for Laravel.
//                                                                php artisan key:generate
// Run Migrations: Run the database migrations to set up your database schema.
//     php artisan migrate
// Build Frontend Assets: Compile the frontend assets using npm.
// npm run dev
// Serve the Application: Start the Laravel development server.
//     php artisan serve
// By following these steps, you should be able to run the project on another PC. Make sure to configure your .env file with the correct database and other environment settings.
// php artisan storage:link
//
</script>

<template>
    <header>
        <Link method="delete" href="users/3">Test</Link>
        <ul>
            <li><Link href="/" :class="{'active': $page.component === 'home'}">Home</Link></li>
            <li><Link href="about" :class="{'active': $page.component === 'about'}">About</Link></li>
            <li><Link href="">Contact</Link></li>
        </ul>

        <div id="name">
            <p>Hello, {{ $page.props.auth.user?.name }}</p>
        </div>
        <div class="account space-x-6 flex">
            <img
                :src="$page.props.auth.user?.avatar ? ('storage/' + $page.props.auth.user?.avatar) : ('/storage/avatars/default.jpg')"
                alt="avatar"
               class="avatar"> <!--  point to the public folder, so avatar folder must have symbolic link to public folder: php artisan storage:link-->
            <Link
                href="/logout"
                method="POST"
                as="button"
                type="button"
            >Logout
            </Link> <!-- as button is neaded bc link can be opened in a new tab -->
        </div>
    </header>

    <main>
        <slot></slot>
    </main>

    <footer>
        <div class="footer-content">
            <div class="contact-info">
                <p>1234 Street Name, City, State, 12345</p>
                <p>Phone: (123) 456-7890</p>
                <p>Email: info@example.com</p>
            </div>
            <div class="social-media">
                <a href="https://facebook.com" target="_blank">Facebook</a>
                <a href="https://twitter.com" target="_blank">Twitter</a>
                <a href="https://instagram.com" target="_blank">Instagram</a>
            </div>
            <div class="footer-nav">
                <a href="/about">About</a>
                <a href="/contact">Contact</a>
                <a href="/privacy-policy">Privacy Policy</a>
                <a href="/terms-of-service">Terms of Service</a>
            </div>
            <div class="newsletter-signup">
                <form>
                    <label for="email">Subscribe to our newsletter:</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email">
                    <button type="submit">Subscribe</button>
                </form>
            </div>
            <div class="copyright">
                <p>&copy; 2023 Your Company. All rights reserved.</p>
            </div>
        </div>
    </footer>
</template>

<style scoped>

</style>
