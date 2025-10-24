<footer class="site-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-11">
                    <div class="row">

                        <div class="col-md-8 text-center">
                            <h2 class="footer-heading mb-3">Quick Links</h2>
                            <ul class="list-unstyled">
                                <li><a href="index.php">Home</a></li>
                                <li><a href="#section-about">About Us</a></li>
                                <li><a href="#section-contact">Contact Us</a></li>
                            </ul>
                        </div>
                        <div class="col-md-13 text-center">
                            <h2 class="footer-heading mb-3">Follow Us</h2>
                            <a href="<?php echo $res[
                                "link_facebook"
                            ]; ?>" class="pl-0 pr-3"><span class="icon-facebook"></span></a>
                            <a href="<?php echo $res[
                                "link_twitter"
                            ]; ?>" class="pl-3 pr-3"><span class="icon-twitter"></span></a>
                            <a href="<?php echo $res[
                                "link_instagram"
                            ]; ?>" class="pl-3 pr-3"><span class="icon-instagram"></span></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row pt-5 mt-5 text-center">
                <div class="col-md-12">
                    <div class="border-top pt-5">
                        <p>
                            Copyright &copy;<script>
                                document.write(new Date().getFullYear());
                            </script> All rights reserved | Velocity Express

                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>