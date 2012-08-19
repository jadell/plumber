<?php
namespace Everyman\Plumber\Pipe;

use Everyman\Plumber\Pipe\FilterPipe;

/**
 * Pipe that only returns an element if a randomly chosen value is greater than
 * the given threshold.
 */
class RandomPipe extends FilterPipe
{
	protected $threshold;
	protected $random;

	public function __construct($threshold, $random=null)
	{
		if (!$random) {
			$random = function () {
				return mt_rand(1,100);
			};
		}

		$this->threshold = $threshold;
		$this->random = $random;

		parent::__construct(array($this, 'random'));
	}

	public function random($value, $key)
	{
		$random = call_user_func($this->random);
		return $random < $this->threshold;
	}
}
