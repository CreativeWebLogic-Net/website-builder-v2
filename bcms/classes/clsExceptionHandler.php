<?php

class clsExceptionHandler
{
	public function handle(Throwable $ex)
	{
		//echo "0=>";
		echo "\n Error=>".var_export($ex,true)." \n";
	}
}

