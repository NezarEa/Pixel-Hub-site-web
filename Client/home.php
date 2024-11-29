<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="../css/style_home.css">
    <title>PixelHub | Home</title>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <header>
                <div class="logo animate__flash">
                    <a href="">
                        <h1>Pixel<span>Hub</span></h1>
                    </a>
                </div>
                <nav>
                    <ul>
                        <li><a href="login.php" class="btn btn-secondary animate__pulse" id="btn">Login</a></li>
                        <li><a href="register.php" class="btn btn-light animate__pulse" id="btn">Register</a></li>
                    </ul>
                </nav>
            </header>
            <section class="hero animate__animated animate__fadeIn">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 card text-center bg-dark" id="card">
                            <h2 style="padding:20px;" class="text-white">Welcome to our <span
                                    id="logo">Pixel<span>Hub</span></span> community</h2>
                            <p style="padding:20px;" id="text">the website aims to create a welcoming and
                                engaging environment for photography enthusiasts, where they can learn, be inspired, and
                                build connections with like-minded individuals.</p>
                        </div>
                        <div class="col-md-6"
                            style="display:flex;align-content: center;justify-content: center;align-items: center;">
                            <div class="swiper">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide"><img src="../css/image1.jpg" alt="image1"></div>
                                    <div class="swiper-slide"><img src="../css/image2.jpg" alt="image2"></div>
                                    <div class="swiper-slide"><img src="../css/image3.jpg" alt="image3"></div>
                                    <div class="swiper-slide"><img src="../css/image4.jpg" alt="image4"></div>
                                </div>
                                <div class="swiper-pagination"></div>
                                <div class="swiper-button-prev"></div>
                                <div class="swiper-button-next"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <footer class="footer animate__animated animate__slideInUp">
                <div class="container text-center">
                    <p>
                        &copy; copyright @ <?= date('Y'); ?> by <span id="logo2">Pixel<span>Hub</span></span> | all
                        rights reserved!
                    </p>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../Js/script.js">
    </script>
</body>

</html>