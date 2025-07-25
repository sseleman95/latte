<?php

/**
 * This file is part of the Latte (https://latte.nette.org)
 * Copyright (c) 2008 David Grudl (https://davidgrudl.com)
 */

declare(strict_types=1);

namespace Latte\Essential;

use Latte;
use Latte\Compiler\Nodes\Php\Scalar;
use Latte\Compiler\Nodes\TextNode;
use Latte\Compiler\Tag;
use Latte\Compiler\TemplateParser;
use Latte\Runtime;
use Latte\RuntimeException;
use Nette;
use function array_keys, class_exists, extension_loaded, preg_match;


/**
 * Basic tags and filters for Latte.
 */
final class CoreExtension extends Latte\Extension
{
	private Latte\Engine $engine;
	private Filters $filters;


	public function __construct()
	{
		$this->filters = new Filters;
	}


	public function beforeCompile(Latte\Engine $engine): void
	{
		$this->engine = $engine;
	}


	public function beforeRender(Runtime\Template $template): void
	{
		$this->filters->locale = $template->getEngine()->getLocale();
	}


	public function getTags(): array
	{
		return [
			'embed' => [Nodes\EmbedNode::class, 'create'],
			'define' => [Nodes\DefineNode::class, 'create'],
			'block' => [Nodes\BlockNode::class, 'create'],
			'layout' => [Nodes\ExtendsNode::class, 'create'],
			'extends' => [Nodes\ExtendsNode::class, 'create'],
			'import' => [Nodes\ImportNode::class, 'create'],
			'include' => \Closure::fromCallable([$this, 'includeSplitter']),

			'n:attr' => [Nodes\NAttrNode::class, 'create'],
			'n:class' => [Nodes\NClassNode::class, 'create'],
			'n:tag' => [Nodes\NTagNode::class, 'create'],

			'parameters' => [Nodes\ParametersNode::class, 'create'],
			'varType' => [Nodes\VarTypeNode::class, 'create'],
			'varPrint' => [Nodes\VarPrintNode::class, 'create'],
			'templateType' => [Nodes\TemplateTypeNode::class, 'create'],
			'templatePrint' => [Nodes\TemplatePrintNode::class, 'create'],

			'=' => [Latte\Compiler\Nodes\PrintNode::class, 'create'],
			'do' => [Nodes\DoNode::class, 'create'],
			'php' => [Nodes\DoNode::class, 'create'], // obsolete
			'contentType' => [Nodes\ContentTypeNode::class, 'create'],
			'spaceless' => [Nodes\SpacelessNode::class, 'create'],
			'capture' => [Nodes\CaptureNode::class, 'create'],
			'l' => fn(Tag $tag) => new TextNode('{', $tag->position),
			'r' => fn(Tag $tag) => new TextNode('}', $tag->position),
			'syntax' => \Closure::fromCallable([$this, 'parseSyntax']),

			'dump' => [Nodes\DumpNode::class, 'create'],
			'debugbreak' => [Nodes\DebugbreakNode::class, 'create'],
			'trace' => [Nodes\TraceNode::class, 'create'],

			'var' => [Nodes\VarNode::class, 'create'],
			'default' => [Nodes\VarNode::class, 'create'],

			'try' => [Nodes\TryNode::class, 'create'],
			'rollback' => [Nodes\RollbackNode::class, 'create'],

			'foreach' => [Nodes\ForeachNode::class, 'create'],
			'for' => [Nodes\ForNode::class, 'create'],
			'while' => [Nodes\WhileNode::class, 'create'],
			'iterateWhile' => [Nodes\IterateWhileNode::class, 'create'],
			'sep' => [Nodes\FirstLastSepNode::class, 'create'],
			'last' => [Nodes\FirstLastSepNode::class, 'create'],
			'first' => [Nodes\FirstLastSepNode::class, 'create'],
			'skipIf' => [Nodes\JumpNode::class, 'create'],
			'breakIf' => [Nodes\JumpNode::class, 'create'],
			'exitIf' => [Nodes\JumpNode::class, 'create'],
			'continueIf' => [Nodes\JumpNode::class, 'create'],

			'if' => [Nodes\IfNode::class, 'create'],
			'ifset' => [Nodes\IfNode::class, 'create'],
			'ifchanged' => [Nodes\IfChangedNode::class, 'create'],
			'n:ifcontent' => [Nodes\IfContentNode::class, 'create'],
			'n:else' => [Nodes\NElseNode::class, 'create'],
			'switch' => [Nodes\SwitchNode::class, 'create'],
		];
	}


