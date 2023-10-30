<?php

$name = "./filecreated/RSL-BKG-31738.pdf";

$f = fopen($name, 'wb');
if (!$f) {
	echo 'Unable to create output file: ' . $name;
}
fwrite($f, $this->getBuffer(), $this->bufferlen);
fclose($f);

?>