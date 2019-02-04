<?php
namespace pcov 
{
	use \Composer\Script\Event;

	class Clobber {

		public static function autoload(Event $ev) {
			$composer = $ev->getComposer();
			$vendor   = $composer->getConfig()
					->get("vendor-dir");
			$autoload = sprintf(
				"%s/autoload.php", $vendor);
			$contents = file_get_contents($autoload);

			$contents = preg_replace(
				"~return([^;]+)~",
				"uopz_set_mock(\\SebastianBergmann\\CodeCoverage\\Driver\\Xdebug::class, ".
					"\\pcov\Driver::class);\n\n".
				"\$autoloader = \\1;\n\n".
				"uopz_set_return(\\SebastianBergmann\\Environment\\Runtime::class, 'hasXdebug', true);\n\n".
				"return \$autoloader;", $contents);

			file_put_contents($autoload, $contents);
		}
	}
}
