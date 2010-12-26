<?php

// This page handles user activation and cancellations...

if ($slug == "activate" || $slug == "cancel")
{
	Log::add('Beginning activation/cancellation sequence...');
	Content::$forcedTitle = 'Email Account Activation';
	$activationError = Message::error('Sorry, but the incorrect parameters were supplied.<br />Please doublecheck the URI, and ensure it is the same as in the email you were sent.<br /><br />If you\'re having problems with account validation, please email admins@hrepo.com.');
	if (count($params) != 2)
	{
		Log::add('Parameter count was not 2. params: ' . count($params));
		Content::setContent($activationError);
	}
	else
	{
		// Okay, let's check to see if that's the right key...
		$username = $params[0];
		$validatekey = $params[1];
		$dbr = Database::select('users', array('uid'), array('validate_key = ? AND username = ? AND status = 0', $validatekey, $username));
		if ($dbr->rowCount() != 1)
		{
			Log::add('Database did not return a row');
			Content::setContent($activationError);
		}
		else
		{
			$uid = $dbr->fetchColumn();
			Log::add('Database returned a row. UID: ' . $uid);
			unset($dbr);
			if ($slug == 'activate')
			{
				// ugh, annoying database class.
				$dbh = Database::getHandle();
				$dbr = $dbh->prepare('UPDATE users SET validate_key = NULL, status = 1 WHERE uid = ?');
				if ($dbr->execute(array($uid)))
				{
					Log::add('Account activation successful.');
					Content::setContent(Message::success('Your account has been activated successfully!'));
				}
				else
				{
					Log::add('Activation error. Database says: ' . print_r(Database::getHandle()->errorInfo(), true));
					Content::setContent(Message::error('An error occurred during account activation. Please contact an hRepo administrator.'));
				}
			}
			else
			{
				// now I've got to delete their account...
				if (Database::delete('users', array('uid = ?', $uid), 1))
				{
					Log::add('Account deletion successful.');
					Content::setContent(Message::success('The account has been deleted successfully.'));
				}
				else
				{
					Log::add('Deletion error. Database says: ' . print_r(Database::getHandle()->errorInfo(), true));
					Content::setContent(Message::error('An error occurred during account deletion. Please contact an hRepo administrator.'));
				}
			}
		}
	}
}