<?php
/**
 * Business Enterprise Employee Repository (B.E.E.R)
 * Developed for Brian Teeman's Developer Showdown, using Nooku Framework
 * @version		$Id$
 * @package		Beer
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class BeerHelperIso extends KTemplateHelperSelect
{
	
	public static function country($name='country', $selected = '')
 	{
 		$countries = array();

 		$countries[] = KTemplate::loadHelper('select.option',  '', '- '. JText::_( 'Select a Country' ) .' -' );
		$countries[] = KTemplate::loadHelper('select.option',  'AF', 'Afghanistan' );
		$countries[] = KTemplate::loadHelper('select.option',  'AX', 'Aland Islands' );
		$countries[] = KTemplate::loadHelper('select.option',  'AL', 'Albania' );
		$countries[] = KTemplate::loadHelper('select.option',  'DZ', 'Algeria' );
		$countries[] = KTemplate::loadHelper('select.option',  'AS', 'American Samoa' );
		$countries[] = KTemplate::loadHelper('select.option',  'AD', 'Andorra' );
		$countries[] = KTemplate::loadHelper('select.option',  'AO', 'Angola' );
		$countries[] = KTemplate::loadHelper('select.option',  'AI', 'Anguilla' );
		$countries[] = KTemplate::loadHelper('select.option',  'AQ', 'Antarctica' );
		$countries[] = KTemplate::loadHelper('select.option',  'AG', 'Antigua and Barbuda' );
		$countries[] = KTemplate::loadHelper('select.option',  'AR', 'Argentina' );
		$countries[] = KTemplate::loadHelper('select.option',  'AM', 'Armenia' );
		$countries[] = KTemplate::loadHelper('select.option',  'AW', 'Aruba' );
		$countries[] = KTemplate::loadHelper('select.option',  'AU', 'Australia' );
		$countries[] = KTemplate::loadHelper('select.option',  'AT', 'Austria' );
		$countries[] = KTemplate::loadHelper('select.option',  'AZ', 'Azerbaidjan' );
		$countries[] = KTemplate::loadHelper('select.option',  'BS', 'Bahamas' );
		$countries[] = KTemplate::loadHelper('select.option',  'BH', 'Bahrain' );
		$countries[] = KTemplate::loadHelper('select.option',  'BD', 'Bangladesh' );
		$countries[] = KTemplate::loadHelper('select.option',  'BB', 'Barbados' );
		$countries[] = KTemplate::loadHelper('select.option',  'BY', 'Belarus' );
		$countries[] = KTemplate::loadHelper('select.option',  'BE', 'Belgium' );
		$countries[] = KTemplate::loadHelper('select.option',  'BZ', 'Belize' );
		$countries[] = KTemplate::loadHelper('select.option',  'BJ', 'Benin' );
		$countries[] = KTemplate::loadHelper('select.option',  'BM', 'Bermuda' );
		$countries[] = KTemplate::loadHelper('select.option',  'BT', 'Bhutan' );
		$countries[] = KTemplate::loadHelper('select.option',  'BO', 'Bolivia' );
		$countries[] = KTemplate::loadHelper('select.option',  'BA', 'Bosnia-Herzegovina' );
		$countries[] = KTemplate::loadHelper('select.option',  'BW', 'Botswana' );
		$countries[] = KTemplate::loadHelper('select.option',  'BV', 'Bouvet Island' );
		$countries[] = KTemplate::loadHelper('select.option',  'BR', 'Brazil' );
		$countries[] = KTemplate::loadHelper('select.option',  'IO', 'British Indian Ocean Territory' );
		$countries[] = KTemplate::loadHelper('select.option',  'BN', 'Brunei Darussalam' );
		$countries[] = KTemplate::loadHelper('select.option',  'BG', 'Bulgaria' );
		$countries[] = KTemplate::loadHelper('select.option',  'BF', 'Burkina Faso' );
		$countries[] = KTemplate::loadHelper('select.option',  'BI', 'Burundi' );
		$countries[] = KTemplate::loadHelper('select.option',  'KH', 'Cambodia' );
		$countries[] = KTemplate::loadHelper('select.option',  'CM', 'Cameroon' );
		$countries[] = KTemplate::loadHelper('select.option',  'CA', 'Canada' );
		$countries[] = KTemplate::loadHelper('select.option',  'CV', 'Cape Verde' );
		$countries[] = KTemplate::loadHelper('select.option',  'KY', 'Cayman Islands' );
		$countries[] = KTemplate::loadHelper('select.option',  'CF', 'Central African Republic' );
		$countries[] = KTemplate::loadHelper('select.option',  'TD', 'Chad' );
		$countries[] = KTemplate::loadHelper('select.option',  'CL', 'Chile' );
		$countries[] = KTemplate::loadHelper('select.option',  'CN', 'China' );
		$countries[] = KTemplate::loadHelper('select.option',  'CX', 'Christmas Island' );
		$countries[] = KTemplate::loadHelper('select.option',  'CC', 'Cocos (Keeling) Islands' );
		$countries[] = KTemplate::loadHelper('select.option',  'CO', 'Colombia' );
		$countries[] = KTemplate::loadHelper('select.option',  'KM', 'Comoros' );
		$countries[] = KTemplate::loadHelper('select.option',  'CG', 'Congo' );
		$countries[] = KTemplate::loadHelper('select.option',  'CK', 'Cook Islands' );
		$countries[] = KTemplate::loadHelper('select.option',  'CR', 'Costa Rica' );
		$countries[] = KTemplate::loadHelper('select.option',  'CI', 'C�te d\'Ivoire' );
		$countries[] = KTemplate::loadHelper('select.option',  'HR', 'Croatia' );
		$countries[] = KTemplate::loadHelper('select.option',  'CU', 'Cuba' );
		$countries[] = KTemplate::loadHelper('select.option',  'CY', 'Cyprus' );
		$countries[] = KTemplate::loadHelper('select.option',  'CZ', 'Czech Republic' );
		$countries[] = KTemplate::loadHelper('select.option',  'DK', 'Denmark' );
		$countries[] = KTemplate::loadHelper('select.option',  'DJ', 'Djibouti' );
		$countries[] = KTemplate::loadHelper('select.option',  'DM', 'Dominica' );
		$countries[] = KTemplate::loadHelper('select.option',  'DO', 'Dominican Republic' );
		$countries[] = KTemplate::loadHelper('select.option',  'EC', 'Ecuador' );
		$countries[] = KTemplate::loadHelper('select.option',  'EG', 'Egypt' );
		$countries[] = KTemplate::loadHelper('select.option',  'SV', 'El Salvador' );
		$countries[] = KTemplate::loadHelper('select.option',  'GQ', 'Equatorial Guinea' );
		$countries[] = KTemplate::loadHelper('select.option',  'ER', 'Eritrea' );
		$countries[] = KTemplate::loadHelper('select.option',  'EE', 'Estonia' );
		$countries[] = KTemplate::loadHelper('select.option',  'ET', 'Ethiopia' );
		$countries[] = KTemplate::loadHelper('select.option',  'FK', 'Falkland Islands' );
		$countries[] = KTemplate::loadHelper('select.option',  'FO', 'Faroe Islands' );
		$countries[] = KTemplate::loadHelper('select.option',  'FJ', 'Fiji' );
		$countries[] = KTemplate::loadHelper('select.option',  'FI', 'Finland' );
		$countries[] = KTemplate::loadHelper('select.option',  'CS', 'Former Czechoslovakia' );
		$countries[] = KTemplate::loadHelper('select.option',  'SU', 'Former USSR' );
		$countries[] = KTemplate::loadHelper('select.option',  'FR', 'France' );
		$countries[] = KTemplate::loadHelper('select.option',  'FX', 'France (European Territory)' );
		$countries[] = KTemplate::loadHelper('select.option',  'GF', 'French Guyana' );
		$countries[] = KTemplate::loadHelper('select.option',  'TF', 'French Southern Territories' );
		$countries[] = KTemplate::loadHelper('select.option',  'GA', 'Gabon' );
		$countries[] = KTemplate::loadHelper('select.option',  'GM', 'Gambia' );
		$countries[] = KTemplate::loadHelper('select.option',  'GE', 'Georgia' );
		$countries[] = KTemplate::loadHelper('select.option',  'DE', 'Germany' );
		$countries[] = KTemplate::loadHelper('select.option',  'GH', 'Ghana' );
		$countries[] = KTemplate::loadHelper('select.option',  'GI', 'Gibraltar' );
		$countries[] = KTemplate::loadHelper('select.option',  'GB', 'Great Britain' );
		$countries[] = KTemplate::loadHelper('select.option',  'GR', 'Greece' );
		$countries[] = KTemplate::loadHelper('select.option',  'GL', 'Greenland' );
		$countries[] = KTemplate::loadHelper('select.option',  'GD', 'Grenada' );
		$countries[] = KTemplate::loadHelper('select.option',  'GP', 'Guadeloupe (French)' );
		$countries[] = KTemplate::loadHelper('select.option',  'GU', 'Guam (USA)' );
		$countries[] = KTemplate::loadHelper('select.option',  'GT', 'Guatemala' );
		$countries[] = KTemplate::loadHelper('select.option',  'GN', 'Guinea' );
		$countries[] = KTemplate::loadHelper('select.option',  'GW', 'Guinea Bissau' );
		$countries[] = KTemplate::loadHelper('select.option',  'GY', 'Guyana' );
		$countries[] = KTemplate::loadHelper('select.option',  'HT', 'Haiti' );
		$countries[] = KTemplate::loadHelper('select.option',  'HM', 'Heard and McDonald Islands' );
		$countries[] = KTemplate::loadHelper('select.option',  'HN', 'Honduras' );
		$countries[] = KTemplate::loadHelper('select.option',  'HK', 'Hong Kong' );
		$countries[] = KTemplate::loadHelper('select.option',  'HU', 'Hungary' );
		$countries[] = KTemplate::loadHelper('select.option',  'IS', 'Iceland' );
		$countries[] = KTemplate::loadHelper('select.option',  'IN', 'India' );
		$countries[] = KTemplate::loadHelper('select.option',  'ID', 'Indonesia' );
		$countries[] = KTemplate::loadHelper('select.option',  'INT', 'International' );
		$countries[] = KTemplate::loadHelper('select.option',  'IR', 'Iran' );
		$countries[] = KTemplate::loadHelper('select.option',  'IQ', 'Iraq' );
		$countries[] = KTemplate::loadHelper('select.option',  'IE', 'Ireland' );
		$countries[] = KTemplate::loadHelper('select.option',  'IL', 'Israel' );
		$countries[] = KTemplate::loadHelper('select.option',  'IT', 'Italy' );
		$countries[] = KTemplate::loadHelper('select.option',  'CI', 'Ivory Coast (Cote D\'Ivoire)' );
		$countries[] = KTemplate::loadHelper('select.option',  'JM', 'Jamaica' );
		$countries[] = KTemplate::loadHelper('select.option',  'JP', 'Japan' );
		$countries[] = KTemplate::loadHelper('select.option',  'JO', 'Jordan' );
		$countries[] = KTemplate::loadHelper('select.option',  'KZ', 'Kazakhstan' );
		$countries[] = KTemplate::loadHelper('select.option',  'KE', 'Kenya' );
		$countries[] = KTemplate::loadHelper('select.option',  'KI', 'Kiribati' );
		$countries[] = KTemplate::loadHelper('select.option',  'KW', 'Kuwait' );
		$countries[] = KTemplate::loadHelper('select.option',  'KG', 'Kyrgyzstan' );
		$countries[] = KTemplate::loadHelper('select.option',  'LA', 'Laos' );
		$countries[] = KTemplate::loadHelper('select.option',  'LV', 'Latvia' );
		$countries[] = KTemplate::loadHelper('select.option',  'LB', 'Lebanon' );
		$countries[] = KTemplate::loadHelper('select.option',  'LS', 'Lesotho' );
		$countries[] = KTemplate::loadHelper('select.option',  'LR', 'Liberia' );
		$countries[] = KTemplate::loadHelper('select.option',  'LY', 'Libya' );
		$countries[] = KTemplate::loadHelper('select.option',  'LI', 'Liechtenstein' );
		$countries[] = KTemplate::loadHelper('select.option',  'LT', 'Lithuania' );
		$countries[] = KTemplate::loadHelper('select.option',  'LU', 'Luxembourg' );
		$countries[] = KTemplate::loadHelper('select.option',  'MO', 'Macau' );
		$countries[] = KTemplate::loadHelper('select.option',  'MK', 'Macedonia' );
		$countries[] = KTemplate::loadHelper('select.option',  'MG', 'Madagascar' );
		$countries[] = KTemplate::loadHelper('select.option',  'MW', 'Malawi' );
		$countries[] = KTemplate::loadHelper('select.option',  'MY', 'Malaysia' );
		$countries[] = KTemplate::loadHelper('select.option',  'MV', 'Maldives' );
		$countries[] = KTemplate::loadHelper('select.option',  'ML', 'Mali' );
		$countries[] = KTemplate::loadHelper('select.option',  'MT', 'Malta' );
		$countries[] = KTemplate::loadHelper('select.option',  'MH', 'Marshall Islands' );
		$countries[] = KTemplate::loadHelper('select.option',  'MQ', 'Martinique (French)' );
		$countries[] = KTemplate::loadHelper('select.option',  'MR', 'Mauritania' );
		$countries[] = KTemplate::loadHelper('select.option',  'MU', 'Mauritius' );
		$countries[] = KTemplate::loadHelper('select.option',  'YT', 'Mayotte' );
		$countries[] = KTemplate::loadHelper('select.option',  'MX', 'Mexico' );
		$countries[] = KTemplate::loadHelper('select.option',  'FM', 'Micronesia' );
		$countries[] = KTemplate::loadHelper('select.option',  'MD', 'Moldavia' );
		$countries[] = KTemplate::loadHelper('select.option',  'MC', 'Monaco' );
		$countries[] = KTemplate::loadHelper('select.option',  'MN', 'Mongolia' );
		$countries[] = KTemplate::loadHelper('select.option',  'MS', 'Montserrat' );
		$countries[] = KTemplate::loadHelper('select.option',  'MA', 'Morocco' );
		$countries[] = KTemplate::loadHelper('select.option',  'MZ', 'Mozambique' );
		$countries[] = KTemplate::loadHelper('select.option',  'MM', 'Myanmar' );
		$countries[] = KTemplate::loadHelper('select.option',  'NA', 'Namibia' );
		$countries[] = KTemplate::loadHelper('select.option',  'NR', 'Nauru' );
		$countries[] = KTemplate::loadHelper('select.option',  'NP', 'Nepal' );
		$countries[] = KTemplate::loadHelper('select.option',  'NL', 'Netherlands' );
		$countries[] = KTemplate::loadHelper('select.option',  'AN', 'Netherlands Antilles' );
		$countries[] = KTemplate::loadHelper('select.option',  'NT', 'Neutral Zone' );
		$countries[] = KTemplate::loadHelper('select.option',  'NC', 'New Caledonia (French)' );
		$countries[] = KTemplate::loadHelper('select.option',  'NZ', 'New Zealand' );
		$countries[] = KTemplate::loadHelper('select.option',  'NI', 'Nicaragua' );
		$countries[] = KTemplate::loadHelper('select.option',  'NE', 'Niger' );
		$countries[] = KTemplate::loadHelper('select.option',  'NG', 'Nigeria' );
		$countries[] = KTemplate::loadHelper('select.option',  'NU', 'Niue' );
		$countries[] = KTemplate::loadHelper('select.option',  'NF', 'Norfolk Island' );
		$countries[] = KTemplate::loadHelper('select.option',  'KP', 'North Korea' );
		$countries[] = KTemplate::loadHelper('select.option',  'MP', 'Northern Mariana Islands' );
		$countries[] = KTemplate::loadHelper('select.option',  'NO', 'Norway' );
		$countries[] = KTemplate::loadHelper('select.option',  'OM', 'Oman' );
		$countries[] = KTemplate::loadHelper('select.option',  'PK', 'Pakistan' );
		$countries[] = KTemplate::loadHelper('select.option',  'PW', 'Palau' );
		$countries[] = KTemplate::loadHelper('select.option',  'PA', 'Panama' );
		$countries[] = KTemplate::loadHelper('select.option',  'PG', 'Papua New Guinea' );
		$countries[] = KTemplate::loadHelper('select.option',  'PY', 'Paraguay' );
		$countries[] = KTemplate::loadHelper('select.option',  'PE', 'Peru' );
		$countries[] = KTemplate::loadHelper('select.option',  'PH', 'Philippines' );
		$countries[] = KTemplate::loadHelper('select.option',  'PN', 'Pitcairn Island' );
		$countries[] = KTemplate::loadHelper('select.option',  'PL', 'Poland' );
		$countries[] = KTemplate::loadHelper('select.option',  'PF', 'Polynesia (French)' );
		$countries[] = KTemplate::loadHelper('select.option',  'PT', 'Portugal' );
		$countries[] = KTemplate::loadHelper('select.option',  'PR', 'Puerto Rico' );
		$countries[] = KTemplate::loadHelper('select.option',  'QA', 'Qatar' );
		$countries[] = KTemplate::loadHelper('select.option',  'RE', 'Reunion (French)' );
		$countries[] = KTemplate::loadHelper('select.option',  'RO', 'Romania' );
		$countries[] = KTemplate::loadHelper('select.option',  'RU', 'Russian Federation' );
		$countries[] = KTemplate::loadHelper('select.option',  'RW', 'Rwanda' );
		$countries[] = KTemplate::loadHelper('select.option',  'GS', 'S. Georgia & S. Sandwich Isls.' );
		$countries[] = KTemplate::loadHelper('select.option',  'SH', 'Saint Helena' );
		$countries[] = KTemplate::loadHelper('select.option',  'KN', 'Saint Kitts & Nevis Anguilla' );
		$countries[] = KTemplate::loadHelper('select.option',  'LC', 'Saint Lucia' );
		$countries[] = KTemplate::loadHelper('select.option',  'PM', 'Saint Pierre and Miquelon' );
		$countries[] = KTemplate::loadHelper('select.option',  'ST', 'Saint Tome (Sao Tome) and Principe' );
		$countries[] = KTemplate::loadHelper('select.option',  'VC', 'Saint Vincent & Grenadines' );
		$countries[] = KTemplate::loadHelper('select.option',  'WS', 'Samoa' );
		$countries[] = KTemplate::loadHelper('select.option',  'SM', 'San Marino' );
		$countries[] = KTemplate::loadHelper('select.option',  'SA', 'Saudi Arabia' );
		$countries[] = KTemplate::loadHelper('select.option',  'SN', 'Senegal' );
		$countries[] = KTemplate::loadHelper('select.option',  'SC', 'Seychelles' );
		$countries[] = KTemplate::loadHelper('select.option',  'SL', 'Sierra Leone' );
		$countries[] = KTemplate::loadHelper('select.option',  'SG', 'Singapore' );
		$countries[] = KTemplate::loadHelper('select.option',  'SK', 'Slovak Republic' );
		$countries[] = KTemplate::loadHelper('select.option',  'SI', 'Slovenia' );
		$countries[] = KTemplate::loadHelper('select.option',  'SB', 'Solomon Islands' );
		$countries[] = KTemplate::loadHelper('select.option',  'SO', 'Somalia' );
		$countries[] = KTemplate::loadHelper('select.option',  'ZA', 'South Africa' );
		$countries[] = KTemplate::loadHelper('select.option',  'KR', 'South Korea' );
		$countries[] = KTemplate::loadHelper('select.option',  'ES', 'Spain' );
		$countries[] = KTemplate::loadHelper('select.option',  'LK', 'Sri Lanka' );
		$countries[] = KTemplate::loadHelper('select.option',  'SD', 'Sudan' );
		$countries[] = KTemplate::loadHelper('select.option',  'SR', 'Suriname' );
		$countries[] = KTemplate::loadHelper('select.option',  'SJ', 'Svalbard and Jan Mayen Islands' );
		$countries[] = KTemplate::loadHelper('select.option',  'SZ', 'Swaziland' );
		$countries[] = KTemplate::loadHelper('select.option',  'SE', 'Sweden' );
		$countries[] = KTemplate::loadHelper('select.option',  'CH', 'Switzerland' );
		$countries[] = KTemplate::loadHelper('select.option',  'SY', 'Syria' );
		$countries[] = KTemplate::loadHelper('select.option',  'TJ', 'Tadjikistan' );
		$countries[] = KTemplate::loadHelper('select.option',  'TW', 'Taiwan' );
		$countries[] = KTemplate::loadHelper('select.option',  'TZ', 'Tanzania' );
		$countries[] = KTemplate::loadHelper('select.option',  'TH', 'Thailand' );
		$countries[] = KTemplate::loadHelper('select.option',  'TL', 'Timor-Leste' );
		$countries[] = KTemplate::loadHelper('select.option',  'TG', 'Togo' );
		$countries[] = KTemplate::loadHelper('select.option',  'TK', 'Tokelau' );
		$countries[] = KTemplate::loadHelper('select.option',  'TO', 'Tonga' );
		$countries[] = KTemplate::loadHelper('select.option',  'TT', 'Trinidad and Tobago' );
		$countries[] = KTemplate::loadHelper('select.option',  'TN', 'Tunisia' );
		$countries[] = KTemplate::loadHelper('select.option',  'TR', 'Turkey' );
		$countries[] = KTemplate::loadHelper('select.option',  'TM', 'Turkmenistan' );
		$countries[] = KTemplate::loadHelper('select.option',  'TC', 'Turks and Caicos Islands' );
		$countries[] = KTemplate::loadHelper('select.option',  'TV', 'Tuvalu' );
		$countries[] = KTemplate::loadHelper('select.option',  'UG', 'Uganda' );
		$countries[] = KTemplate::loadHelper('select.option',  'UA', 'Ukraine' );
		$countries[] = KTemplate::loadHelper('select.option',  'AE', 'United Arab Emirates' );
		$countries[] = KTemplate::loadHelper('select.option',  'GB', 'United Kingdom' );
		$countries[] = KTemplate::loadHelper('select.option',  'US', 'United States' );
		$countries[] = KTemplate::loadHelper('select.option',  'UY', 'Uruguay' );
		$countries[] = KTemplate::loadHelper('select.option',  'MIL', 'USA Military' );
		$countries[] = KTemplate::loadHelper('select.option',  'UM', 'USA Minor Outlying Islands' );
		$countries[] = KTemplate::loadHelper('select.option',  'UZ', 'Uzbekistan' );
		$countries[] = KTemplate::loadHelper('select.option',  'VU', 'Vanuatu' );
		$countries[] = KTemplate::loadHelper('select.option',  'VA', 'Vatican City State' );
		$countries[] = KTemplate::loadHelper('select.option',  'VE', 'Venezuela' );
		$countries[] = KTemplate::loadHelper('select.option',  'VN', 'Vietnam' );
		$countries[] = KTemplate::loadHelper('select.option',  'VG', 'Virgin Islands (British)' );
		$countries[] = KTemplate::loadHelper('select.option',  'VI', 'Virgin Islands (USA)' );
		$countries[] = KTemplate::loadHelper('select.option',  'WF', 'Wallis and Futuna Islands' );
		$countries[] = KTemplate::loadHelper('select.option',  'EH', 'Western Sahara' );
		$countries[] = KTemplate::loadHelper('select.option',  'YE', 'Yemen' );
		$countries[] = KTemplate::loadHelper('select.option',  'ZR', 'Zaire' );
		$countries[] = KTemplate::loadHelper('select.option',  'ZM', 'Zambia' );
		$countries[] = KTemplate::loadHelper('select.option',  'ZW', 'Zimbabwe' );

 		return self::genericlist($countries, $name, 'class="inputbox" size="1" ', 'value', 'text', $selected );
 	}  
 	
	public static function us( $name = '', $selected = '' )
 	{
 		$states = array();
 		$states[] = KTemplate::loadHelper('select.option',  '', '- '. JText::_( 'Select a State' ) .' -' );
 		$states[] = KTemplate::loadHelper('select.option',  'AL', 'Alabama' );
		$states[] = KTemplate::loadHelper('select.option',  'AK', 'Alaska' );
		$states[] = KTemplate::loadHelper('select.option',  'AZ', 'Arizona' );
		$states[] = KTemplate::loadHelper('select.option',  'AR', 'Arkansas' );
		$states[] = KTemplate::loadHelper('select.option',  'CA', 'California' );
		$states[] = KTemplate::loadHelper('select.option',  'CO', 'Colorado' );
		$states[] = KTemplate::loadHelper('select.option',  'CT', 'Connecticut' );
		$states[] = KTemplate::loadHelper('select.option',  'DE', 'Delaware' );
		$states[] = KTemplate::loadHelper('select.option',  'DC', 'District Of Columbia' );
		$states[] = KTemplate::loadHelper('select.option',  'FL', 'Florida' );
		$states[] = KTemplate::loadHelper('select.option',  'GA', 'Georgia' );
		$states[] = KTemplate::loadHelper('select.option',  'HI', 'Hawaii' );
		$states[] = KTemplate::loadHelper('select.option',  'ID', 'Idaho' );
		$states[] = KTemplate::loadHelper('select.option',  'IL', 'Illinois' );
		$states[] = KTemplate::loadHelper('select.option',  'IN', 'Indiana' );
		$states[] = KTemplate::loadHelper('select.option',  'IA', 'Iowa' );
		$states[] = KTemplate::loadHelper('select.option',  'KS', 'Kansas' );
		$states[] = KTemplate::loadHelper('select.option',  'KY', 'Kentucky' );
		$states[] = KTemplate::loadHelper('select.option',  'LA', 'Louisiana' );
		$states[] = KTemplate::loadHelper('select.option',  'ME', 'Maine' );
		$states[] = KTemplate::loadHelper('select.option',  'MD', 'Maryland' );
		$states[] = KTemplate::loadHelper('select.option',  'MA', 'Massachusetts' );
		$states[] = KTemplate::loadHelper('select.option',  'MI', 'Michigan' );
		$states[] = KTemplate::loadHelper('select.option',  'MN', 'Minnesota' );
		$states[] = KTemplate::loadHelper('select.option',  'MS', 'Mississippi' );
		$states[] = KTemplate::loadHelper('select.option',  'MO', 'Missouri' );
		$states[] = KTemplate::loadHelper('select.option',  'MT', 'Montana' );
		$states[] = KTemplate::loadHelper('select.option',  'NE', 'Nebraska' );
		$states[] = KTemplate::loadHelper('select.option',  'NV', 'Nevada' );
		$states[] = KTemplate::loadHelper('select.option',  'NH', 'New Hampshire' );
		$states[] = KTemplate::loadHelper('select.option',  'NJ', 'New Jersey' );
		$states[] = KTemplate::loadHelper('select.option',  'NM', 'New Mexico' );
		$states[] = KTemplate::loadHelper('select.option',  'NY', 'New York' );
		$states[] = KTemplate::loadHelper('select.option',  'NC', 'North Carolina' );
		$states[] = KTemplate::loadHelper('select.option',  'ND', 'North Dakota' );
		$states[] = KTemplate::loadHelper('select.option',  'OH', 'Ohio' );
		$states[] = KTemplate::loadHelper('select.option',  'OK', 'Oklahoma' );
		$states[] = KTemplate::loadHelper('select.option',  'OR', 'Oregon' );
		$states[] = KTemplate::loadHelper('select.option',  'PA', 'Pennsylvania' );
		$states[] = KTemplate::loadHelper('select.option',  'RI', 'Rhode Island' );
		$states[] = KTemplate::loadHelper('select.option',  'SC', 'South Carolina' );
		$states[] = KTemplate::loadHelper('select.option',  'SD', 'South Dakota' );
		$states[] = KTemplate::loadHelper('select.option',  'TN', 'Tennessee' );
		$states[] = KTemplate::loadHelper('select.option',  'TX', 'Texas' );
		$states[] = KTemplate::loadHelper('select.option',  'UT', 'Utah' );
		$states[] = KTemplate::loadHelper('select.option',  'VT', 'Vermont' );
		$states[] = KTemplate::loadHelper('select.option',  'VA', 'Virginia' );
		$states[] = KTemplate::loadHelper('select.option',  'WA', 'Washington' );
		$states[] = KTemplate::loadHelper('select.option',  'WV', 'West Virginia' );
		$states[] = KTemplate::loadHelper('select.option',  'WI', 'Wisconsin' );
		$states[] = KTemplate::loadHelper('select.option',  'WY', 'Wyoming' );

  		return self::genericlist($states, $name, 'class="inputbox" size="1" ', 'value', 'text', $selected );
 	} 

	public static function au( $name = '', $selected = '' )
 	{
 		$states = array();
 		$states[] = KTemplate::loadHelper('select.option',  '', '- '. JText::_( 'Select a State' ) .' -' );
 		$states[] = KTemplate::loadHelper('select.option',  'ATC', 'Australian Capital Territory' );
		$states[] = KTemplate::loadHelper('select.option',  'NSW', 'New South Wales' );
		$states[] = KTemplate::loadHelper('select.option',  'NT', 'Northern Territory' );
		$states[] = KTemplate::loadHelper('select.option',  'QLD', 'Queensland' );
		$states[] = KTemplate::loadHelper('select.option',  'SA', 'South Australia' );
		$states[] = KTemplate::loadHelper('select.option',  'TAS', 'Tasmania' );
		$states[] = KTemplate::loadHelper('select.option',  'VIC', 'Victoria' );
		$states[] = KTemplate::loadHelper('select.option',  'WA', 'Western Australia' );

 		return self::genericlist($states, $name, 'class="inputbox" size="1" ', 'value', 'text', $selected );
	}  

	public static function ca( $name = '', $selected = '' )
 	{
 		$states = array();
 		$states[] = KTemplate::loadHelper('select.option',  '', '- '. JText::_( 'Select a Provence' ) .' -' );
 		$states[] = KTemplate::loadHelper('select.option',  'AB', 'Alberta' );
		$states[] = KTemplate::loadHelper('select.option',  'BC', 'British Columbia' );
		$states[] = KTemplate::loadHelper('select.option',  'MB', 'Manitoba' );
		$states[] = KTemplate::loadHelper('select.option',  'NB', 'New Brunswick' );
		$states[] = KTemplate::loadHelper('select.option',  'NF', 'Newfoundland' );
		$states[] = KTemplate::loadHelper('select.option',  'NT', 'Northwest Territories' );
		$states[] = KTemplate::loadHelper('select.option',  'NS', 'Nova Scotia' );
		$states[] = KTemplate::loadHelper('select.option',  'NU', 'Nunavut' );
		$states[] = KTemplate::loadHelper('select.option',  'ON', 'Ontario' );
		$states[] = KTemplate::loadHelper('select.option',  'PE', 'Prince Edward Island' );
		$states[] = KTemplate::loadHelper('select.option',  'QC', 'Quebec' );
		$states[] = KTemplate::loadHelper('select.option',  'SK', 'Saskatchewan' );
		$states[] = KTemplate::loadHelper('select.option',  'YT', 'Yukon Territory' );

 		return self::genericlist($states, $name, 'class="inputbox" size="1" ', 'value', 'text', $selected );
 	}
 	
	public static function za( $name = '', $selected = '' )
 	{
 		$states = array();
 		$states[] = KTemplate::loadHelper('select.option',  '', '- '. JText::_( 'Select a Provence' ) .' -' );
 		$states[] = KTemplate::loadHelper('select.option',  'EC', 'Eastern Cape' );
		$states[] = KTemplate::loadHelper('select.option',  'FS', 'Free State' );
		$states[] = KTemplate::loadHelper('select.option',  'GP', 'Gauteng' );
		$states[] = KTemplate::loadHelper('select.option',  'ZN', 'KwaZulu-Natal' );
		$states[] = KTemplate::loadHelper('select.option',  'LP', 'Limpopo' );
		$states[] = KTemplate::loadHelper('select.option',  'MP', 'Mpumalanga' );
		$states[] = KTemplate::loadHelper('select.option',  'NC', 'Northern Cape' );
		$states[] = KTemplate::loadHelper('select.option',  'NW', 'North-West' );
		$states[] = KTemplate::loadHelper('select.option',  'WC', 'Western Cape' );

 		return self::genericlist($states, $name, 'class="inputbox" size="1" ', 'value', 'text', $selected );
 	}
 	
	public static function nz( $name = '', $selected = '' )
 	{
 		$states = array();
 		$states[] = KTemplate::loadHelper('select.option',  '', '- '. JText::_( 'Select a Provence' ) .' -' );
 		$states[] = KTemplate::loadHelper('select.option',  'AUK', 'Auckland' );
		$states[] = KTemplate::loadHelper('select.option',  'BOP', 'Bay Of Plenty' );
		$states[] = KTemplate::loadHelper('select.option',  'CAN', 'Canterbury' );
		$states[] = KTemplate::loadHelper('select.option',  'GIS', 'Gisborne' );
		$states[] = KTemplate::loadHelper('select.option',  'HKB', 'Hawkes Bay' );
		$states[] = KTemplate::loadHelper('select.option',  'MBH', 'Marlborough' );
		$states[] = KTemplate::loadHelper('select.option',  'MWT', 'Manawatu-Wanganui' );
		$states[] = KTemplate::loadHelper('select.option',  'NSN', 'Nelson' );
		$states[] = KTemplate::loadHelper('select.option',  'NTL', 'Northland' );
		$states[] = KTemplate::loadHelper('select.option',  'OTA', 'Otago' );
		$states[] = KTemplate::loadHelper('select.option',  'STL', 'Southland' );
		$states[] = KTemplate::loadHelper('select.option',  'TAS', 'Tasman' );
		$states[] = KTemplate::loadHelper('select.option',  'TKI', 'Taranaki' );
		$states[] = KTemplate::loadHelper('select.option',  'WGN', 'Wellington' );
		$states[] = KTemplate::loadHelper('select.option',  'WKO', 'Waikato' );
		$states[] = KTemplate::loadHelper('select.option',  'WTC', 'West Coast' );
		
 		return self::genericlist($states, $name, 'class="inputbox" size="1" ', 'value', 'text', $selected );
 	} 	
	
}