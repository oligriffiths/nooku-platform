 <?php
/**
 * @version		$Id: weblink.php 3314 2012-02-10 02:14:52Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Contacts
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Mail Controller
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Contacts
 */
class ComContactsControllerMail extends ComDefaultControllerResource
{ 
    protected function _actionAdd(KCommandContext $context)
	{
	    $name          = $context->data->name;
	    $email         = $context->data->email;
	    $body          = $context->data->text;
	    $subject       = $context->data->subject;
	    $emailcopy     = $context->data->email_copy;
	
	    $mainframe = JFactory::getApplication();
	    
	    $SiteName = $mainframe->getCfg('sitename');
	    $default  = JText::sprintf( 'MAILENQUIRY', $SiteName );
	
	    if($emailto == '' && $userid != 0)
	    {
	        $contact_user = JUser::getInstance($userid);
	        $emailto = $contact_user->get('email');
	    }
	
	    jimport('joomla.mail.helper');
	    if (!$email || !$body || (JMailHelper::isEmailAddress($email) == false))
	    {
	        $mainframe->enqueueMessage(JText::_('CONTACT_FORM_NC'));
	        $this->display();
	        return false;
	    }
	
	    $contact = $this->getService('com://site/contacts.model.contacts')->set('id', $id)->getItem();
	
	    $params = $mainframe->getParams('com_contact');
	    
	    $MailFrom = $mainframe->getCfg('mailfrom');
	    $FromName = $mainframe->getCfg('fromname');
	
	    // Prepare email body
	    $prefix = JText::sprintf('ENQUIRY_TEXT', JURI::base());
	    $body   = $prefix."\n".$name.' <'.$email.'>'."\r\n\r\n".stripslashes($body);
	
	    $mail = JFactory::getMailer();
	    
	    $mail->addRecipient( $emailto );
	    $mail->setSender( array( $email, $name ) );
	    $mail->setSubject( $FromName.': '.$subject );
	    $mail->setBody( $body );
	
	    $sent = $mail->Send();
	
		/*
	     * Copy administrator.
	     */
	     if ( $emailcopy && $showemailcopy )
	     {
	         $copyText     = JText::sprintf('Copy of:', $name, $SiteName);
	         $copyText    .= "\r\n\r\n".$body;
	         $copySubject  = JText::_('Copy of:')." ".$subject;
	
	         $mail = JFactory::getMailer();
	
	         $mail->addRecipient( $email );
	         $mail->setSender( array( $MailFrom, $FromName ) );
	         $mail->setSubject( $copySubject );
	         $mail->setBody( $copyText );
	
	         $sent = $mail->Send();
	    }
	
	    $msg  = JText::_( 'Thank you for your e-mail');
	    $link = JRoute::_('index.php?option=com_contact&view=contact&id='.$id, false);
	    
	    $this->setRedirect($link, $msg);
	}
	
	function _validateInput(KCommandContext $context)
	{
	    $mainframe = KFactory::get('lib.joomla.application');
	   
	    // Get params and component configurations
	    $params = $mainframe->getParams('com_contact');
	
	    // Test to ensure that only one email address is entered
	    $check = explode( '@', $context->data->email );
	    if ( strpos( $context->data->email, ';' ) || strpos( $context->data->email, ',' ) || strpos( $context->data->email, ' ' ) || count( $check ) > 2 ) {
	        $mainframe->enqueueMessage('You cannot enter more than one email address.', 'error');
	        return false;
	    }
	
	    return true;
	}
}