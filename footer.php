</main>
</div>
<?php if (!is_front_page()) : ?>
    <footer id="footer" class="" role="contentinfo">
        <div class="footer-main">
            <div class="footer-socials">
                <a href="https://www.instagram.com/bnomio" target="_blank" class="social-link">
                    <span class="icon icon-instagram"></span>
                    <p class="social-text">@BNOMIO</p>
                </a>
            </div>
            <div class="footer-info">
                <p>BNOMIO | ©COPYRIGHT 2025 ALL RIGHTS RESERVED</p>
            </div>
        </div>
        <div class="footer-navigation theme--artist">
            <ul class="navigation-left">
                <li><a class="link <?= is_studio() ? 'link--active' : '' ?>" href="#">Studio zone</a></li>
                <li><a class="link <?= is_artist() ? 'link--active' : '' ?>" href="#">Artist zone</a></li>
            </ul>
            <ul class="navigation-right">
                <li><a class="link" href="#">ESP</a></li>
                <li><a class="link link--active" href="#">ENG</a></li>
            </ul>
        </div>
    </footer>
<?php endif; ?>
</div>
<?php wp_footer(); ?>
</body>

</html>