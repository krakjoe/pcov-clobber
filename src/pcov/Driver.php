<?php
namespace pcov 
{
	/**
	 * Driver for PCOV code coverage functionality.
	 *
	 * @codeCoverageIgnore
	 */
	class Driver
	{
	    public function __construct($filter = null)
	    {
		
	    }

	    /**
	     * Start collection of code coverage information.
	     */
	    public function start(bool $determineUnusedAndDead = true)
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

			if ($collect) {
				\pcov\clear();
			}
		}

		return $collect;
	    }
	}

}
