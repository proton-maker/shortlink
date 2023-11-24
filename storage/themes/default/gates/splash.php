

<link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
<link data-optimized="2" rel="stylesheet" href="https://gempixel.com/wp-content/litespeed/css/17b970ea00d1b897710a07a18a8f046b.css?ver=41f02">
<br />
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9941818527091748"
     crossorigin="anonymous"></script>
<ins class="adsbygoogle"
     style="display:block"
     data-ad-format="autorelaxed"
     data-ad-client="ca-pub-9941818527091748"
     data-ad-slot="3497588158"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
<br />
<div class="container my-5">    
    <?php \Helpers\App::ads('resp') ?>
    <div class="card card-body">
        <div class="row">
            <div class="col-md-4">
                <img src="<?php echo \Helpers\App::shortRoute($url->domain, $url->alias.$url->custom).'/i' ?>" class="img-fluid rounded shadow">
            </div>
            <div class="col-md-8">
                <h2>
                    <?php if (!empty($url->meta_title)): ?>
                        <?php echo $url->meta_title ?>
                    <?php else: ?>
                        <?php echo e("You are about to be redirected to another page.") ?>
                    <?php endif ?>
                </h2>
                <p class="description">
                    <?php if (!empty($url->meta_description)): ?>
                        <?php echo $url->meta_description ?>
                    <?php endif ?>
                </p>
                <br>
                <div class="row">
                    <div class="col-sm-6">
                        <a href="<?php echo $url->url ?>" class="btn btn-secondary btn-block redirect" rel="nofollow"><?php echo e("Redirect me"); ?></a>
                    </div>
                    <div class="col-sm-6">
                        <a href="<?php echo config('url') ?>" class="btn btn-primary btn-block" rel="nofollow"><?php echo e("Take me to homepage") ?></a></a>
                    </div>
                </div>
                <hr>
                <p class="disclaimer">
                    <?php echo e("Please Click The Ads So The Shortlink Feature Can Be Used For Free Forever.<br /> You are about to be redirected to another page. We are not responsible for the content of that page or the consequences it may have on you.") ?>
                </p>
            </div>
        </div>
    </div>
</div>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9941818527091748"
     crossorigin="anonymous"></script>
<ins class="adsbygoogle"
     style="display:block"
     data-ad-format="autorelaxed"
     data-ad-client="ca-pub-9941818527091748"
     data-ad-slot="3497588158"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
<br />
<br />