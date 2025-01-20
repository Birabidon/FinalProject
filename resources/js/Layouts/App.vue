<script setup>

</script>

<template>
    <header>
        <ul>
            <li><Link href="/" :class="{'active': $page.component === 'home'}">Home</Link></li>
            <li><Link href="about" :class="{'active': $page.component === 'about'}">About</Link></li>
            <li><Link href="">Contact</Link></li>
        </ul>

        <div id="name">
            <p>Hello, {{ $page.props.user }}</p>
        </div>
        <div class="account">
            <Link href="login">Logout</Link>
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

<script>
import { Link } from '@inertiajs/vue3'
export default {
    components: {
        Link
    }
}
</script>
<style scoped>

</style>
