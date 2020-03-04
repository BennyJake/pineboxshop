<?php
require_once("vendor/autoload.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Rakit\Validation\Validator;
use Sabre\HTTP;
use Twilio\Rest\Client as TwilioClient;

$config = require_once('config.php');

$imageArray = [
    'book1' => [
        'title' => 'Placeholder',
        'description' => 'Placeholder',
    ],
    'coffee1' => [
        'title' => 'Placeholder',
        'description' => 'Placeholder',
    ],
    'coffee2' => [
        'title' => 'Placeholder',
        'description' => 'Placeholder',
    ],
    'divider1' => [
        'title' => 'Placeholder',
        'description' => 'Placeholder',
    ],
    'trash1' => [
        'title' => 'Placeholder',
        'description' => 'Placeholder',
    ],
    'wine1' => [
        'title' => 'Placeholder',
        'description' => 'Placeholder',
    ],
];

/*if (!session_id()) {
    session_start();
}

// https://www.facebook.com/thepineboxshop/photos/?tab=album&album_id=1557869774370222

$fb = new \Facebook\Facebook([
    'app_id' => '',
    'app_secret' => '',
    'default_graph_version' => 'v2.10',
    //'default_access_token' => '{access-token}', // optional
]);

$helper = $fb->getRedirectLoginHelper();

try {
    $accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}


try {
    // Get the \Facebook\GraphNodes\GraphUser object for the current user.
    // If you provided a 'default_access_token', the '{access-token}' is optional.
    //$response = $fb->get('/me', $accessToken);
    $response = $fb->get('/1557869774370222/photos', $accessToken);

} catch(\Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(\Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}*/

$request = HTTP\Sapi::getRequest();

$post = $request->getPostData();
$validationArray = [];
if(isset($post) && !empty($post)){
    $validator = new Validator([
            'required' => 'Please provide a :attribute'
    ]);

    // make it
    $validation = $validator->make($post, [
        'name'                  => 'required',
        'contact'               => 'required',
        'email'                 => 'required_if:contact,emailOption|email',
        'phone'                 => 'required_if:contact,phoneOption',
        'project'               => 'required',
        'custom'                => 'required_if:project,customOption',
    ]);

    $validation->validate();

    $errors = $validation->errors();
    $validationArray = $errors->all('<li>:message</li>');

    if(sizeof($validationArray) == 0){

        $validatedData = $validation->getValidatedData();

        $mail = new \PHPMailer\PHPMailer\PHPMailer();

        $mail->IsSMTP();
        $mail->Mailer = "smtp";
        $mail->SMTPDebug  = 0;
        $mail->SMTPAuth   = TRUE;
        $mail->SMTPSecure = "tls";

        // /etc/php/7.2/fpm/pool.d/www.conf
        $mail->Port       = $config['PINEBOXSHOP_PORT'];
        $mail->Host       = $config['PINEBOXSHOP_HOST'];
        $mail->Username   = $config['PINEBOXSHOP_USER'];
        $mail->Password   = $config['PINEBOXSHOP_PASS'];

        $mail->setFrom($config['PINEBOXSHOP_FROM']);
        $mail->addAddress($config['PINEBOXSHOP_TOEM'], $config['PINEBOXSHOP_TONM']);

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'The Pine Box Shop Website';
        $mail->Body    = 'Name: ' . $validatedData['name'];
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        try{
            $mail->send();
        } catch (Exception $e) {
            //TODO: Catch this somehow
            //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        $AccountSid = $config['PINEBOXSHOP_TWILIO_ACCT_SID'];
        $AuthToken = $config['PINEBOXSHOP_TWILIO_AUTH_TOK'];

        $client = new TwilioClient($AccountSid, $AuthToken);

        $client->messages->create(
            $config['PINEBOXSHOP_TWILIO_FROM_NUM'],
            [
                'from' => $config['PINEBOXSHOP_TWILIO_TO_NUM'],
                'body' => "From BennyJake.com "
            ]
        );
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<style type="text/css">
    header {
        background-image: url(<?= random_pic("img/header", "main")?>);
    }
    #gallery img{
        vertical-align: middle;
        border-style: none;
        width: 100%;
    }
    body {
        background-color: #cccccc;
        color: #333333;
    }
    .masonry-wrapper {
        padding: 1.5em;
        max-width: 1140px;
        margin-right: auto;
        margin-left: auto;
    }
    .masonry {
        display: grid;
        grid-template-columns: repeat(1, minmax(100px,1fr));
        grid-gap: 10px;
        grid-auto-rows: 0;
    }
    @media only screen and (max-width: 1023px) and (min-width: 768px) {
        .masonry {
            grid-template-columns: repeat(2, minmax(100px,1fr));
        }
    }
    @media only screen and (min-width: 1024px) {
        .masonry {
            grid-template-columns: repeat(3, minmax(100px,1fr));
        }
    }
    .masonry-item, .masonry-content {
        border-radius: 4px;
        overflow: hidden;
    }
    .masonry-item {
        filter: drop-shadow(0px 2px 2px rgba(0, 0, 0, .3));
        transition: filter .25s ease-in-out;
    }
    .masonry-item:hover {
        filter: drop-shadow(0px 5px 5px rgba(0, 0, 0, .3));
    }
    .masonry-content {
        overflow: hidden;
    }
    .masonry-item {
        color: #111111;
        background-color: #f9f9f9;
    }
    .masonry-title, .masonry-description {
        margin: 0;
    }
    .masonry-title {
        font-weight: 700;
        font-size: 1.1rem;
        padding: 1rem 1.5rem;
    }
    .masonry-description {
        padding: 1.5rem;
        font-size: .75rem;
        border-top: 1px solid rgba(0, 0, 0, .05);
    }
    .active .nav-link{
        text-decoration: underline;
    }
</style>


  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>The Pine Box Shop</title>
    <script src="js/lightbox-plus-jquery.js"></script>


  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

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
<style type="text/css">
    header {
        background: url("img/woodshop.jpg");
    }
    body {
        background-color:#F2EADE !important;
        color: #160700 !important;
    }
    h1, h2, h3, p, span{
        color: #160700 !important;
    }
    #gallery img{
        vertical-align: middle;
        border-style: none;
        width: 100%;
    }
    #home-logo{
        color:#160700;
    }
    .navbar-custom{
        background-color: #160700;
    }
</style>
<body>

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
    <div class="container">
      <a class="navbar-brand" data-link="#home" href="#home">The Pine Box Shop</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" data-link="#home" href="#home">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-link="#about_me" href="#about_me">About Me</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-link="#gallery" href="#gallery">Gallery</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-link="#contact_me" href="#contact_me">Contact Me</a>
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
              <img src="img/PB_Logo_Final.svg" id="home-logo" class="centered-logo"/>
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
            <h2 class="display-4">About Pine Box Shop</h2>
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
                      <h2 class="display-4 text-center">Work Examples</h2>
                      <p>Some blurbage.</p>
                  </div>
                  <div class="row">

                      <div class="masonry-wrapper">
                          <div class="masonry">
                              <?php foreach(scandir('img/gallery_full') as $img) {
                                  if ($img != '.' && $img != '..') {
                                      ?>
                                      <div class="masonry-item">
                                          <div class="masonry-content">
                                              <a href="img/gallery_full/<?= $img ?>" data-caption="Sea side, south shore<br><em class='text-muted'>© Dominik Schröder</em>" data-width="1200" data-height="900" itemprop="contentUrl">
                                              <img src="img/gallery_full/<?= $img ?>" alt="Dummy Image">
                                              </a>
                                              <h3 class="masonry-title">

                                                  <?= $imageArray[str_replace(['.jpg','.jpeg'], '', $img)]['title'] ?? "Nesciunt aspernatur eaque similique laudantium a" ?></h3>
                                              <p class="masonry-description"><?= $imageArray[str_replace(['.jpg','.jpeg'], '', $img)]['description'] ?? "Lorem ipsum dolor sit amet, consectetur
                                                  adipisicing elit. Assumenda modi inventore, totam vero consequuntur,
                                                  aut animi veritatis tempora nulla facere placeat velit illum explicabo
                                                  dicta enim ipsum. Vitae ducimus, ratione."?></p>
                                          </div>
                                      </div>
                                  <?php }
                              }
                                  ?>
                          </div>
                      </div><script src="//unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script>

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

                </div>
                <?php
                if(!empty($validationArray) && isset($validationArray)){ ?>
                <div class="error">
                    <div class="invalid-feedback" style="display:block;font-size:1.2rem;">
                        <?php
                        if(!empty($validationArray) && isset($validationArray)){ ?>
                            <ul>
                                <?php foreach($validationArray as $message) { ?>
                                    <?= $message ?>
                                    <?php
                                }
                                ?>
                            </ul>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>
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
  <footer class="py-5">
    <div class="container">
      <p class="m-0 text-center text-white small">Copyright &copy; The Pine Box Shop <?= date('Y', strtotime('now')) ?></p>
    </div>
    <!-- /.container -->
  </footer>

  <!-- Import jQuery and PhotoSwipe Scripts -->
  <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.0/photoswipe.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.0/photoswipe-ui-default.min.js"></script>

  <!-- Bootstrap core JavaScript -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

</body>
<script src="js/menuspy.min.js"></script>
<script>

    var elm = document.querySelector('.navbar');
    var ms = new MenuSpy(elm);

    $(".nav-link").click(function(event) {
        event.preventDefault();
        var target = $(this).attr('href');
        $('#navbarResponsive').collapse('hide');
        $('html,body').animate({
                scrollTop: $(target).offset().top},
            'slow');
    });
</script>
<script>
    'use strict';
    /* global jQuery, PhotoSwipe, PhotoSwipeUI_Default, console */
    /**
     * Set appropriate spanning to any masonry item
     *
     * Get different properties we already set for the masonry, calculate
     * height or spanning for any cell of the masonry grid based on its
     * content-wrapper's height, the (row) gap of the grid, and the size
     * of the implicit row tracks.
     *
     * @param item Object A brick/tile/cell inside the masonry
     * @link https://w3bits.com/css-grid-masonry/
     */
    function resizeMasonryItem(item){
        /* Get the grid object, its row-gap, and the size of its implicit rows */
        var grid = document.getElementsByClassName('masonry')[0];
        if( grid ) {
            var rowGap = parseInt(window.getComputedStyle(grid).getPropertyValue('grid-row-gap')),
                rowHeight = parseInt(window.getComputedStyle(grid).getPropertyValue('grid-auto-rows')),
                gridImagesAsContent = item.querySelector('img.masonry-content');

            /*
             * Spanning for any brick = S
             * Grid's row-gap = G
             * Size of grid's implicitly create row-track = R
             * Height of item content = H
             * Net height of the item = H1 = H + G
             * Net height of the implicit row-track = T = G + R
             * S = H1 / T
             */
            var rowSpan = Math.ceil((item.querySelector('.masonry-content').getBoundingClientRect().height+rowGap)/(rowHeight+rowGap));

            /* Set the spanning as calculated above (S) */
            item.style.gridRowEnd = 'span '+rowSpan;
            if(gridImagesAsContent) {
                item.querySelector('img.masonry-content').style.height = item.getBoundingClientRect().height + "px";
            }
        }
    }

    /**
     * Apply spanning to all the masonry items
     *
     * Loop through all the items and apply the spanning to them using
     * `resizeMasonryItem()` function.
     *
     * @uses resizeMasonryItem
     * @link https://w3bits.com/css-grid-masonry/
     */
    function resizeAllMasonryItems(){
        // Get all item class objects in one list
        var allItems = document.querySelectorAll('.masonry-item');

        /*
         * Loop through the above list and execute the spanning function to
         * each list-item (i.e. each masonry item)
         */
        if( allItems ) {
            for(var i=0;i>allItems.length;i++){
                resizeMasonryItem(allItems[i]);
            }
        }
    }

    /**
     * Resize the items when all the images inside the masonry grid
     * finish loading. This will ensure that all the content inside our
     * masonry items is visible.
     *
     * @uses ImagesLoaded
     * @uses resizeMasonryItem
     * @link https://w3bits.com/css-grid-masonry/
     */
    function waitForImages() {
        //var grid = document.getElementById("masonry");
        var allItems = document.querySelectorAll('.masonry-item');
        if( allItems ) {
            for(var i=0;i<allItems.length;i++){
                imagesLoaded( allItems[i], function(instance) {
                    var item = instance.elements[0];
                    resizeMasonryItem(item);
                    console.log("Waiting for Images");
                } );
            }
        }
    }

    /* Resize all the grid items on the load and resize events */
    var masonryEvents = ['load', 'resize'];
    masonryEvents.forEach( function(event) {
        window.addEventListener(event, resizeAllMasonryItems);
    } );

    /* Do a resize once more when all the images finish loading */
    waitForImages();

    (function($){
        // Init empty gallery array
        var container = [];
        // Loop over gallery items and push it to the array
        $('#gallery').find('.masonry-item').each(function(){
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
                    index: $(this).parent('figure').parent().index(),
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