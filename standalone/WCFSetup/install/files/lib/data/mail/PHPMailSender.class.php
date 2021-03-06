<?php
// wcf imports
require_once(WCF_DIR.'lib/data/mail/MailSender.class.php');
require_once(WCF_DIR.'lib/data/mail/Mail.class.php');

/**
 * Sends a Mail with the php mail function.
 * 
 * @author	Michael Schaefer
 * @copyright	2001-2009 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf
 * @subpackage	data.mail
 * @category 	Community Framework
 */
class PHPMailSender extends MailSender {
	/**
	 * @see MailSender::sendMail()
	 */
	public function sendMail(Mail $mail) {
		if (USE_MBSTRING) {
			if (MAIL_USE_F_PARAM) return @mb_send_mail($mail->getToString(), $mail->getSubject(), $mail->getBody(), $mail->getHeader(), '-f'.MAIL_FROM_ADDRESS);
			else return @mb_send_mail($mail->getToString(), $mail->getSubject(), $mail->getBody(), $mail->getHeader());
		}
		else {
			if (MAIL_USE_F_PARAM) return @mail($mail->getToString(), $mail->getSubject(), $mail->getBody(), $mail->getHeader(), '-f'.MAIL_FROM_ADDRESS);
			else return @mail($mail->getToString(), $mail->getSubject(), $mail->getBody(), $mail->getHeader());
		}
	}
}
?>