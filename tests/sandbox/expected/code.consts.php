<?php
%A%
		echo 'consts

';
		echo LR\Filters::escapeHtmlText(\Name\MyClass::CONST) /* line %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($obj::CONST) /* line %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($obj::CONST) /* line %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->call($obj::CONST, [])) /* line %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->call($obj::CONST[0], [])) /* line %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->call(namespace\CONST[0], [])) /* line %d%:%d% */;
		echo '
-';
%A%
