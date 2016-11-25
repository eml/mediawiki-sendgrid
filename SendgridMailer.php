<?php
/**
 * SendgridMailer Extension to handle email in MediaWiki
 *
 * Copyright (c) 2016, Eric Lambrecht <eric@mrtechlab.com>
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
 * @author Tony Thomas<01tonythomas@gmail.com>
 * @author Legoktm <legoktm@gmail.com>
 * @author Jeff Green <jgreen@wikimedia.org>
 * @license GPL-2.0
 * @ingroup Extensions
 */
$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'SendgridMailer',
	'author' => array(
		'Eric Lambrecht'
	),
	'url' => "https://www.mediawiki.org/wiki/Extension:SwiftMailer",
	'descriptionmsg' => 'sendgridmailer-desc',
	'version'  => '1.0',
	'license-name' => "GPL-2.0",
);

//Hooks files
$wgAutoloadClasses[ 'SendgridMailerHooks' ] =  __DIR__ . '/SendgridMailerHooks.php';

//Register Hooks
$wgHooks[ 'AlternateUserMailer' ][] = 'SendgridMailerHooks::onAlternateUserMailer';

/*Messages Files */
$wgMessagesDirs[ 'SendgridMailer' ] = __DIR__ . '/i18n';

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}
