<?php
/**
 * Private helpers function for checked, selected, and disabled.
 *
 * Compares the first two arguments and if identical marks as $type
 *
 * @since 2.8.0
 * @access private
 *
 * @param mixed  $helper  One of the values to compare
 * @param mixed  $current (true) The other value to compare if not just true
 * @param bool   $echo    Whether to echo or just return the string
 * @param string $type    The type of checked|selected|disabled we are doing
 * @return string html attribute or empty string
 */

function checked_selected_helper( $helper, $current, $echo, $type )
{
    if ( (string) $helper === (string) $current )
        $result = " $type='$type'";
    else $result = '';
    if ( $echo ) echo $result;
    return $result;
}

function selected( $selected, $current = true, $echo = true ) {
    return checked_selected_helper( $selected, $current, $echo, 'selected' );
}

function disabled( $disabled, $current = true, $echo = true ) {
    return checked_selected_helper( $disabled, $current, $echo, 'disabled' );
}

function checked( $checked, $current = true, $echo = true ) {
    return checked_selected_helper( $checked, $current, $echo, 'checked' );
}

function make_slug($urlStr){
    $parsed = parse_url($urlStr);
    if (empty($parsed['scheme'])) {
        $urlStr = 'http://' . ltrim($urlStr, '/');
    }
    return $urlStr;
} 

if (!function_exists('get_ini_bytes'))
{
    function get_ini_bytes($val){
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        switch($last) {
            // The 'G' modifier is available since PHP 5.1.0
            case 'g':
            $val = (int) $val * 1024;
            case 'm':
            $val = (int) $val * 1024;
            case 'k':
            $val = (int) $val * 1024;
        }
        return $val;
    }
}

if (!function_exists('formatBytes'))
{
    function formatBytes($bytes, $precision = 2) {
        if ($bytes > pow(1024,3)) return round($bytes / pow(1024,3), $precision)."gb";
        else if ($bytes > pow(1024,2)) return round($bytes / pow(1024,2), $precision)."mb";
        else if ($bytes > 1024) return round($bytes / 1024, $precision)."kb";
        else return ($bytes)."B";
    }
}

if (!function_exists('get_minutes'))
{
    function get_minutes($time) {
        $split_time = explode(':', $time);
        return ($split_time[0] * 60) + ($split_time[1]) + ($split_time[2] > 30 ? 1 : 0);
    }
}

function get_attachments_list()
{
    return $attachments = array(
        'pdf'  => 'pdf',
        'doc'  => 'word',
        'ppt'  => 'powerpoint',
        'pptx' => 'powerpoint',
        'docx' => 'word',
        'jpg'  => 'image',
        'jpeg' => 'image',
        'png'  => 'image',
        'bmp'  => 'image',
        'gif'  => 'image',
        'xlsx' => 'excel',
        'csv'  => 'excel',
        'xls'  => 'excel',
        'txt'  => 'text',
        'zip'  => 'zip',
        'rar'  => 'zip',
    );
}

function get_months_list()
{
    return $months = array(
        '1' => 'January',
        '2' => 'February',
        '3' => 'March',
        '4' => 'April',
        '5' => 'May',
        '6' => 'June',
        '7' => 'July',
        '8' => 'August',
        '9' => 'September',
        '10'=> 'October',
        '11'=> 'November',
        '12'=> 'December',
    );
}

