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

		$fromSG = new SendGrid\Email($from->name, $from->address);
		$content = new SendGrid\Content("text/html", $body);

		$apiKey = getenv('SENDGRID_API_KEY');
		$sg = new \SendGrid($apiKey);

		wfDebug("SendgridMailer", "Sending mail via Sendgrid\n" );

		foreach ( $to as $recip ) {
      try {
        $recipSG = new SendGrid\Email($recip->name, $recip->address);

        $mail = new SendGrid\Mail($fromSG, $subject, $recipSG, $content);
        $response = $sg->client->mail()->send()->post($mail);

        $responseCode = $response->statusCode();
        $responseHeaders = $response->headers();
        $responseBody = $response->body();

        wfDebugLog( 'SendgridMailer', "SG response: " . print_r(array("code" => $responseCode, "headers" => $responseHeaders, "body" => $responseBody ), true) );
			} catch ( Exception $e ) {
				wfDebugLog( 'SendgridMailer', "Sendgrid Mailer failed: $e" );
				return $e;
			}
		}

		// Alternate Mailer hooks should return false to skip regular false sending
		return false;
	}

}
