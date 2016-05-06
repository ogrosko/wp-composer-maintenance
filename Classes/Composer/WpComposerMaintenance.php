<?php 
namespace OGrosko\Composer;

use Composer\Script\Event;

class WpComposerMaintenance {

	const MAINTENANCE_FILENAME = '.maintenance';

	/**
	 * Enable maintenance mode
	 * @return int|bool
	 */
	public static function maintenance_enable(Event $event) {
		$file = self::getMaintenanceFilePath($event);
		
		$maintenance_string = '<?php $upgrading = ' . time() . '; ?>';
		if (file_exists($file)) {
			unlink($file);
		}
		$result = file_put_contents($file, $maintenance_string);

		if ($result !== false) {
			$event->getIO()->write(">> Maintenance mode successfully enabled <<");
		}
		else {
			throw new Exception\WpComposerMaintenanceException('Problem with enabling Wordpress maintenance mode');
		}

		return $result;
	}


	/**
	 * Disable maintenance mode
	 * @return bool
	 */
	public static function maintenance_disable(Event $event) {
		$file = self::getMaintenanceFilePath($event);
		$result = true;

		if (file_exists($file)) {
			$result = unlink($file);
		}

		if ($result) {
			$event->getIO()->write('>> Maintenance mode successfully disabled <<');
		}
		else {
			throw new Exception\WpComposerMaintenanceException('Problem with disabling Wordpress maintenance mode');
		}

		return $result;
	}

	/**
	 * Get wordrepss core dir
	 * @param  Event  $event
	 * @return string
	 */
	private static function getMaintenanceFilePath(Event $event) {
		$installationDir = false;
		$package = $event->getComposer()->getPackage();
		$prettyName      = $package->getPrettyName();
		if ( $event->getComposer()->getPackage() ) {
			$topExtra = $event->getComposer()->getPackage()->getExtra();
			if ( ! empty( $topExtra['wordpress-install-dir'] ) ) {
				$installationDir = $topExtra['wordpress-install-dir'];
				if ( is_array( $installationDir ) ) {
					$installationDir = empty( $installationDir[$prettyName] ) ? false : $installationDir[$prettyName];
				}
			}
		}
		$extra = $event->getComposer()->getPackage()->getExtra();
		if ( ! $installationDir && ! empty( $extra['wordpress-install-dir'] ) ) {
			$installationDir = $extra['wordpress-install-dir'];
		}
		if ( ! $installationDir ) {
			$installationDir = '';
		}
		
		return rtrim(getcwd() . DIRECTORY_SEPARATOR . $installationDir, '/') . '/' . self::MAINTENANCE_FILENAME;
	}
}