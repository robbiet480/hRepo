<?php

if (empty(Content::content)) {
  $http-error = 404;
}

if ($http-error == 404) {
  Content::setcontent(<<<EOT



EOT);
}
