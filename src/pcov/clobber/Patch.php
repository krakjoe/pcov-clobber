<?php
namespace pcov\Clobber {

	use PhpParser\ParserFactory;
	use PhpParser\BuilderFactory;
	use PhpParser\PrettyPrinter;

	abstract class Patch {

		final public function __construct($target) {
			if (!\file_exists($target) || !\is_writable($target)) {
				throw new Error("the target {$target} could not be found or written");
			}

			$this->target = $target;
			$this->source  = file_get_contents($this->target);
			$this->builder = new BuilderFactory();
			$this->parser = (new ParserFactory)
				->create(ParserFactory::ONLY_PHP7);
		}

		abstract public function clobber(int $version);
		abstract public function unclobber();

		final protected function save($ast) {
			$source = (new PrettyPrinter\Standard())
					->prettyPrintFile($ast);

			file_put_contents($this->target, $source);
		}

		protected $target;
		protected $source;
		protected $builder;
		protected $parser;
	}
}
