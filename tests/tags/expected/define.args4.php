<?php
%A%
final class Template%a% extends Latte\Runtime\Template
{
	public const Source = 'main';


	public function main(array $ʟ_args): void
	{
		extract($ʟ_args);
		unset($ʟ_args);

		echo '
named arguments import

';
		$this->createTemplate('import.latte', $this->params, "import")->render() /* line %d%:%d% */;
		echo '
a) ';
		$this->renderBlock('test', [1, 'var1' => 2] + [], 'html') /* line %d%:%d% */;
		echo '

b) ';
		$this->renderBlock('test', ['var2' => 1] + [], 'html') /* line %d%:%d% */;
		echo '

c) ';
		$this->renderBlock('test', ['hello' => 1] + [], 'html') /* line %d%:%d% */;
	}
}
