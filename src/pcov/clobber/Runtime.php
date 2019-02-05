<?php
namespace pcov\Clobber {

	use PhpParser\ParserFactory;
	use PhpParser\BuilderFactory;

	use PhpParser\NodeTraverser;
	use PhpParser\Node;
	use PhpParser\Node\Stmt\ClassMethod;
	use PhpParser\NodeVisitorAbstract;

	use PhpParser\PrettyPrinter;

	class Runtime extends Patch {

		public function clobber(int $version) {
			$iterator = new NodeTraverser();
			$iterator->addVisitor(new class($this->builder) extends NodeVisitorAbstract {
				public function __construct($builder) {
					$this->builder = $builder;
				}

				public function leaveNode(Node $node) {
					if ($node instanceof ClassMethod) {
						if ($node->name->name == "hasXdebug") {
							$node->stmts = [new Node\Stmt\Return_(
								new Node\Expr\BinaryOp\BooleanAnd(
									$this->builder->funcCall("\\extension_loaded", ["pcov"]),
									$this->builder->funcCall("\\ini_get", ["pcov.enabled"]))
							)];
						}
					}
				}

				private $builder;
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
					if ($node instanceof ClassMethod) {
						if ($node->name->name == "hasXdebug") {
							$node->stmts = [new Node\Stmt\Return_(
								$this->builder->funcCall("\\extension_loaded", ["xdebug"])
							)];
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
