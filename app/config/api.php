<?php
/**
 * ====================================================================================
 *                           GemFramework (c) gempixel.com
 * ----------------------------------------------------------------------------------
 *  This software is packaged with an exclusive framework owned by gempixel.com Inc as such
 *  distribution or modification of this framework is not allowed before prior consent
 *  from gempixel.com administrators. If you find that this framework is packaged in a 
 *  software not distributed by gempixel.com or authorized parties, you must not use this
 *  software and contact gempixel.com at https://gempixel.com/contact to inform them of this
 *  misuse otherwise you risk of being prosecuted in courts.
 * ====================================================================================
 *
 * @package AppConfig
 * @author gempixel.com (http://gempixel.com.com)
 * @copyright 2020 gempixel.com
 * @license http://gempixel.com.com/license
 * @link http://gempixel.com.com  
 * @since 1.0
 */
$pixels = [];
foreach(\Traits\Pixels::pixels() as $pixel => $data){
    $pixels[] = $pixel;
}

return [

    'account' => [
        'admin' => false,
        'title' => e('Account'),
        'description' => null,
        'endpoints' => [
            [
                'title' => e('Get Account'),
                'method' => 'GET',
                'route' => route('api.account.get'),
                'description' => e('To get information on the account, you can send a request to this endpoint and it will return data on the account.'),
                'parameters' => null,
                'code' => null,
                'response' => [
                    "error" => 0,
                    "data" => [
                        "id" => 1,
                        "email" => "sample@domain.com",
                        "username" => "sampleuser",
                        "avatar" => "https://domain.com/content/avatar.png",
                        "status" => "pro",
                        "expires" => "2022-11-15 15:00:00",
                        "registered" => "2020-11-10 18:01:43",
                    ]
                ]
            ],
            [
                'title' => e('Update Account'),
                'method' => 'PUT',
                'route' => route('api.account.update'),
                'description' => e('To update information on the account, you can send a request to this endpoint and it will update data on the account.'),
                'parameters' => null,
                'code' => [
                    "email" => "newemail@google.com",
                    "password" => "newpassword",    
                ],
                'response' => [
                    "error" => 0,
                    "message" => 'Account has been successfully updated.'
                ]
            ]
        ]
    ],
    'domains' => [
        'admin' => false,
        'title' => e('Branded Domains'),
        'description' => null,
        'endpoints' => [
            [
                'title' => e('List all branded domains'),
                'method' => 'GET',
                'route' => route('api.domain.get', ['limit' => 2, 'page' => '1']),
                'description' => e('To get your branded domains codes via the API, you can use this endpoint. You can also filter data (See table for more info).'),
                'parameters' => [
                    'limit' => e('(optional) Per page data result'),
                    'page' => e('(optional) Current page request'),
                ],                
                'code' => null,
                'response' => [
                        'error' => '0',
                        'data' => [
                            'result' => 2,
                            'perpage' => 2,
                            'currentpage' => 1,
                            'nextpage' => 1,
                            'maxpage' => 1,
                            'domains' => [
                                [
                                'id' => 1,
                                'domain' => 'https://domain1.com',
                                'redirectroot' => 'https://rootdomain.com',
                                'redirect404' => 'https://rootdomain.com/404',                                
                                ],
                                [
                                'id' => 2,
                                'domain' => 'https://domain2.com',
                                'redirectroot' => 'https://rootdomain2.com',
                                'redirect404' => 'https://rootdomain2.com/404',                                
                                ],
                            ],
                        ]
                    ]
            ],
            [
                'title' => e('Create a Branded Domain'),
                'method' => 'POST',
                'route' => route('api.domain.create'),
                'description' => e('A domain can be added using this endpoint. Please make sure the domain is correctly pointed to our server.'),
                'parameters' => [                    
                    'domain' => e('(required) Branded domain including http or https'),
                    'redirectroot' => e('(optional) Root redirect when someone visits your domain'),
                    'redirect404' => e('(optional) Custom 404 redirect')
                ],  
                'code' => [
                    'domain' => 'https://domain1.com',
                    'redirectroot' => 'https://rootdomain.com',
                    'redirect404' => 'https://rootdomain.com/404',          
                ],
                'response' => [
                        'error' => 0,
                        'id' => 1
                    ]
            ],
            [
                'title' => e('Update a Domain'),
                'method' => 'PUT',
                'route' => route('api.domain.update', [':id']),
                'description' => e('To update a branded domain, you need to send a valid data in JSON via a PUT request. The data must be sent as the raw body of your request as shown below. The example below shows all the parameters you can send but you are not required to send all (See table for more info).'),
                'parameters' => [
                    'redirectroot' => e('(optional) Root redirect when someone visits your domain'),
                    'redirect404' => e('(optional) Custom 404 redirect')
                ],  
                'code' => [
                    'redirectroot' => 'https://rootdomain-new.com',
                    'redirect404' => 'https://rootdomain-new.com/404',   
                ],
                'response' => [
                        'error' => 0,
                        'message' => 'Domain has been updated successfully.'
                    ]
            ],
            [
                'title' => e('Delete Domain'),
                'method' => 'DELETE',
                'route' => route('api.domain.delete', [':id']),
                'description' => e('To delete a domain, you need to send a DELETE request.'),
                'parameters' => null,  
                'code' => null,
                'response' => [
                        'error' => 0,
                        'message' => 'Domain has been deleted successfully.'
                    ]
            ]
        ]        
    ],
    'splash' => [
        'admin' => false,
        'title' => e('Custom Splash'),
        'description' => null,
        'endpoints' => [
            [
                'title' => e('List all custom splash'),
                'method' => 'GET',
                'route' => route('api.splash.get', ['limit' => 2, 'page' => '1']),
                'description' => e('To get custom splash pages via the API, you can use this endpoint. You can also filter data (See table for more info).'),
                'parameters' => [
                    'limit' => e('(optional) Per page data result'),
                    'page' => e('(optional) Current page request'),
                ],                
                'code' => null,
                'response' => [
                        'error' => '0',
                        'data' => [
                            'result' => 2,
                            'perpage' => 2,
                            'currentpage' => 1,
                            'nextpage' => 1,
                            'maxpage' => 1,
                            'splash' => [
                                [
                                'id' => 1,
                                'name' => 'Product 1 Promo',
                                'date' => '2020-11-10 18:00:00',
                                ],
                                [
                                'id' => 2,
                                'name' => 'Product 2 Promo',
                                'date' => '2020-11-10 18:10:00',
                                ],
                            ],
                        ]
                    ]
            ],
        ]
    ],
    'cta' => [
        'admin' => false,
        'title' => e('CTA Overlays'),
        'description' => null,
        'endpoints' => [
            [
                'title' => e('List all cta overlays'),
                'method' => 'GET',
                'route' => route('api.overlay.get', ['limit' => 2, 'page' => '1']),
                'description' => e('To get cta overlays via the API, you can use this endpoint. You can also filter data (See table for more info).'),
                'parameters' => [
                    'limit' => e('(optional) Per page data result'),
                    'page' => e('(optional) Current page request'),
                ],                
                'code' => null,
                'response' => [
                        'error' => '0',
                        'data' => [
                            'result' => 2,
                            'perpage' => 2,
                            'currentpage' => 1,
                            'nextpage' => 1,
                            'maxpage' => 1,
                            'cta' => [
                                [
                                'id' => 1,
                                'type' => 'message',
                                'name' => 'Product 1 Promo',
                                'date' => '2020-11-10 18:00:00',
                                ],
                                [
                                'id' => 2,
                                'type' => 'contact',
                                'name' => 'Contact Page',
                                'date' => '2020-11-10 18:10:00',
                                ],
                            ],
                        ]
                    ]
            ],
        ]
    ],     
    'links' => [
        'admin' => false,
        'title' => e('Links'),
        'description' => null,
        'endpoints' => [
            [
                'title' => e('List all Links'),
                'method' => 'GET',
                'route' => route('api.url.get', ['limit' => 2, 'page' => '1', 'order' => 'date']),
                'description' => e('To get your links via the API, you can use this endpoint. You can also filter data (See table for more info).'),
                'parameters' => [
                    'limit' => e('(optional) Per page data result'),
                    'page' => e('(optional) Current page request'),
                    'order' => e('(optional) Sort data between date or click'),
                ],                
                'code' => null,                
                'response' => [
                        'error' => '0',
                        'data' => [
                            'result' => 2,
                            'perpage' => 2,
                            'currentpage' => 1,
                            'nextpage' => 1,
                            'maxpage' => 1,
                            'urls' => [
                                [
                                'id' => 2,
                                'alias' => 'google',
                                'shorturl' => route('redirect', ['google']),
                                'longurl' => 'https://google.com',
                                'clicks' => 0,
                                'title' => 'Google',
                                'description' => '',
                                'date' => '2020-11-10 18:01:43',
                                ],
                                [
                                'id' => 1,
                                'alias' => 'googlecanada',
                                'shorturl' => route('redirect', ['googlecanada']),
                                'longurl' => 'https://google.ca',
                                'clicks' => 0,
                                'title' => 'Google Canada',
                                'description' => '',
                                'date' => '2020-11-10 18:00:25',
                                ],
                            ],
                        ]
                    ]
            ],
            [
                'title' => e('Get a single link'),
                'method' => 'GET',
                'route' => route('api.url.single', [':id']),
                'description' => e('To get details for a single link via the API, you can use this endpoint.'),
                'parameters' => null,  
                'code' => null,
                'response' => [
                        'error' => 0,
                        'details' => [
                          'id' => 1,
                          'shorturl' => route('redirect', ['googlecanada']),
                          'longurl' => 'https://google.com',
                          'title' => 'Google',
                          'description' => '',
                          'location' => [
                            'canada' => 'https://google.ca',
                            'united states' => 'https://google.us',
                          ],
                          'device' => [
                            'iphone' => 'https://google.com',
                            'android' => 'https://google.com',
                          ],
                          'expiry' => NULL,
                          'date' => '2020-11-10 18:01:43',
                        ],
                        'data' => [
                          'clicks' => 0,
                          'uniqueClicks' => 0,
                          'topCountries' => 0,
                          'topReferrers' => 0,
                          'topBrowsers' => 0,
                          'topOs' => 0,
                          'socialCount' => [
                            'facebook' => 0,
                            'twitter' => 0,
                            'google' => 0,
                          ],
                        ]
                ]
            ],
            [
                'title' => e('Shorten a Link'),
                'method' => 'POST',
                'route' => route('api.url.create'),
                'description' => e('To shorten a link, you need to send a valid data in JSON via a POST request. The data must be sent as the raw body of your request as shown below. The example below shows all the parameters you can send but you are not required to send all (See table for more info).'),
                'parameters' => [
                    'url' => e('(required) Long URL to shorten.'),
                    'custom' => e('(optional) Custom alias instead of random alias.'),
                    'type' => e('(optional) Redirection type [direct, frame, splash], only <i>id</i> for custom splash page or <i>overlay-id</i> for cta pages'),
                    'password' => e('(optional) Password protection'),
                    'domain' => e('(optional) Custom Domain'),
                    'expiry' => e('(optional) Expiration for the link example 2021-09-28 23:11:16'),
                    'geotarget' => e('(optional) Geo targeting data'),
                    'devicetarget' => e('(optional) Device targeting data'),
                    'metatitle' => e('(optional) Meta title'),
                    'metadescription' => e('(optional) Meta description'),
                ],  
                'code' => [
                    'url' => 'https://google.com',
                    'custom' => 'google',
                    'password' => 'mypass',
                    'expiry' => '2020-11-11 12:00:00',
                    'type' => 'splash',
                    'geotarget' => [     
                      [                 
                        'location' => 'Canada',
                        'link' => 'https://google.ca',
                      ],
                      [
                        'location' => 'United States',
                        'link' => 'https://google.us',
                      ],
                    ],
                    'devicetarget' => [     
                      [
                        'device' => 'iPhone',
                        'link' => 'https://google.com',
                      ],
                      [
                        'device' => 'Android',
                        'link' => 'https://google.com',
                      ],
                    ],
                    'parameters' => [     
                        [
                          'name' => 'aff',
                          'value' => '3',
                        ],
                        [
                          'device' => 'gtm_source',
                          'link' => 'api',
                        ],
                    ],
                  ],
                'response' => [
                        'error' => 0,
                        'id' => 3,
                        'shorturl' => route('redirect', 'google'),
                    ]
            ],
            [
                'title' => e('Update a Link'),
                'method' => 'PUT',
                'route' => route('api.url.update', [':id']),
                'description' => e('To update a link, you need to send a valid data in JSON via a PUT request. The data must be sent as the raw body of your request as shown below. The example below shows all the parameters you can send but you are not required to send all (See table for more info).'),
                'parameters' => [
                    'url' => e('(required) Long URL to shorten.'),
                    'custom' => e('(optional) Custom alias instead of random alias.'),
                    'type' => e('(optional) Redirection type [direct, frame, splash]'),
                    'password' => e('(optional) Password protection'),
                    'domain' => e('(optional) Custom Domain'),
                    'expiry' => e('(optional) Expiration for the link example 2021-09-28 23:11:16'),
                    'geotarget' => e('(optional) Geo targeting data'),
                    'devicetarget' => e('(optional) Device targeting data'),
                ],  
                'code' => [
                    'url' => 'https://google.com',
                    'custom' => 'google',
                    'password' => 'mypass',
                    'expiry' => '2020-11-11 12:00:00',
                    'type' => 'splash',
                    'geotarget' => [     
                      [                 
                        'location' => 'Canada',
                        'link' => 'https://google.ca',
                      ],
                      [
                        'location' => 'United States',
                        'link' => 'https://google.us',
                      ],
                    ],
                    'devicetarget' => [     
                      [
                        'device' => 'iPhone',
                        'link' => 'https://google.com',
                      ],
                      [
                        'device' => 'Android',
                        'link' => 'https://google.com',
                      ],
                    ],
                    'parameters' => [     
                        [
                          'name' => 'aff',
                          'value' => '3',
                        ],
                        [
                          'device' => 'gtm_source',
                          'link' => 'api',
                        ],
                    ],
                  ],
                'response' => [
                        'error' => 0,
                        'id' => 3,
                        'short' => route('redirect', 'google'),
                    ]
            ],
            [
                'title' => e('Delete a Link'),
                'method' => 'DELETE',
                'route' => route('api.url.delete', [':id']),
                'description' => e('To delete a link, you need to send a DELETE request.'),
                'parameters' => null,  
                'code' => null,
                'response' => [
                        'error' => 0,
                        'message' => 'Link has been deleted successfully'
                    ]
            ]
        ]        
    ],       
    'pixels' => [
        'admin' => false,
        'title' => e('Pixels'),
        'description' => null,
        'endpoints' => [
            [
                'title' => e('List all pixels'),
                'method' => 'GET',
                'route' => route('api.pixels.get', ['limit' => 2, 'page' => '1']),
                'description' => e('To get your pixels codes via the API, you can use this endpoint. You can also filter data (See table for more info).'),
                'parameters' => [
                    'limit' => e('(optional) Per page data result'),
                    'page' => e('(optional) Current page request'),
                ],                
                'code' => null,
                'response' => [
                        'error' => '0',
                        'data' => [
                            'result' => 2,
                            'perpage' => 2,
                            'currentpage' => 1,
                            'nextpage' => 1,
                            'maxpage' => 1,
                            'pixels' => [
                                [
                                'id' => 1,
                                'type' => 'gtmpixel',
                                'name' => 'GTM Pixel',
                                'tag' => 'GA-123456789',
                                'date' => '2020-11-10 18:00:00',
                                ],
                                [
                                'id' => 2,
                                'type' => 'twitterpixel',
                                'name' => 'Twitter Pixel',
                                'tag' => '1234567',
                                'date' => '2020-11-10 18:10:00',
                                ],
                            ],
                        ]
                    ]
            ],
            [
                'title' => e('Create a Pixel'),
                'method' => 'POST',
                'route' => route('api.pixel.create'),
                'description' => e('A pixel can be created using this endpoint. You need to send the pixel type and the tag.'),
                'parameters' => [
                    'type' => '(required) '.implode(' | ', $pixels),
                    'name' => e('(required) Custom name for your pixel'),
                    'tag' => e('(required) The tag for the pixel'),
                ],  
                'code' => [
                    'type' => 'gtmpixel',
                    'name' => 'My GTM',
                    'tag' => 'GTM-ABCDE'              
                ],
                'response' => [
                        'error' => 0,
                        'id' => 1
                    ]
            ],
            [
                'title' => e('Update a Pixel'),
                'method' => 'PUT',
                'route' => route('api.pixel.update', [':id']),
                'description' => e('To update a pixel, you need to send a valid data in JSON via a PUT request. The data must be sent as the raw body of your request as shown below. The example below shows all the parameters you can send but you are not required to send all (See table for more info).'),
                'parameters' => [
                    'name' => e('(optional) Custom name for your pixel'),
                    'tag' => e('(required) The tag for the pixel'),
                ],  
                'code' => [
                    'name' => 'My GTM',
                    'tag' => 'GTM-ABCDE'   
                ],
                'response' => [
                        'error' => 0,
                        'message' => 'Pixel has been updated successfully.'
                    ]
            ],
            [
                'title' => e('Delete Pixel'),
                'method' => 'DELETE',
                'route' => route('api.pixel.delete', [':id']),
                'description' => e('To delete a pixel, you need to send a DELETE request.'),
                'parameters' => null,  
                'code' => null,
                'response' => [
                        'error' => 0,
                        'message' => 'Pixel has been deleted successfully.'
                    ]
            ]
        ]        
    ],    
    'qr' => [
        'admin' => false,
        'title' => e('QR Codes'),
        'description' => null,
        'endpoints' => [
            [
                'title' => e('List all QR codes'),
                'method' => 'GET',
                'route' => route('api.qr.get', ['limit' => 2, 'page' => '1']),
                'description' => e('To get your QR codes via the API, you can use this endpoint. You can also filter data (See table for more info).'),
                'parameters' => [
                    'limit' => e('(optional) Per page data result'),
                    'page' => e('(optional) Current page request'),
                ],                
                'code' => null,
                'response' => [
                        'error' => '0',
                        'data' => [
                            'result' => 2,
                            'perpage' => 2,
                            'currentpage' => 1,
                            'nextpage' => 1,
                            'maxpage' => 1,
                            'qrs' => [
                                [
                                'id' => 2,
                                'link' => route('qr.generate', 'a2d5e'),
                                'scans' => 0,
                                'name' => 'Google',
                                'date' => '2020-11-10 18:01:43',
                                ],
                                [
                                'id' => 1,
                                'link' => route('qr.generate', 'b9edfe'),
                                'scans' => 5,
                                'name' => 'Google Canada',
                                'date' => '2020-11-10 18:00:25',
                                ],
                            ],
                        ]
                    ]
            ],
            [
                'title' => e('Get a single QR Code'),
                'method' => 'GET',
                'route' => route('api.qr.single', [':id']),
                'description' => e('To get details for a single QR code via the API, you can use this endpoint.'),
                'parameters' => null,  
                'code' => null,
                'response' => [
                        'error' => 0,
                        'details' => [
                            'id' => 1,
                            'link' => route('qr.generate', 'b9edfe'),
                            'scans' => 5,
                            'name' => 'Google Canada',
                            'date' => '2020-11-10 18:00:25'
                        ],
                        'data' => [
                            'clicks' => 1,
                            'uniqueClicks' => 1,
                            'topCountries' => [
                              'Unknown' => '1',
                            ],
                            'topReferrers' => [
                              'Direct, email and other' => '1',
                            ],
                            'topBrowsers' => [
                              'Chrome' => '1',
                            ],
                            'topOs' => [
                              'Windows 10' => '1',
                            ],
                            'socialCount' => [
                              'facebook' => 0,
                              'twitter' => 0,
                              'instagram' => 0,
                            ],
                        ]
                ]
            ],
            [
                'title' => e('Create a QR Code'),
                'method' => 'POST',
                'route' => route('api.qr.create'),
                'description' => e('To create a QR Code, you need to send a valid data in JSON via a POST request. The data must be sent as the raw body of your request as shown below. The example below shows all the parameters you can send but you are not required to send all (See table for more info).'),
                'parameters' => [
                    'type' => e('(required) text | vcard | link | email | phone | sms | wifi'),
                    'data' => e('(required) Data to be embedded inside the QR code. The data can be string or array depending on the type'),
                    'background' => e('(optional) RGB color e.g. rgb(255,255,255)'),
                    'foreground' => e('(optional) RGB color e.g. rgb(0,0,0)'),
                    'logo' => e('(optional) Path to the logo either png or jpg'),
                ],  
                'code' => [
                    'type' => 'link',
                    'data' => 'https://google.com',
                    'background' => 'rgb(255,255,255)',
                    'foreground' => 'rgb(0,0,0)',
                    'logo' => 'https://site.com/logo.png'                  
                ],
                'response' => [
                        'error' => 0,
                        'id' => 3,
                        'link' => route('qr.generate', 'a58f79'),
                    ]
            ],
            [
                'title' => e('Update a QR Code'),
                'method' => 'PUT',
                'route' => route('api.qr.update', [':id']),
                'description' => e('To update a QR Code, you need to send a valid data in JSON via a PUT request. The data must be sent as the raw body of your request as shown below. The example below shows all the parameters you can send but you are not required to send all (See table for more info).'),
                'parameters' => [
                    'data' => e('(required) Data to be embedded inside the QR code. The data can be string or array depending on the type'),
                    'background' => e('(optional) RGB color e.g. rgb(255,255,255)'),
                    'foreground' => e('(optional) RGB color e.g. rgb(0,0,0)'),
                    'logo' => e('(optional) Path to the logo either png or jpg'),
                ],  
                'code' => [
                    'type' => 'link',
                    'data' => 'https://google.com',
                    'background' => 'rgb(255,255,255)',
                    'foreground' => 'rgb(0,0,0)',
                    'logo' => 'https://site.com/logo.png'
                ],
                'response' => [
                        'error' => 0,
                        'message' => 'QR has been updated successfully.'
                    ]
            ],
            [
                'title' => e('Delete a QR Code'),
                'method' => 'DELETE',
                'route' => route('api.qr.delete', [':id']),
                'description' => e('To delete a QR code, you need to send a DELETE request.'),
                'parameters' => null,  
                'code' => null,
                'response' => [
                        'error' => 0,
                        'message' => 'QR Code has been deleted successfully.'
                    ]
            ]
        ]        
    ],    
    'plans' => [
        'admin' => true,
        'title' => e('Plans'),
        'description' => '<span class="alert alert-warning text-dark">'.e('This endpoint is only accessible by users with admin privileges.').'</span>',
        'endpoints' => [
            [
                'title' => e('List all plans'),
                'method' => 'GET',
                'route' => route('api.plan.get'),
                'description' => e('Get a list of all plans on the platform.'),
                'parameters' => null,
                'code' => null,
                'response' => [
                    "error" => 0,
                    "data" => [
                        [
                            "id" => 2,
                            "name" => "Business",
                            "free" => false,
                            "prices" => [
                                'monthly' => 9.99,
                                'yearly' => 99.99,
                                'lifetime' => 999.99
                            ],
                            'limits' => [
                                'links' => 100,
                                'clicks' => 100000,
                                'retention' => 60,
                                'custom' => [
                                    'enabled' => '0',
                                ],
                                'team' => [
                                    'enabled' => '0',
                                    'count' => '0',
                                ],
                                'splash' => [
                                    'enabled' => '1',
                                    'count' => '5',
                                ],
                                'overlay' => [
                                    'enabled' => '1',
                                    'count' => '10',
                                ],
                                'pixels' => [
                                    'enabled' => '1',
                                    'count' => '10',
                                ],
                                'domain' => [
                                    'enabled' => '1',
                                    'count' => '1',
                                ],
                                'multiple' => [
                                    'enabled' => '0',
                                ],
                                'alias' => [
                                    'enabled' => '1',
                                ],
                                'device' => [
                                    'enabled' => '0',
                                ],
                                'geo' => [
                                    'enabled' => '0',
                                ],
                                'bundle' => [
                                    'enabled' => '0',
                                ],
                                'parameters' => [
                                    'enabled' => '0',
                                ],
                                'export' => [
                                    'enabled' => '0',
                                ],
                                'api' => [
                                    'enabled' => '0',
                                ]
                            ]
                        ],                        
                        [
                            "id" => 1,
                            "name" => "Starter",
                            "free" => true,
                            "prices" => null,
                            'limits' => [
                                'links' => 10,
                                'clicks' => 1000,
                                'retention' => 7,
                                'custom' => [
                                    'enabled' => '0',
                                ],
                                'team' => [
                                    'enabled' => '0',
                                    'count' => '0',
                                ],
                                'splash' => [
                                    'enabled' => '0',
                                    'count' => '0',
                                ],
                                'overlay' => [
                                    'enabled' => '0',
                                    'count' => '10',
                                ],
                                'pixels' => [
                                    'enabled' => '0',
                                    'count' => '10',
                                ],
                                'domain' => [
                                    'enabled' => '0',
                                    'count' => '0',
                                ],
                                'multiple' => [
                                    'enabled' => '0',
                                ],
                                'alias' => [
                                    'enabled' => '0',
                                ],
                                'device' => [
                                    'enabled' => '0',
                                ],
                                'geo' => [
                                    'enabled' => '0',
                                ],
                                'bundle' => [
                                    'enabled' => '0',
                                ],
                                'parameters' => [
                                    'enabled' => '0',
                                ],
                                'export' => [
                                    'enabled' => '0',
                                ],
                                'api' => [
                                    'enabled' => '0',
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            [
                'title' => e('Subscribe a user to a plan'),
                'method' => 'PUT',
                'route' => route('api.plan.subscribe', [':planid', ':userid']),
                'description' => e('To subscribe a user to plan, send a PUT request to this endpoint with the plan id and user id. The type of subscription and the expiration date will need to be specified. If the expiration date is not specified, the date will be adjusted according to the type.'),
                'parameters' => [
                    'type' => e('monthly | yearly | lifetime'),
                    'expiration' => e('(optional) Expiration date of the plan e.g. ').\Core\Helper::dtime('+1 month'),
                ],
                'code' => [
                    'type' => 'monthly',
                    'expiration' => \Core\Helper::dtime('+1 month'),
                ],
                'response' => [
                    'error' => 0,
                    'message' => 'User has been subscribed to this plan.',
                ]
            ]
        ]
    ], 
    'users' => [
        'admin' => true,
        'title' => e('Users'),
        'description' => '<span class="alert alert-warning text-dark">'.e('This endpoint is only accessible by users with admin privileges.').'</span>',
        'endpoints' => [
            [
                'title' => e('List all users'),
                'method' => 'GET',
                'route' => route('api.user.get', ['filter' => 'free']),
                'description' => e('Get a list of all users on the platform. Data can be filtered by sending a filter parameter in the url.'),
                'parameters' => [
                    'filter' => e('admin | free | pro'),
                    'email' => e('Search a user by email')
                ],
                'code' => null,
                'response' => [
                    "error" => 0,
                    "data" => [
                        [
                            "id" => 2,
                            "email" => "sample2@domain.com",
                            "username" => "sample2user",
                            "avatar" => "https:\/\/domain.com/content/avatar2.png",
                            "status" => "free",
                            "planid" => 1,
                            "expires" => null,
                            "registered" => "2020-11-10 18:01:43",
                            "apikey" => "ABC123DEF456"
                        ],                        
                        [
                            "id" => 1,
                            "email" => "sample@domain.com",
                            "username" => "sampleuser",
                            "avatar" => "https:\/\/domain.com/content/avatar.png",
                            "status" => "pro",
                            "planid" => 2,
                            "expires" => "2022-11-15 15:00:00",
                            "registered" => "2020-11-10 18:01:43",
                            "apikey" => "ABC123DEF456"
                        ]
                    ]
                ]
            ],
            [
                'title' => e('List a single user'),
                'method' => 'GET',
                'route' => route('api.user.single', [':id']),
                'description' => e('Get data for a single user.'),                
                'parameters' => null,
                'code' => null,
                'response' => [
                    "error" => 0,
                    "data" => [
                        "id" => 2,
                        "email" => "sample2@domain.com",
                        "username" => "sample2user",
                        "avatar" => "https:\/\/domain.com/content/avatar2.png",
                        "status" => "free",
                        "planid" => 1,
                        "expires" => null,
                        "registered" => "2020-11-10 18:01:43",
                        "apikey" => "ABC123DEF456"
                    ]
                ]
            ],
            [
                'title' => e('Create a user'),
                'method' => 'POST',
                'route' => route('api.user.create'),
                'description' => e('To create a user, use this endpoint and send the following information as JSON.'),
                'parameters' => [
                    "username" => e("(required) User's username. Needs to be valid."),
                    "email" => e("(required) User's email. Needs to be valid."),
                    "password" => e("(required) User's password. Minimum 5 characters."),
                    "planid" => e("(optional) Premium plan. This can be found in the admin panel."),
                    "expiration" => e("(optional) Membership expiration example 2020-12-26 12:00:00"),
                ],
                'code' => [
                    'username' => 'user',
                    'password' => '1234567891011',
                    'email' => 'demo@yourwebsite.com',
                    'planid' => 1,
                    'expiration' => '2020-11-20 11:00:00',
                  ],
                'response' => [
                    'error' => 0,
                    'message' => 'User has been registered.',
                    'data' => [
                      'id' => 3,
                      'email' => 'demo@yourwebsite.com',
                      'username' => 'user',
                    ],
                  ]
            ],
            [
                'title' => e('Delete a user'),
                'method' => 'DELETE',
                'route' => route('api.user.delete', [':id']),
                'description' => e('To delete a user, use this endpoint.'),
                'parameters' => null,
                'code' => null,
                'response' => [
                    'error' => 0,
                    'message' => 'User has been deleted.'
                ]
            ],
            [
                'title' => e('Login a user'),
                'method' => 'GET',
                'route' => route('api.user.login', [':id']),
                'description' => e('This endpoint will generate a unique link that will allow the user to automatically login to the platform. SSO login urls are valid for 1 hour and they can be used a single time.'),
                'parameters' => null,
                'code' => null,
                'response' => [
                    'error' => 0,
                    'url' => route('login.sso', [strtolower(\Core\Helper::rand(32))])
                ]
            ]
        ]
    ],           
];