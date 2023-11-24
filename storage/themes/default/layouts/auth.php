<!DOCTYPE html>
<html lang="<?php echo \Core\Localization::locale() ?>"<?php echo \Core\Localization::get('rtl') ? 'dir=""':''?>>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <?php meta() ?>
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link rel="stylesheet" href="<?php echo assets('frontend/libs/select2/dist/css/select2.min.css') ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo assets('cookieconsent.min.css') ?>">
        <link rel="stylesheet" href="<?php echo assets('frontend/css/style'.(request()->cookie('darkmode') || \Helpers\App::themeConfig('homestyle', 'darkmode', true) ? '-dark' : '').'.min.css') ?>" id="stylesheet">
        <?php if(config('font')): ?>
            <link rel="preconnect" href="https://fonts.gstatic.com">
            <link href="https://fonts.googleapis.com/css2?family=<?php echo str_replace(' ', '+', config('font')) ?>:wght@300;400;600" rel="stylesheet">
            <style>body{font-family:'<?php echo config('font') ?>' !important}</style>
        <?php endif ?>
        <?php block('header') ?>
        <?php echo html_entity_decode(config('customheader')) ?>
    </head>
    <body<?php echo \Core\View::bodyClass() ?>>
        <?php section() ?>               
        <script src="<?php echo assets('bundle.pack.js') ?>"></script>   
        <?php if(config('cookieconsent')->enabled): ?>
            <script src="<?php echo assets('cookieconsent.min.js') ?>"></script>
        <?php endif ?>
        <?php block('footer') ?>
        <script type="text/javascript">
            var lang = <?php echo json_encode([       
                "error" => e('Please enter a valid URL.'),
                "cookie" => !empty(config('cookieconsent')->message) ? e(config('cookieconsent')->message) : e("This website uses cookies to ensure you get the best experience on our website."),
                "cookieok" => e("Got it!"),
                "cookiemore" => e("Learn more"),
                "cookielink" => !empty(config('cookieconsent')->link) ? config('cookieconsent')->link : route('page', ['terms']),
                "couponinvalid" => e("The coupon enter is not valid"),
                "minurl" => e("You must select at least 1 url."),
                "minsearch" => e("Keyword must be more than 3 characters!"),
                "nodata" => e("No data is available for this request."),
                "datepicker" => [
                    '7d' => 'Last 7 Days',
                    '3d' => 'Last 30 Days',
                    'tm' => 'This Month',
                    'lm' => 'Last Month',                    
                ]]) ?>
        </script> 
        <script src="<?php echo assets('frontend/js/app.js') ?>"></script>
        <script src="<?php echo assets('custom.min.js') ?>"></script>
        <script src="<?php echo assets('server.min.js') ?>"></script>        
        <script>
            feather.replace({
                'width': '1em',
                'height': '1em'
            })
        </script>
        <?php echo html_entity_decode(config('customfooter')) ?>
        <?php if(!empty(config('analytic'))): ?>
			<script async src='https://www.googletagmanager.com/gtag/js?id=<?php echo config('analytic') ?>'></script><script>window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag('js', new Date());gtag('config', '<?php echo config('analytic') ?>');</script>
		<?php endif ?>
    </body>
</html>