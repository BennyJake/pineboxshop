<?php
require_once("vendor/autoload.php");

use Rakit\Validation\Validator;
use Sabre\HTTP;

$request = HTTP\Sapi::getRequest();

$post = $request->getPostData();
$validationArray = [];
if(isset($post) && !empty($post)){
    $validator = new Validator;

    // make it
    $validation = $validator->make($post, [
        'name'                  => 'required',
        'contact'               => 'required',
        'email'                 => 'required_if:contact,emailOption',
        'phone'                 => 'required_if:contact,phoneOption',
        'project'               => 'required',
        'custom'                => 'required_if:project,customOption',
    ]);

    $validation->validate();

    $validationArray = $validation->errors->toArray();
}
?>
<!DOCTYPE html>
<html lang="en">
<style type="text/css">
    header {
        background-image: url(<?= random_pic("img/header", "main")?>);
    }
    #gallery img{
        vertical-align: middle;
        border-style: none;
        width: 100%;
    }
</style>
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>One Page Wonder - Start Bootstrap Template</title>
    <script src="js/lightbox-plus-jquery.js"></script>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom fonts for this template -->
  <link href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/one-page-wonder.css" rel="stylesheet">
  <link href="css/lightbox.css" rel="stylesheet">

    <!-- Import PhotoSwipe Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.0/photoswipe.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.0/default-skin/default-skin.css">

    <!-- A touch of fanciness 💄 -->
    <link href='https://fonts.googleapis.com/css?family=Bitter:400,700,400italic' rel='stylesheet' type='text/css'>


</head>

<body>

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
    <div class="container">
      <a class="navbar-brand" href="#">The Pinebox Shop</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" data-link="#home" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-link="#about_me" href="#">About Me</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-link="#gallery" href="#">Gallery</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-link="#contact_me" href="#">Contact Me</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!--<header class="masthead text-center text-white">
    <div class="masthead-content">
      <div class="container">
        <h1 class="masthead-heading mb-0">Custom, Affordable Woodwork</h1>
        <h2 class="masthead-subheading mb-0">Will Rock Your Socks Off</h2>
        <a href="#" class="btn btn-primary btn-xl rounded-pill mt-5">Learn More</a>
      </div>
    </div>
  </header>-->

  <header id="home">
      <video playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
          <source src="img/header_movie/header_bg_4.mp4" type="video/mp4">
      </video>
      <!--<div class="container h-100">
          <div class="d-flex h-100 text-center align-items-center">
              <div class="w-100 text-white">
                  <img src="img/PB_Logo_Final.svg" id="home-logo" class="centered">
                  <p class="lead mb-0">With HTML5 Video and Bootstrap 4</p>
              </div>
          </div>
      </div>-->
      <div class="d-flex text-center align-items-center">
              <img src="img/PB_Logo_Final.svg" id="home-logo" class="centered">
      </div>
  </header>

  <section id="about_me" class="full-window-height">
    <div class="container centered">
      <div class="row align-items-center">
        <div class="col-lg-6 order-lg-2">
          <div>
            <img class="img-fluid rounded-circle" src="<?= random_pic("img/gallery", "main")?>" alt="">
          </div>
        </div>
        <div class="col-lg-6 order-lg-1">
          <div>
            <h2 class="display-4">Custom, Affordable Woodwork</h2>
            <p>About me goes here.</p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section style="clear:both;">
      <div class="container">
          <div class="col-lg-12 order-lg-1">
              <!-- Galley wrapper that contains all items -->
              <div id="gallery" class="gallery" itemscope itemtype="http://schema.org/ImageGallery">
                  <div>
                      <h2 class="display-4 text-center">Custom, Affordable Woodwork</h2>
                      <p>About me goes here.</p>
                  </div>
                  <div class="row">
                  <?php for($i = 0; $i < 40; $i++){
                  $randomPic = random_pic("img/gallery", "gallery") ?>
                      <div class="col-lg-3" style="margin:0;padding:0">
                  <!-- Use figure for a more semantic html -->
                  <figure itemprop="associatedMedia" itemscope itemtype="http://schema.org/ImageObject" style="margin:0;padding:0;">
                      <!-- Link to the big image, not mandatory, but usefull when there is no JS -->
                      <a href="<?= $randomPic ?>" data-caption="Sea side, south shore<br><em class='text-muted'>© Dominik Schröder</em>" data-width="1200" data-height="900" itemprop="contentUrl">
                          <!-- Thumbnail -->
                          <img src="<?= $randomPic ?>" itemprop="thumbnail" alt="Image description">
                      </a>
                  </figure>
                      </div>
                  <?php } ?>
                  </div>
              </div>

          </div>

      </div>
  </section>
  <section id="contact_me" class="full-window-height">
    <div class="container centered">
      <div class="row align-items-center">
        <div class="col-lg-6">
          <div class="">
              <img class="img-fluid rounded-circle" src="<?= random_pic("img/gallery", "main")?>" alt="">
          </div>
        </div>
        <div class="col-lg-6">
          <div class="">
            <h2 class="display-4">Contact Us</h2>
            <form method="post" action="#contact_me">
                <div class="form-group">
                    <label for="name">What's your name?</label>
                    <input type="text" class="form-control" name="name">
                </div>
                <div class="form-group">
                    <label for="contact">What's the best way to get back to you?</label>
                    <br/>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="contact" id="inlineRadio1" value="emailOption">
                        <label class="form-check-label" for="inlineRadio1">Email</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="contact" id="inlineRadio2" value="phoneOption">
                        <label class="form-check-label" for="inlineRadio2">Phone</label>
                    </div>
                </div>
                <div class="form-group d-none">
                    <label for="name">Please provide your email address so I can contact you</label>
                    <input type="text"  class="form-control" name="email">
                </div>
                <div class="form-group d-none">
                    <label for="name">Please provide your phone number so I can contact you</label>
                    <input type="text"  class="form-control" name="phone">
                </div>
                <div class="form-group">
                    <label for="project">What are you interested in?</label>
                    <select name="project" class="form-control" id="exampleFormControlSelect1">
                        <option value="">Select One...</option>
                        <option value="beerOption">Beer/Bottle Caddy (6-pack)</option>
                        <option value="wineOption">Wine Bottle & Wine Glass Display</option>
                        <option value="customOption">Something Completely Custom!</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="custom">Describe your custom project</label>
                    <textarea name="custom" class="form-control" rows="5"></textarea>
                    <?php
                    var_dump($validationArray);
                    ?>
                    <div class="invalid-feedback">
                    <?php
                    if(!empty($validationArray) && isset($validationArray['custom'])){ ?>
                        <ul>
                            <?php foreach($validationArray['custom'] as $reason => $message) { ?>
                                <li><?= $message ?></li>
                            <?php
                            }
                            ?>
                        </ul>
                    <?php } ?>
                    </div>
                </div>
                <div class="form-group">
                    <input name="submit" class="form-control btn-primary" type="submit" value="Send Message">
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Root element of PhotoSwipe. Must have class pswp. -->
  <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
      <!-- Background of PhotoSwipe.
           It's a separate element as animating opacity is faster than rgba(). -->
      <div class="pswp__bg"></div>
      <!-- Slides wrapper with overflow:hidden. -->
      <div class="pswp__scroll-wrap">
          <!-- Container that holds slides.
              PhotoSwipe keeps only 3 of them in the DOM to save memory.
              Don't modify these 3 pswp__item elements, data is added later on. -->
          <div class="pswp__container">
              <div class="pswp__item"></div>
              <div class="pswp__item"></div>
              <div class="pswp__item"></div>
          </div>
          <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
          <div class="pswp__ui pswp__ui--hidden">
              <div class="pswp__top-bar">
                  <!--  Controls are self-explanatory. Order can be changed. -->
                  <div class="pswp__counter"></div>
                  <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                  <button class="pswp__button pswp__button--share" title="Share"></button>
                  <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                  <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                  <!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
                  <!-- element will get class pswp__preloader--active when preloader is running -->
                  <div class="pswp__preloader">
                      <div class="pswp__preloader__icn">
                          <div class="pswp__preloader__cut">
                              <div class="pswp__preloader__donut"></div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                  <div class="pswp__share-tooltip"></div>
              </div>
              <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
              </button>
              <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
              </button>
              <div class="pswp__caption">
                  <div class="pswp__caption__center"></div>
              </div>
          </div>
      </div>
  </div>

  <!-- Footer -->
  <footer class="py-5 bg-black">
    <div class="container">
      <p class="m-0 text-center text-white small">Copyright &copy; Your Website 2019</p>
    </div>
    <!-- /.container -->
  </footer>

  <!-- Bootstrap core JavaScript -->
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Import jQuery and PhotoSwipe Scripts -->
  <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.0/photoswipe.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.0/photoswipe-ui-default.min.js"></script>
