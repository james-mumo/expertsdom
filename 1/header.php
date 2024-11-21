<!-- header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Website</title>
</head>
<body>
    <header>
        <nav>
            <div>
                <a href="#" id="brandText">Expertsdom</a>
                <div>
                    <div>
                        <a href="#" id="writingSamplesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Writing Samples
                        </a>
                        <div aria-labelledby="writingSamplesDropdown">
                            <a href="#">Sample 1</a>
                            <a href="#">Sample 2</a>
                            <a href="#">Sample 3</a>
                            <a href="#">Sample 4</a>
                            <a href="#">Sample 5</a>
                            <a href="#">Sample 6</a>
                        </div>
                    </div>
                    <a href="samples.html">Samples</a>
                    <a href="#">Plagiarism Checker</a>
                    <a href="blog.html">Blog</a>
                    <!-- <a href="#">For Writers</a>
                    <a href="#">Log In</a> -->
                    <a href="orderdetails.html">Order Now</a>
                    <a href="<?php echo site_url('home/login'); ?>"><?php echo get_phrase('log_in'); ?></a>
                    <a href="<?php echo site_url('home/sign_up'); ?>"><?php echo get_phrase('sign_up'); ?></a>
                </div>
            </div>
        </nav>
    </header>
</body>
</html>