	public function getFilters(): array
	{
		return [
			'batch' => [$this->filters, 'batch'],
			'breakLines' => [$this->filters, 'breaklines'],
			'breaklines' => [$this->filters, 'breaklines'],
			'bytes' => [$this->filters, 'bytes'],
			'capitalize' => extension_loaded('mbstring')
				? [$this->filters, 'capitalize']
				: fn() => throw new RuntimeException('Filter |capitalize requires mbstring extension.'),
			'ceil' => [$this->filters, 'ceil'],
			'checkUrl' => [$this->filters, 'sanitizeUrl'],
			'clamp' => [$this->filters, 'clamp'],
			'dataStream' => [$this->filters, 'dataStream'],
			'datastream' => [$this->filters, 'dataStream'],
			'date' => [$this->filters, 'date'],
			'escape' => [Latte\Runtime\Filters::class, 'nop'],
			'escapeCss' => [Latte\Runtime\Filters::class, 'escapeCss'],
			'escapeHtml' => [Latte\Runtime\Filters::class, 'escapeHtml'],
			'escapeHtmlComment' => [Latte\Runtime\Filters::class, 'escapeHtmlComment'],
			'escapeICal' => [Latte\Runtime\Filters::class, 'escapeICal'],
			'escapeJs' => [Latte\Runtime\Filters::class, 'escapeJs'],
			'escapeUrl' => 'rawurlencode',
			'escapeXml' => [Latte\Runtime\Filters::class, 'escapeXml'],
			'explode' => [$this->filters, 'explode'],
			'filter' => [$this->filters, 'filter'],
			'first' => [$this->filters, 'first'],
			'firstLower' => extension_loaded('mbstring')
				? [$this->filters, 'firstLower']
				: fn() => throw new RuntimeException('Filter |firstLower requires mbstring extension.'),
			'firstUpper' => extension_loaded('mbstring')
				? [$this->filters, 'firstUpper']
				: fn() => throw new RuntimeException('Filter |firstUpper requires mbstring extension.'),
			'floor' => [$this->filters, 'floor'],
			'group' => [$this->filters, 'group'],
			'implode' => [$this->filters, 'implode'],
			'indent' => [$this->filters, 'indent'],
			'join' => [$this->filters, 'implode'],
			'last' => [$this->filters, 'last'],
			'length' => [$this->filters, 'length'],
			'localDate' => [$this->filters, 'localDate'],
			'lower' => extension_loaded('mbstring')
				? [$this->filters, 'lower']
				: fn() => throw new RuntimeException('Filter |lower requires mbstring extension.'),
			'number' => [$this->filters, 'number'],
			'padLeft' => [$this->filters, 'padLeft'],
			'padRight' => [$this->filters, 'padRight'],
			'query' => [$this->filters, 'query'],
			'random' => [$this->filters, 'random'],
			'repeat' => [$this->filters, 'repeat'],
			'replace' => [$this->filters, 'replace'],
			'replaceRe' => [$this->filters, 'replaceRe'],
			'replaceRE' => [$this->filters, 'replaceRe'],
			'reverse' => [$this->filters, 'reverse'],
			'round' => [$this->filters, 'round'],
			'slice' => [$this->filters, 'slice'],
			'sort' => [$this->filters, 'sort'],
			'spaceless' => [$this->filters, 'strip'],
			'split' => [$this->filters, 'explode'],
			'strip' => [$this->filters, 'strip'], // obsolete
			'stripHtml' => [$this->filters, 'stripHtml'],
			'striphtml' => [$this->filters, 'stripHtml'],
			'stripTags' => [$this->filters, 'stripTags'],
			'striptags' => [$this->filters, 'stripTags'],
			'substr' => [$this->filters, 'substring'],
			'trim' => [$this->filters, 'trim'],
			'truncate' => [$this->filters, 'truncate'],
			'upper' => extension_loaded('mbstring')
				? [$this->filters, 'upper']
				: fn() => throw new RuntimeException('Filter |upper requires mbstring extension.'),
			'webalize' => class_exists(Nette\Utils\Strings::class)
				? [Nette\Utils\Strings::class, 'webalize']
				: fn() => throw new RuntimeException('Filter |webalize requires nette/utils package.'),
		];
	}


