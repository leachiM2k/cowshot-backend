<?php
/**
 * Generates a highly customized password
 *
 * @author B3NZ0, Michael Rotmanov
 */
class PasswordGen
{

	/**
	 *
	 * @param $pass_len int
	 *       	 The length of the password
	 * @param $pass_num bool
	 *       	 Include numeric chars in the password?
	 * @param $pass_alpha bool
	 *       	 Include alpha chars in the password?
	 * @param $pass_mc bool
	 *       	 Include mixed case chars in the password?
	 * @param $pass_exclude string
	 *       	 Chars to exclude from the password
	 * @param $num_required bool
	 *       	 Password must contain numbers
	 * @return string The password
	 */
	public function PWGen($pass_len = 8, $pass_num = true, $pass_alpha = true, $pass_mc = true, $pass_exclude = '', $num_required = false)
	{
		// Create the salt used to generate the password
		$alpha = 'abcdefghjkmnpqrstuvwxyz';
		$nums = '23456789';

		$salt = '';
		if($pass_alpha !== false) 	$salt .= $alpha;
		if($pass_num !== false)		$salt .= $nums;
		if($salt == '')				throw new Sipgate_Exception("Can not generate password");

		if($pass_mc)
		{ // A-Z
			$salt .= strtoupper($salt);
		}
		// Remove any excluded chars from salt
		if($pass_exclude)
		{
			$exclude = array_unique(preg_split('//', $pass_exclude));
			$salt = str_replace($exclude, '', $salt);
		}
		$salt_len = strlen($salt)-1;
		// Seed the random number generator with today's seed & password's
		// unique Calllist for extra randomness
		mt_srand();
		$pass = '';
		do {
			$pass .= substr($salt, mt_rand(0, $salt_len), 1);
		} while (strlen($pass) < $pass_len);

		if($num_required == true && !preg_match("/\d/", $pass)) {
			$replacement = substr($nums, mt_rand(0, strlen($nums)-1), 1);
			$pass = substr_replace($pass, $replacement, mt_rand(0, $pass_len));
		}

		return $pass;
	}
}
