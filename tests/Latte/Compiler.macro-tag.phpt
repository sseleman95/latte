<?php

/**
 * Test: Latte\Compiler: <{$tag}>
 */

declare(strict_types=1);

use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


$latte = new Latte\Engine;
$latte->setLoader(new Latte\Loaders\StringLoader);

$template = '<{$tag} title="{$title}">hello</{$tag}>';

Assert::match(<<<'X'
%A%
		echo '<';
		echo LR\Filters::escapeHtmlAttrUnquoted($tag) /* line 1 */;
		echo ' title="';
		echo LR\Filters::escapeHtmlAttr($title) /* line 1 */;
		echo '">hello</';
		echo LR\Filters::escapeHtmlText($tag) /* line 1 */;
		echo '>';
%A%
X
, $latte->compile($template));

Assert::same('<h1 title="hello">hello</h1>', $latte->renderToString($template, ['tag' => 'h1', 'title' => 'hello']));
