<?php

if (strtolower($_SERVER['REQUEST_METHOD']) === 'post') {

  header('Content-Type: text/plain; charset=UTF-8');

  $message = isset($_POST['message']) ? $_POST['message'] : '';
  $from = isset($_POST['from']) ? $_POST['from'] : '';

  if (!empty($message)) {

    $id = md5($_SERVER['REMOTE_ADDR']);

    $users = array();
    $filename = $_SERVER['NFSN_SITE_ROOT'] . 'protected/email_users.dat';
    if (file_exists($filename)) {
      $users = unserialize(file_get_contents($filename));
      if (!is_array($users)) {
        $users = array();
      }
    }

    if (isset($users[$id])) {

      if ($users[$id]['unblocked'] > time()) {
        echo 'limit';
        exit;
      }

      if ($users[$id]['count'] >= 3) {
        $users[$id]['count'] = 0;
      }

    } else {

      $users[$id] = array(

        'count' => 0,

        'unblocked' => 0,

        'ip' => $_SERVER['REMOTE_ADDR']

      );

    }

    if (empty($from)) {
      $from = 'Anonymous';
    }

    $content = sprintf("Email sent from <%s> via %s:\n\n%s", $from, $_SERVER['HTTP_HOST'], $message);

    $success = mail($_SERVER['SERVER_ADMIN'], 'Aiham.net email form', wordwrap($content, 70));

    if (!$success) {
      echo 'fail';
      exit;
    }

    $users[$id]['count']++;

    if ($users[$id]['count'] >= 3) {
      $users[$id]['unblocked'] = time() + 24 * 60 * 60;
    }

    file_put_contents($filename, serialize($users));

    echo 'success';
    exit;

  }

  echo 'invalid';
  exit;

}

header('Content-Type: text/html; charset=UTF-8');

$quotes = array(

  'Nothing worth having comes easy'

);

$quote = $quotes[mt_rand(0, count($quotes) - 1)];

?><!doctype html>

<!-- Why hello there! Fancy seeing you here. Take a look around, make yourself at home. -->

<html lang="en">

  <head>

    <!-- Isn't html 5 nice and pretty? -->

    <meta charset="utf-8">

    <title>Aiham Hammami</title>

    <link rel="stylesheet" href="style.css" media="all">

    <meta name="google-site-verification" content="QhjjbMu1isNSj0NFvNkJ1y4y9HDtzYzKGsYX8Low3fI">

  </head>
  
  <body>
  
    <div id="container">

      <!-- I did my best to keep this page viewable even without Javascript. Hooray for unobtrusive Javascript! -->

      <script> document.getElementById('container').style.display = 'none';</script>
    
      <h1><a href="http://www.aiham.net/">Aiham Hammami</a></h1>

      <div id="quote" title="My favourite quotes"><span>&ldquo;</span> <?php echo htmlspecialchars($quote, ENT_QUOTES, 'UTF-8'); ?> <span>&rdquo;</span></div>

      <!-- Pretty sure the bots won't be able to figure out how to get around this one too fast. -->
      
      <div id="contact">aiham<span style="font-style: italics; display:none">don't add this to the email, it's here so i don't get strange emails from bots trying to sell me stuff (they're not very good at it)</span>&#64;aiham.net</div>

      <ul>
      
        <li>
      
          <h2><a href="http://personal.aiham.net/">Personal</a></h2>
      
          <p>Personal blog about my interests, daily life, travel, work and school. Other than programming, I'm interested in Japan and have lived there for a few years learning the language and experiencing the culture.</p>
      
        </li>
      
        <li>
      
          <h2><a href="http://programming.aiham.net/">Programming</a></h2>
      
          <p>Programming blog detailing my experience with technology; mainly software development. The main languages I use are php, javascript, perl and objective-c. I'm always learning new things and so try to post about them.</p>
      
        </li>
      
      </ul>
    
    </div>

    <!-- jQuery, making developers lazy since 2006. -->
  
    <script src="http://files.aiham.net/js/jquery-1.5.1.min.js"></script>

    <script src="script.js"></script>

    <script src="/analytics.js"></script>

  </body>

</html>
