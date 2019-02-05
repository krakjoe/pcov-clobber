<?php
namespace pcov\Clobber {

	use PhpParser\ParserFactory;
	use PhpParser\BuilderFactory;

	use PhpParser\NodeTraverser;
	use PhpParser\Node;
	use PhpParser\Node\Stmt\ClassMethod;
	use PhpParser\NodeVisitorAbstract;

	use PhpParser\PrettyPrinter;

	class CodeCoverage extends Patch {

		public function clobber(int $version) {
			$iterator = new NodeTraverser();
			$iterator->addVisitor(new class($this->builder, $version) extends NodeVisitorAbstract {
				public function __construct($builder, $version) {
					$this->builder = $builder;
					$this->version = $version;
				}

				public function leaveNode(Node $node) {
					if ($node instanceof Node\Expr\New_) {
						if ($node->class->parts[0] == "Xdebug") {
							return new Node\Expr\New_(
								new Node\Name("\\pcov\\Clobber\\Driver\\PHPUnit{$this->version}"), $node->args);
						}
					}
				}

				private $builder;
				private $version;
			});
			
			$this->save(
				$iterator->traverse(
					$this->parser->parse($this->source)));
		}

		public function unclobber() {
			$iterator = new NodeTraverser();
			$iterator->addVisitor(new class($this->builder) extends NodeVisitorAbstract {
				public function __construct($builder) {
					$this->builder = $builder;
				}

				public function leaveNode(Node $node) {
					if ($node instanceof Node\Expr\New_) {
						if ($node->class->parts[0] == "pcov") {
							return new Node\Expr\New_(new Node\Name("Xdebug"), $node->args);
						}
					}
				}

				private $builder;
			});
			
			$this->save(
				$iterator->traverse(
					$this->parser->parse($this->source)));
		}
	}
}
