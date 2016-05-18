<?php

if ( ! function_exists('uses_trait'))
{
	function uses_trait($class, $trait, $includeParents = true)
	{
		if (in_array(trim($trait, ' \\'), class_uses($class)))
			return true;

		if ($includeParents)
		{
			foreach (class_parents($class) as $parent)
			{
				if (uses_trait($parent, $trait, false))
					return true;
			}
		}

		return false;
	}
}
