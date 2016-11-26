<?php
/**
 * Hooks for SendgridMailer extension
 *
 * Copyright (c) 2014, Tony Thomas <01tonythomas@gmail.com>
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @author Tony Thomas <01tonythomas@gmail.com>
 * @author Legoktm <legoktm@gmail.com>
 * @author Jeff Green <jgreen@wikimedia.org>
 * @license GPL-2.0
 * @ingroup Extensions
 */
class SendgridMailerHooks {
	/**
	 * Creates and sends a mail using Sendgrid
	 *
	 * @param array $headers
	 * @param MailAddress $to
	 * @param MailAddress $from
	 * @param string $subject
	 * @param string $body
	 * @return bool|Exception
	 */
	public static function onAlternateUserMailer( array $headers, array $to,
						      MailAddress $from, $subject, $body ) {

		//wfDebugLog( 'SendgridMailer', print_r(array($headers, $to, $from, $subject, $body), true) );

		// should be set in LocalSettings.php
		global $wgSendgridAPIKey;
		$sendgrid_apikey = $wgSendgridAPIKey;

                $url = 'https://api.sendgrid.com/';

		foreach ( $to as $recip ) {
wfDebugLog('SendgridMailer', "sending to " . $recip->address);
		$params = array(
		    'to'        => $recip->address,
		    'toname'    => $recip->name,
		    'from'      => $from->address,
		    'fromname'  => $from->name,
		    'subject'   => $subject,
		    'text'      => $body,
		  );

		$request =  $url.'api/mail.send.json';

		// Generate curl request
		$session = curl_init($request);
		// Tell PHP not to use SSLv3 (instead opting for TLS)
		curl_setopt($session, CURLOPT_SSLVERSION, 'CURL_SSLVERSION_TLSv1_2');
		curl_setopt($session, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $sendgrid_apikey));
		// Tell curl to use HTTP POST
		curl_setopt ($session, CURLOPT_POST, true);
		// Tell curl that this is the body of the POST
		curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
		// Tell curl not to return headers, but do return the response
		curl_setopt($session, CURLOPT_HEADER, false);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

		// obtain response
		$response = curl_exec($session);
		curl_close($session);

		// print everything out
		wfDebugLog('SendgridMailer', print_r($response, true));
              }

		// Alternate Mailer hooks should return false to skip regular false sending
		return false;
	}

}
