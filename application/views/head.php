<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta name="Author" content="RockStar">
        <title><?=$data['meta_title']?></title>
		<meta name="description" content="<?=$data['meta_description']?>">
		<meta name="keywords" content="<?=$data['meta_keyword']?>">
        <meta name="subject" content="<?=$data['meta_subject']?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta property="og:title" content="락스타-개인블로그">
        <meta property="og:url" content="http://www.rockstar.pe.kr/">
        <meta property="og:description" content="코딩,언어,취미,개인사에대한 블로그입니다."/>
        <meta property="og:site_name" content="락스타-개인블로그">
		<meta name="viewport" content="width=device-width, initial-scale=1,shrink-to-fit=no">
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-100850095-2"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'UA-100850095-2');
        </script>
		<!-- Bootstrap -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <!-- fontawesome CDN -->
        <script defer src="https://use.fontawesome.com/releases/v5.0.4/js/all.js"></script>
        <!-- The core React library -->
        <script src="http://fb.me/react-0.12.0.js"></script>
        <!-- In-browser JSX transformer, remove when pre-compiling JSX. -->
        <script src="http://fb.me/JSXTransformer-0.12.0.js"></script>

        <link rel="shortcut icon" href="/static/img/main/favicon.ico"/>		
		<link rel="stylesheet" href="/css/style.css" media="screen">
        <link rel="stylesheet" href="/css/simple-sidebar.css" media="screen">
        <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
        <link rel="shortcut icon" href="">
        <!-- <script>
            FontAwesomeConfig = { searchPseudoElements: true };
        </script>	 -->	
	</head>
    <body>
    	<?php 	
    	if($this->session->flashdata('message')){
    	?>
    	<script>
    			alert('<?=$this->session->flashdata('message')?>');
    	</script>
    	<?php
    	}
    	?>
    	
