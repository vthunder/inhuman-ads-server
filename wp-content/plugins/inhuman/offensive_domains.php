<?php

  // From Alexa top sites, adult category
  $offensive_domains = [
    "xvideos.com",
    "xnxx.com",
    "chaturbate.com",
    "livejasmin.com",
    "4chan.org",
    "youporn.com",
    "bongacams.com",
    "flirt4free.com",
    "porn.com",
    "cam4.com",
    "liveleak.com",
    "nudevista.com",
    "spankwire.com",
    "adultfriendfinder.com",
    "fetlife.com",
    "clips4sale.com",
    "literotica.com",
    "planetsuzy.org",
    "freeones.com",
    "luscious.net",
    "cams.com",
    "ebaumsworld.com",
    "hentai-foundry.com",
    "sextvx.com",
    "adam4adam.com",
    "hentai2read.com",
    "cliphunter.com",
    "nhentai.net",
    "oglaf.com",
    "gayboystube.com",
    "mrskin.com",
    "iafd.com",
    "digitalplayground.com",
    "alotporn.com",
    "adultwork.com",
    "adultdvdempire.com",
    "imlive.com",
    "asstr.org",
    "ftvgirls.com",
    "suicidegirls.com",
    "celebritymoviearchive.com",
    "streamate.com",
    "manhunt.net",
    "squirt.org",
    "furaffinity.net",
    "f-list.net",
    "vintage-erotica-forum.com",
    "adultdvdtalk.com",
    "inkbunny.net",
    "nifty.org"
  ];

  function likely_offensive_domain($domain) {
    global $offensive_domains;
    $matches = preg_grep("/$domain/", $offensive_domains);
    return count($matches) > 0;
  }

?>
