<?php
namespace Everyman\Plumber\Pipe;

class IdentityPipe extends TransformPipe
{
	public function __construct()
	{
		parent::__construct(function ($value) {
			return $value;
		});
	}
}