function get_countries_list()
{
    return $countries = Array(
        'ABW'=>'Aruba',
        'AFG'=>'Afghanistan',
        'AGO'=>'Angola',
        'AIA'=>'Anguilla',
        'ALA'=>'Åland Islands',
        'ALB'=>'Albania',
        'AND'=>'Andorra',
        'ARE'=>'United Arab Emirates',
        'ARG'=>'Argentina',
        'ARM'=>'Armenia',
        'ASM'=>'American Samoa',
        'ATA'=>'Antarctica',
        'ATF'=>'French Southern Territories',
        'ATG'=>'Antigua and Barbuda',
        'AUS'=>'Australia',
        'AUT'=>'Austria',
        'AZE'=>'Azerbaijan',
        'BDI'=>'Burundi',
        'BEL'=>'Belgium',
        'BEN'=>'Benin',
        'BES'=>'Bonaire, Sint Eustatius and Saba',
        'BFA'=>'Burkina Faso',
        'BGD'=>'Bangladesh',
        'BGR'=>'Bulgaria',
        'BHR'=>'Bahrain',
        'BHS'=>'Bahamas',
        'BIH'=>'Bosnia and Herzegovina',
        'BLM'=>'Saint Barthélemy',
        'BLR'=>'Belarus',
        'BLZ'=>'Belize',
        'BMU'=>'Bermuda',
        'BOL'=>'Bolivia, Plurinational State of',
        'BRA'=>'Brazil',
        'BRB'=>'Barbados',
        'BRN'=>'Brunei Darussalam',
        'BTN'=>'Bhutan',
        'BVT'=>'Bouvet Island',
        'BWA'=>'Botswana',
        'CAF'=>'Central African Republic',
        'CAN'=>'Canada',
        'CCK'=>'Cocos (Keeling) Islands',
        'CHE'=>'Switzerland',
        'CHL'=>'Chile',
        'CHN'=>'China',
        'CIV'=>'Côte d\'Ivoire',
        'CMR'=>'Cameroon',
        'COD'=>'Congo, the Democratic Republic of the',
        'COG'=>'Congo',
        'COK'=>'Cook Islands',
        'COL'=>'Colombia',
        'COM'=>'Comoros',
        'CPV'=>'Cape Verde',
        'CRI'=>'Costa Rica',
        'CUB'=>'Cuba',
        'CUW'=>'Curaçao',
        'CXR'=>'Christmas Island',
        'CYM'=>'Cayman Islands',
        'CYP'=>'Cyprus',
        'CZE'=>'Czech Republic',
        'DEU'=>'Germany',
        'DJI'=>'Djibouti',
        'DMA'=>'Dominica',
        'DNK'=>'Denmark',
        'DOM'=>'Dominican Republic',
        'DZA'=>'Algeria',
        'ECU'=>'Ecuador',
        'EGY'=>'Egypt',
        'ERI'=>'Eritrea',
        'ESH'=>'Western Sahara',
        'ESP'=>'Spain',
        'EST'=>'Estonia',
        'ETH'=>'Ethiopia',
        'FIN'=>'Finland',
        'FJI'=>'Fiji',
        'FLK'=>'Falkland Islands (Malvinas)',
        'FRA'=>'France',
        'FRO'=>'Faroe Islands',
        'FSM'=>'Micronesia, Federated States of',
        'GAB'=>'Gabon',
        'GBR'=>'United Kingdom',
        'GEO'=>'Georgia',
        'GGY'=>'Guernsey',
        'GHA'=>'Ghana',
        'GIB'=>'Gibraltar',
        'GIN'=>'Guinea',
        'GLP'=>'Guadeloupe',
        'GMB'=>'Gambia',
        'GNB'=>'Guinea-Bissau',
        'GNQ'=>'Equatorial Guinea',
        'GRC'=>'Greece',
        'GRD'=>'Grenada',
        'GRL'=>'Greenland',
        'GTM'=>'Guatemala',
        'GUF'=>'French Guiana',
        'GUM'=>'Guam',
        'GUY'=>'Guyana',
        'HKG'=>'Hong Kong',
        'HMD'=>'Heard Island and McDonald Islands',
        'HND'=>'Honduras',
        'HRV'=>'Croatia',
        'HTI'=>'Haiti',
        'HUN'=>'Hungary',
        'IDN'=>'Indonesia',
        'IMN'=>'Isle of Man',
        'IND'=>'India',
        'IOT'=>'British Indian Ocean Territory',
        'IRL'=>'Ireland',
        'IRN'=>'Iran, Islamic Republic of',
        'IRQ'=>'Iraq',
        'ISL'=>'Iceland',
        'ISR'=>'Israel',
        'ITA'=>'Italy',
        'JAM'=>'Jamaica',
        'JEY'=>'Jersey',
        'JOR'=>'Jordan',
        'JPN'=>'Japan',
        'KAZ'=>'Kazakhstan',
        'KEN'=>'Kenya',
        'KGZ'=>'Kyrgyzstan',
        'KHM'=>'Cambodia',
        'KIR'=>'Kiribati',
        'KNA'=>'Saint Kitts and Nevis',
        'KOR'=>'Korea, Republic of',
        'KWT'=>'Kuwait',
        'LAO'=>'Lao People\'s Democratic Republic',
        'LBN'=>'Lebanon',
        'LBR'=>'Liberia',
        'LBY'=>'Libya',
        'LCA'=>'Saint Lucia',
        'LIE'=>'Liechtenstein',
        'LKA'=>'Sri Lanka',
        'LSO'=>'Lesotho',
        'LTU'=>'Lithuania',
        'LUX'=>'Luxembourg',
        'LVA'=>'Latvia',
        'MAC'=>'Macao',
        'MAF'=>'Saint Martin (French part)',
        'MAR'=>'Morocco',
        'MCO'=>'Monaco',
        'MDA'=>'Moldova, Republic of',
        'MDG'=>'Madagascar',
        'MDV'=>'Maldives',
        'MEX'=>'Mexico',
        'MHL'=>'Marshall Islands',
        'MKD'=>'Macedonia, the former Yugoslav Republic of',
        'MLI'=>'Mali',
        'MLT'=>'Malta',
        'MMR'=>'Myanmar',
        'MNE'=>'Montenegro',
        'MNG'=>'Mongolia',
        'MNP'=>'Northern Mariana Islands',
        'MOZ'=>'Mozambique',
        'MRT'=>'Mauritania',
        'MSR'=>'Montserrat',
        'MTQ'=>'Martinique',
        'MUS'=>'Mauritius',
        'MWI'=>'Malawi',
        'MYS'=>'Malaysia',
        'MYT'=>'Mayotte',
        'NAM'=>'Namibia',
        'NCL'=>'New Caledonia',
        'NER'=>'Niger',
        'NFK'=>'Norfolk Island',
        'NGA'=>'Nigeria',
        'NIC'=>'Nicaragua',
        'NIU'=>'Niue',
        'NLD'=>'Netherlands',
        'NOR'=>'Norway',
        'NPL'=>'Nepal',
        'NRU'=>'Nauru',
        'NZL'=>'New Zealand',
        'OMN'=>'Oman',
        'PAK'=>'Pakistan',
        'PAN'=>'Panama',
        'PCN'=>'Pitcairn',
        'PER'=>'Peru',
        'PHL'=>'Philippines',
        'PLW'=>'Palau',
        'PNG'=>'Papua New Guinea',
        'POL'=>'Poland',
        'PRI'=>'Puerto Rico',
        'PRK'=>'Korea, Democratic People\'s Republic of',
        'PRT'=>'Portugal',
        'PRY'=>'Paraguay',
        'PSE'=>'Palestinian Territory, Occupied',
        'PYF'=>'French Polynesia',
        'QAT'=>'Qatar',
        'REU'=>'Réunion',
        'ROU'=>'Romania',
        'RUS'=>'Russian Federation',
        'RWA'=>'Rwanda',
        'SAU'=>'Saudi Arabia',
        'SDN'=>'Sudan',
        'SEN'=>'Senegal',
        'SGP'=>'Singapore',
        'SGS'=>'South Georgia and the South Sandwich Islands',
        'SHN'=>'Saint Helena, Ascension and Tristan da Cunha',
        'SJM'=>'Svalbard and Jan Mayen',
        'SLB'=>'Solomon Islands',
        'SLE'=>'Sierra Leone',
        'SLV'=>'El Salvador',
        'SMR'=>'San Marino',
        'SOM'=>'Somalia',
        'SPM'=>'Saint Pierre and Miquelon',
        'SRB'=>'Serbia',
        'SSD'=>'South Sudan',
        'STP'=>'Sao Tome and Principe',
        'SUR'=>'Suriname',
        'SVK'=>'Slovakia',
        'SVN'=>'Slovenia',
        'SWE'=>'Sweden',
        'SWZ'=>'Swaziland',
        'SXM'=>'Sint Maarten (Dutch part)',
        'SYC'=>'Seychelles',
        'SYR'=>'Syrian Arab Republic',
        'TCA'=>'Turks and Caicos Islands',
        'TCD'=>'Chad',
        'TGO'=>'Togo',
        'THA'=>'Thailand',
        'TJK'=>'Tajikistan',
        'TKL'=>'Tokelau',
        'TKM'=>'Turkmenistan',
        'TLS'=>'Timor-Leste',
        'TON'=>'Tonga',
        'TTO'=>'Trinidad and Tobago',
        'TUN'=>'Tunisia',
        'TUR'=>'Turkey',
        'TUV'=>'Tuvalu',
        'TWN'=>'Taiwan, Province of China',
        'TZA'=>'Tanzania, United Republic of',
        'UGA'=>'Uganda',
        'UKR'=>'Ukraine',
        'UMI'=>'United States Minor Outlying Islands',
        'URY'=>'Uruguay',
        'USA'=>'United States',
        'UZB'=>'Uzbekistan',
        'VAT'=>'Holy See (Vatican City State)',
        'VCT'=>'Saint Vincent and the Grenadines',
        'VEN'=>'Venezuela, Bolivarian Republic of',
        'VGB'=>'Virgin Islands, British',
        'VIR'=>'Virgin Islands, U.S.',
        'VNM'=>'Viet Nam',
        'VUT'=>'Vanuatu',
        'WLF'=>'Wallis and Futuna',
        'WSM'=>'Samoa',
        'YEM'=>'Yemen',
        'ZAF'=>'South Africa',
        'ZMB'=>'Zambia',
        'ZWE'=>'Zimbabwe'
    );
}

