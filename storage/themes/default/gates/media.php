<br /> <br />
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9941818527091748"
     crossorigin="anonymous"></script>
<ins class="adsbygoogle"
     style="display:block; text-align:center;"
     data-ad-layout="in-article"
     data-ad-format="fluid"
     data-ad-client="ca-pub-9941818527091748"
     data-ad-slot="6343177108"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
<div class="container my-5">
    <div class="row">
        <div class="col-md-8">
            <div class="card card-body">
                <div class="embed">
                    <?php echo $url->embed ?>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-9">
                        <h6><?php echo $url->meta_title ?></h6>
                    </div>
                    <div class="col-sm-3 text-right">
                        <span><?php echo $url->click+1 ?></span>
                        <?php echo e("Views") ?>
                    </div>						
                </div>
                <p class="mt-2">
                    <?php echo $url->meta_description ?>				
                </p>					
            </div>
            <?php echo \Helpers\App::ads(728) ?>
        </div>
        <div class="col-md-4">
            <?php echo \Helpers\App::ads(300) ?>
            <div class="card card-body">
                <h6><?php echo e("Short URL") ?></h6>
                <input type="text" class="form-control" value="<?php echo \Helpers\App::shortRoute($url->domain, $url->alias.$url->custom) ?>" readonly>
                <br>
                <a href="#copy" class="btn btn-primary copy" data-clipboard-text="<?php echo \Helpers\App::shortRoute($url->domain, $url->alias.$url->custom) ?>"><?php echo e("Copy") ?></a>
                <?php if(config("sharing")): ?>
                    <hr>
                    <p>
                        <a href="https://www.facebook.com/sharer.php?u=<?php echo \Helpers\App::shortRoute($url->domain, $url->alias.$url->custom) ?>" class="btn btn-facebook btn-block"><?php echo e("Share on") ?> Facebook</a></p>
                    <p><a href="https://twitter.com/share?url=<?php echo \Helpers\App::shortRoute($url->domain, $url->alias.$url->custom) ?>&amp;text=Check+out+this+url" class="btn btn-twitter btn-block"><?php echo e("Share on") ?> Twitter</a>
                    </p>
                <?php endif ?>					
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