<?php

/**
 * This file is part of the ApiGen (http://apigen.org)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace ApiGen\Reflection;

use ApiGen\Configuration\Configuration;
use ApiGen\Parser\Elements\Elements;
use ApiGen\Parser\ParserResult;
use ApiGen\Reflection\TokenReflection\ReflectionFactory;
use ArrayObject;
use Nette;
use Nette\Utils\ArrayHash;
use TokenReflection\Broker;
use TokenReflection\IReflection;
use TokenReflection\IReflectionClass;
use TokenReflection\IReflectionExtension;
use TokenReflection\IReflectionFunction;
use TokenReflection\IReflectionMethod;
use TokenReflection\IReflectionParameter;


abstract class ReflectionBase extends Nette\Object implements IReflection
{

	/**
	 * @var array
	 */
	protected static $reflectionMethods = [];

	/**
	 * @var string
	 */
	protected $reflectionType;

	/**
	 * @var IReflectionClass|IReflectionMethod|IReflectionFunction|IReflectionExtension|IReflectionParameter
	 */
	protected $reflection;

	/**
	 * @var Configuration
	 */
	protected $configuration;

	/**
	 * @var ParserResult
	 */
	protected $parserResult;

	/**
	 * @var ReflectionFactory
	 */
	protected $reflectionFactory;


	public function __construct(IReflection $reflection)
	{
		$this->reflectionType = get_class($this);
		if ( ! isset(self::$reflectionMethods[$this->reflectionType])) {
			self::$reflectionMethods[$this->reflectionType] = array_flip(get_class_methods($this));
		}
		$this->reflection = $reflection;
	}


	/**
	 * @return Broker
	 */
	public function getBroker()
	{
		return $this->reflection->getBroker();
	}


	/**
	 * Returns FQN name.
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->reflection->getName();
	}


	/**
	 * Returns an element pretty (docblock compatible) name.
	 *
	 * @return string
	 */
	public function getPrettyName()
	{
		return $this->reflection->getPrettyName();
	}


	/**
	 * @return bool
	 */
	public function isInternal()
	{
		return $this->reflection->isInternal();
	}


	/**
	 * @return bool
	 */
	public function isUserDefined()
	{
		return $this->reflection->isUserDefined();
	}


	/**
	 * @return bool
	 */
	public function isTokenized()
	{
		return $this->reflection->isTokenized();
	}


	/**
	 * @return string
	 */
	public function getFileName()
	{
		return $this->reflection->getFileName();
	}


	/**
	 * @return integer
	 */
	public function getStartLine()
	{
		$startLine = $this->reflection->getStartLine();

		if ($doc = $this->getDocComment()) {
			$startLine -= substr_count($doc, "\n") + 1;
		}

		return $startLine;
	}


	/**
	 * @return integer
	 */
	public function getEndLine()
	{
		return $this->reflection->getEndLine();
	}


	public function setConfiguration(Configuration $configuration)
	{
		$this->configuration = $configuration;
	}


	public function setParserResult(ParserResult $parserResult)
	{
		$this->parserResult = $parserResult;
	}


	public function setReflectionFactory(ReflectionFactory $reflectionFactory)
	{
		$this->reflectionFactory = $reflectionFactory;
	}


	/**
	 * @return ArrayObject
	 */
	protected function getParsedClasses()
	{
		return $this->parserResult->getElementsByType(Elements::CLASSES);
	}

}
