<?php
namespace pcov\Clobber\Driver
{
	/**
	 * Driver for PCOV code coverage functionality.
	 *
	 * @codeCoverageIgnore
	 */
	class PHPUnit6 implements \SebastianBergmann\CodeCoverage\Driver\Driver
	{
		public function __construct($filter = null)
		{
		
		}

		/**
		 * Start collection of code coverage information.
		 */
		public function start($determineUnusedAndDead = true)
		{
			\pcov\start();
		}

		/**
		 * Stop collection of code coverage information.
		 */
		public function stop()
		{
			\pcov\stop();

			$waiting = \pcov\waiting();
			$collect  = [];

			if ($waiting) {
				$collect = \pcov\collect(\pcov\inclusive, $waiting);

				\pcov\clear();
			}

			return $collect;
		}
	}

}