</body>
<script type="text/javascript">
    $(".nav-link").click(function() {
        var target = $(this).attr('data-link');
        $('html,body').animate({
                scrollTop: $(target).offset().top},
            'slow');
    });
</script>
<script>
    'use strict';
    /* global jQuery, PhotoSwipe, PhotoSwipeUI_Default, console */
    (function($){
        // Init empty gallery array
        var container = [];
        // Loop over gallery items and push it to the array
        $('#gallery').find('figure').each(function(){
            var $link = $(this).find('a'),
                item = {
                    src: $link.attr('href'),
                    w: $link.data('width'),
                    h: $link.data('height'),
                    title: $link.data('caption')
                };
            container.push(item);
        });
        // Define click event on gallery item
        $('#gallery a').click(function(event){
            // Prevent location change
            event.preventDefault();
            // Define object and gallery options
            var $pswp = $('.pswp')[0],
                options = {
                    index: $(this).parent('figure').index(),
                    bgOpacity: 0.85,
                    showHideOpacity: true
                };
            // Initialize PhotoSwipe
            var gallery = new PhotoSwipe($pswp, PhotoSwipeUI_Default, container, options);
            gallery.init();
        });
        $('input[name="contact"]').click(function(event){

            var chosenOption = $(this).val();
            switch(chosenOption){
                case "emailOption":
                    $('input[name="email"]').parent().removeClass("d-none");
                    $('input[name="phone"]').parent().addClass("d-none");
                    break;
                case "phoneOption":
                    $('input[name="phone"]').parent().removeClass("d-none");
                    $('input[name="email"]').parent().addClass("d-none");
                    break;
            }
        });
    }(jQuery));
</script>
<?php
$files = [];
function random_pic($dir, $store)
{
    global $files;

    if(!isset($files[$store])) {
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file != '.' && $file != '..') {
                        $files[$store][] = $file;
                    }
                }
                closedir($dh);
            }
        }
    }
    //echo '<pre>';
    //var_dump($files);
    //echo '</pre>';
    $pictureIndex = array_rand($files[$store]);
    $pictureFile = $files[$store][$pictureIndex];
    unset($files[$store][$pictureIndex]);
    return $dir . "/" . $pictureFile;
}
?>
</html>