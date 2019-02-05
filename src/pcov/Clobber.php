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

			require_once($autoload);

			if (!class_exists(\PHPUnit\Runner\Version::id())) {
				return;
			}

			if (version_compare(\PHPUnit\Runner\Version::id(), "7", ">=")) {
				$driver = \pcov\Driver7::class;
			} else {
				$driver = \pcov\Driver6::class;
			}

			
			$contents = preg_replace(
				"~return([^;]+)~",
				"return (function(){\n".
					"\tuopz_set_mock(\\SebastianBergmann\\CodeCoverage\\Driver\\Xdebug::class, ".
					"\t$driver::class);\n\n".
				"\t\$autoloader =\\1;\n\n".
				"\t\uopz_set_return(\n".
					"\t\t\\SebastianBergmann\\Environment\\Runtime::class, 'hasXdebug', true);\n\n".
				"return \$autoloader;\n".
				"})();", file_get_contents($autoload));

			file_put_contents($autoload, $contents);
		}

		public static function install(Event $ev) {
			echo "installing\n";
		}
	}
}
