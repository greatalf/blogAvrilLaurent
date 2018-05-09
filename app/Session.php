<?php
class Session
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
		}
		unset($_SESSION['flash']);
	}
}
?>
