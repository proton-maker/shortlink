<h1 class="h3 mb-5"><?php ee('Advanced Settings') ?></h1>
<div class="row">
    <div class="col-md-3 d-none d-lg-block">
        <?php view('admin.partials.settings_menu') ?>
    </div>
    <div class="col-md-12 col-lg-9">
        <div class="card">
            <div class="card-body">
                <form method="post" action="<?php echo route('admin.settings.save') ?>" enctype="multipart/form-data">
                    <?php echo csrf() ?>                                        
                    <div class="form-group">
                        <label for="root_domain" class="form-label"><?php ee('Shorten links with') ?> <strong><?php echo str_replace(["http://", "https://"], "", config("url")) ?></strong></label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" data-binary="true" id="root_domain" name="root_domain" value="1" <?php echo config("root_domain") ? 'checked':'' ?>>
                            <label class="form-check-label" for="root_domain"><?php ee('Enable') ?></label>
                        </div>
                        <p class="form-text"><?php ee('If you have additional domains and you want to prevent people from using the root domain to shorten, disable this.') ?></p>
                    </div>   
                    <div class="form-group">
                        <label for="multiple_domains" class="form-label"><?php ee('Multiple Domain Names') ?></label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" data-binary="true" id="multiple_domains" name="multiple_domains" value="1" <?php echo config("multiple_domains") ? 'checked':'' ?> data-toggle="togglefield" data-toggle-for="domain_names">
                            <label class="form-check-label" for="multiple_domains"><?php ee('Enable') ?></label>
                        </div>
                        <p class="form-text"><?php ee('If enabled users will have the choice to select their preferred domain name from the list below. Make sure that all these point to the script.') ?></p>
                    </div>
                    <div class="form-group <?php echo config("multiple_domains") ? '':'d-none' ?>">
                        <label for="domain_names" class="form-label"><?php ee('Domains') ?></label>
                        <textarea name="domain_names" id="domain_names" rows="5" class="form-control"><?php echo config("domain_names") ?></textarea>	
                        <p class="form-text"><?php ee('One domain per line including http://, do not include your main domain name (read documentation).') ?></p>
                    </div> 
                    <div class="form-group mb-4">
					    <label for="serverip" class="form-label"><?php ee('Server IP') ?></label>
					    <input class="form-control" name="serverip" id="serverip" value="<?php echo config('serverip') ?>">
					    <p class="form-text"><?php ee('Add your server IP here to enable A records. Otherwise your customers can only use CNAME.') ?></p>
                    </div>       
                    <div class="form-group mb-4">
					    <label for="analytic" class="form-label"><?php ee('Google Analytics Account ID') ?></label>
					    <input class="form-control" name="analytic" id="analytic" value="<?php echo config('analytic') ?>">
					    <p class="form-text"><?php ee('Your Google Analytics account id e.g. G-12345678-1. This will be used to collect data separately for your information only.') ?></p>
                    </div>    
                    <hr>            
                    <div class="form-group mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" data-binary="true" id="deepl[enabled]" name="deepl[enabled]" value="1" <?php echo config("deepl")->enabled ? 'checked':'' ?>>
                            <label class="form-check-label" for="deepl[enabled]"><?php ee('Enable') ?></label>
                        </div>
                        <p class="form-text"><?php ee('Enable DeepL API') ?>
                    </div>
                    <div class="form-group mb-4">
					    <label for="deepl[key]" class="form-label"><?php ee('DeepL API Key') ?></label>
					    <input class="form-control" name="deepl[key]" id="deepl[key]" value="<?php echo config('deepl')->key ?>">
					    <p class="form-text"><?php ee('Enter your DeepL API key so you can translate languages files automatically.') ?> <a href="https://www.deepl.com/" target="blank">DeepL</a></p>
                    </div>
                    <div class="form-group">
                        <label for="deepl[limit]" class="form-label"><?php ee('Limit to 500,000 characters') ?></label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" data-binary="true" id="deepl[limit]" name="deepl[limit]" value="1" <?php echo config("deepl")->limit ? 'checked':'' ?>>
                            <label class="form-check-label" for="deepl[limit]"><?php ee('Enable Limit') ?></label>
                        </div>
                        <p class="form-text"><?php ee("DeepL's free plan limits to 500,000 characters and will charge after that. You can enable this option stop translation at about 500,000 characters. You will need make sure on your own to not exceed this limit. Do not run the translator on another language if you have reached the limit.") ?></p>
                    </div> 
                    <button type="submit" class="btn btn-primary"><?php ee('Save Settings') ?></button>
                </form>

            </div>
        </div>
    </div>
</div>