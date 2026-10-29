<?php
require_once __DIR__ . '/../config/app.php';
require_once "../includes/system_settings.php";
?>

        </main>

        <footer class="footer">

            <div class="footer-content">

                <div class="footer-grid">

                    <div class="footer-brand">

                        <img
                            src="<?= BASE_URL; ?>/assets/images/logo.png"
                            alt="Logo">

                        <h4><?= APP_SHORT_NAME; ?></h4>

                        <p>
                            <?= APP_NAME; ?><br>
                            Version <?= APP_VERSION; ?>
                        </p>

                    </div>

                    <div>

                        <h5 class="footer-title">
                            Quick Links
                        </h5>

                        <ul class="footer-links">

                            <li><a href="<?= BASE_URL; ?>/dashboard/index.php">Dashboard</a></li>
                            <li><a href="<?= BASE_URL; ?>/cows/manage.php">Cow Management</a></li>
                            <li><a href="<?= BASE_URL; ?>/milk/records.php">Milk Production</a></li>
                            <li><a href="<?= BASE_URL; ?>/reports/dashboard.php">Reports</a></li>

                        </ul>

                    </div>

                    <div>

                        <h5 class="footer-title">
                            System Status
                        </h5>

                        <div class="footer-status">

                            <span class="status-dot"></span>

                            <span>System Online</span>

                        </div>

                    </div>

                </div>

            </div>

            <div class="footer-divider"></div>

            <div class="footer-bottom">

                <div class="copyright">

                    © <?= date('Y'); ?>
                    <?= APP_NAME; ?>

                </div>

                <div class="version-badge">

                    Version <?= APP_VERSION; ?>

                </div>

            </div>

        </footer>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="<?= BASE_URL; ?>/assets/js/app.js"></script>

</body>
</html>