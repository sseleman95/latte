<?php
%A%
		echo 'functions

';
		echo LR\Filters::escapeHtmlText(func()) /* line %d%:%d% */;
		echo '
';
		echo LR\Filters::escapeHtmlText($this->global->sandbox->call('func', [])) /* line %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->call('fu' . 'nc', [])) /* line %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText(\func()) /* line %d%:%d% */;
		echo '
';
		echo LR\Filters::escapeHtmlText(ns\func()) /* line %d%:%d% */;
		echo '
';
		echo LR\Filters::escapeHtmlText($this->global->sandbox->prop(func(), 'prop')->prop) /* line %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->call(func(), [])) /* line %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->call(func(...$this->global->sandbox->args($this->global->sandbox->prop($a, 'prop')->prop)), [func()])) /* line %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($this->global->sandbox->call(func()['x'], [])) /* line %d%:%d% */;
		echo '
-';
%A%
