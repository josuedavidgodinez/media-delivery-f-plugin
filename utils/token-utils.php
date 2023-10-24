<?php
function getNoExpToken_MDF()
{
  accessToken();  
  return $_SESSION['token'];
}