function date_format_php_list($index)
{
    $val = "-";
    switch($index) {
        case '0':
        $val = 'Y-m-d';     // 2017-10-30
        break;
        case '1':
        $val = 'F j, Y';    // October 30, 2017
        break;
        case '2':
        $val = 'm/d/Y';     // 10/30/2017
        break;
        case '3':
        $val = 'd/m/Y';     //  30/10/2017
        break;
        case '4':
        $val = 'd-m-Y';     // 30-10-2017
        break;
        case '5':
        $val = 'd-m-Y h:i A';   // 30-10-2017
        break;
    }
    return $val;
}

function date_format_js_list()
{
    return array(
        "default"       =>   "ddd mmm dd yyyy HH:MM:ss",
        "pakDate"       =>   "dd-mm-yyyy",
        "shortDate"     =>   "m/d/yy",
        "mediumDate"    =>   "mmm d, yyyy",
        "longDate"      =>   "mmmm d, yyyy",
        "fullDate"      =>   "dddd, mmmm d, yyyy",
        "shortTime"     =>   "h:MM TT",
        "mediumTime"    =>   "h:MM:ss TT",
        "longTime"      =>   "h:MM:ss TT Z",
        "isoDate"       =>   "yyyy-mm-dd",
        "isoTime"       =>   "HH:MM:ss",
        "isoDateTime"   =>   "yyyy-mm-dd'T'HH:MM:ss",
        "isoUtcDateTime"=>   "UTC:yyyy-mm-dd'T'HH:MM:ss'Z'"
    );
}