	public function getFunctions(): array
	{
		return [
			'clamp' => [$this->filters, 'clamp'],
			'divisibleBy' => [$this->filters, 'divisibleBy'],
			'even' => [$this->filters, 'even'],
			'first' => [$this->filters, 'first'],
			'group' => [$this->filters, 'group'],
			'last' => [$this->filters, 'last'],
			'odd' => [$this->filters, 'odd'],
			'slice' => [$this->filters, 'slice'],
			'hasBlock' => fn(Runtime\Template $template, string $name): bool => $template->hasBlock($name),
			'hasTemplate' => fn(Runtime\Template $template, string $name): bool => $this->hasTemplate($name, $template->getName()),
		];
	}


	public function getPasses(): array
	{
		$passes = new Passes($this->engine);
		return [
			'internalVariables' => [$passes, 'forbiddenVariablesPass'],
			'checkUrls' => [$passes, 'checkUrlsPass'],
			'overwrittenVariables' => [Nodes\ForeachNode::class, 'overwrittenVariablesPass'],
			'customFunctions' => [$passes, 'customFunctionsPass'],
			'moveTemplatePrintToHead' => [Nodes\TemplatePrintNode::class, 'moveToHeadPass'],
			'nElse' => [Nodes\NElseNode::class, 'processPass'],
		];
	}


	public function getCacheKey(Latte\Engine $engine): mixed
	{
		return array_keys($engine->getFunctions());
	}


	/**
	 * {include [file] "file" [with blocks] [,] [params]}
	 * {include [block] name [,] [params]}
	 */
	private function includeSplitter(Tag $tag, TemplateParser $parser): Nodes\IncludeBlockNode|Nodes\IncludeFileNode
	{
		$tag->expectArguments();
		$mod = $tag->parser->tryConsumeTokenBeforeUnquotedString('block', 'file');
		if ($mod) {
			$block = $mod->text === 'block';
		} elseif ($tag->parser->stream->tryConsume('#')) {
			$block = true;
		} else {
			$name = $tag->parser->parseUnquotedStringOrExpression();
			$block = $name instanceof Scalar\StringNode && preg_match('~[\w-]+$~DA', $name->value);
		}
		$tag->parser->stream->seek(0);

		return $block
			? Nodes\IncludeBlockNode::create($tag, $parser)
			: Nodes\IncludeFileNode::create($tag);
	}


	/**
	 * {syntax ...}
	 */
	private function parseSyntax(Tag $tag, TemplateParser $parser): \Generator
	{
		if ($tag->isNAttribute() && $tag->prefix !== $tag::PrefixNone) {
			throw new Latte\CompileException("Use n:syntax instead of {$tag->getNotation()}", $tag->position);
		}
		$tag->expectArguments();
		$token = $tag->parser->stream->consume();
		$lexer = $parser->getLexer();
		$lexer->setSyntax($token->text, $tag->isNAttribute() ? null : $tag->name);
		[$inner] = yield;
		if (!$tag->isNAttribute()) {
			$lexer->popSyntax();
		}
		return $inner;
	}


	/**
	 * Checks if template exists.
	 */
	private function hasTemplate(string $name, string $referringName): bool
	{
		try {
			$name = $this->engine->getLoader()->getReferredName($name, $referringName);
			$this->engine->createTemplate($name, [], clearCache: false);
			return true;
		} catch (Latte\TemplateNotFoundException) {
			return false;
		}
	}
}
