<?php
%A%
		echo 'firstclass callable

';
		echo LR\Filters::escapeHtmlText($this->global->sandbox->closure('trim')) /* line %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->closure([$obj, 'foo'])) /* line %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->closure([$obj, 'foo'])) /* line %d%:%d% */;
		echo '
-';
%A%
