<?php
namespace Laurent\App\Service;
if(session_status() == PHP_SESSION_NONE)
{
	session_start();
}
class Flash
{
	public static function setFlash($message, $type = 'danger')
	{
		$_SESSION['flash'] = array(
			'message' => $message,
			'type' => $type
		);

	}

	public static function flash()
	{
		if(isset($_SESSION['flash']))
		{
		?>
			<div class="alert alert-<?= $_SESSION['flash']['type'] ?>">
				<a class="close">x</a>
				<?= $_SESSION['flash']['message'] ?>
			</div>
		<?php
		// D::V($_SESSION['flash']);
		}
		unset($_SESSION['flash']);
	}

	public static function cookieFlash($value, $type = 'danger')
	{
		if(isset($_COOKIE["$value"]))
		{
		?>
			<div class="alert alert-<?= $type ?>">
				<a class="close">x</a>
				<?= $_COOKIE["$value"] ?>
			</div>
		<?php
		}
	}
}
