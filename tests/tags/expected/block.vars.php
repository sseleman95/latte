<?php
%A%
		echo LR\Filters::escapeHtmlText($var) /* line %d%:%d% */;
		echo '


';
		$this->renderBlock('b', get_defined_vars()) /* line %d%:%d% */;
		echo "\n";
		echo LR\Filters::escapeHtmlText($var) /* line %d%:%d% */;
		echo '


';
		ob_start(fn() => '') /* line %d%:%d% */;
		try {
			(function () {
				extract(func_get_arg(0));
				echo '	';
				echo LR\Filters::escapeHtmlText($var) /* line %d%:%d% */;
				echo "\n";
				$var = 'blockmod' /* line %d%:%d% */;
			})(get_defined_vars());
		} finally {
			$ʟ_fi = new LR\FilterInfo('html');
			echo LR\Filters::convertTo($ʟ_fi, 'html', $this->filters->filterContent('trim', $ʟ_fi, ob_get_clean()));
		}
		echo "\n";
		echo LR\Filters::escapeHtmlText($var) /* line %d%:%d% */;
		echo '


';
		ob_start(fn() => '') /* line %d%:%d% */;
		try {
			(function () {
				extract(func_get_arg(0));
				echo '	';
				echo LR\Filters::escapeHtmlText($var) /* line %d%:%d% */;
				echo "\n";
				$var = 'block' /* line %d%:%d% */;
			})(get_defined_vars());
		} finally {
			$ʟ_fi = new LR\FilterInfo('html');
			echo ob_get_clean();
		}
		echo "\n";
		echo LR\Filters::escapeHtmlText($var) /* line %d%:%d% */;
	}


	public function prepare(): array
	{
		extract($this->params);

		$var = 'a' /* line %d%:%d% */;
		return get_defined_vars();
	}


	/** {define a} on line %d% */
	public function blockA(array $ʟ_args): void
	{
		extract($this->params);
		extract($ʟ_args);
		unset($ʟ_args);

		echo '	';
		echo LR\Filters::escapeHtmlText($var) /* line %d%:%d% */;
		echo "\n";
		$var = 'define' /* line %d%:%d% */;
	}


	/** {block b} on line %d% */
	public function blockB(array $ʟ_args): void
	{
		extract($this->params);
		extract($ʟ_args);
		unset($ʟ_args);

		echo '	';
		echo LR\Filters::escapeHtmlText($var) /* line %d%:%d% */;
		echo "\n";
		$var = 'blocknamed' /* line %d%:%d% */;
	}
%A%
