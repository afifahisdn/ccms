<footer class="site-footer">
    <div class="container">
        <div class="row">
            <!-- Footer content wrapper -->
            <div class="col-md-12">
                <div class="row">

                    <!-- Quick Links -->
                    <div class="col-md-8 text-center">
                        <h2 class="footer-heading mb-3">Quick Links</h2>
                        <ul class="list-unstyled">
                            <li><a href="index.php">Home</a></li>
                            <li><a href="index.php#section-about">About Us</a></li>
                            <li><a href="index.php#section-contact">Contact Us</a></li>
                        </ul>
                    </div>

                    <!-- Follow Us -->
                    <!-- Corrected col-md-13 to col-md-4 -->
                    <div class="col-md-4 text-center">
                        <h2 class="footer-heading mb-3">Follow Us</h2>
                        <?php if (isset($res) && !empty($res["link_facebook"]) && $res["link_facebook"] != '#') : ?>
                            <a href="<?php echo htmlspecialchars($res["link_facebook"]); ?>" target="_blank" rel="noopener noreferrer" class="pl-0 pr-3"><span class="icon-facebook"></span></a>
                        <?php endif; ?>
                        <?php if (isset($res) && !empty($res["link_twitter"]) && $res["link_twitter"] != '#') : ?>
                            <a href="<?php echo htmlspecialchars($res["link_twitter"]); ?>" target="_blank" rel="noopener noreferrer" class="pl-3 pr-3"><span class="icon-twitter"></span></a>
                        <?php endif; ?>
                        <?php if (isset($res) && !empty($res["link_instagram"]) && $res["link_instagram"] != '#') : ?>
                            <a href="<?php echo htmlspecialchars($res["link_instagram"]); ?>" target="_blank" rel="noopener noreferrer" class="pl-3 pr-3"><span class="icon-instagram"></span></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Copyright Row -->
        <div class="row pt-5 mt-5 text-center">
            <div class="col-md-12">
                <div class="border-top pt-5">
                    <p>
                        <!-- Updated Copyright Text -->
                        Copyright &copy;<script>
                            document.write(new Date().getFullYear());
                        </script> All rights reserved | College Complaint Management System (CCMS)
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>