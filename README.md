Malin - Ett enkelt MVC ramverk
==============================

Detta ramverk är ett projekt baserat på kursen PHPMVC, gjort av Daniel Schäder.


Installation
------------

För att installera Malin måste du först ladda ner ramverket från Github och sedan ladda upp på din server. Som standard är ramverket inställt på att det ligger som grund till din webbsida. Skulle du lägga Malin inuti en mapp på din server måste du nagivera .htaccess filen dit vilket du gör genom att skriva in vart det ligger på raden "RewriteBase".

t ex:

    RewriteBase / {din mapp} /
  
Nu kan du navigera till din server i webbläsaren. Där kan du se en startsida där det finns information om det nuvarande läget för ramverket. Det står lite lätt information vad du behöver göra för att kunna installera databasen och inställningar. I den nedersta rutan skall det nu stå `Your database has not yet been configured please proceed with setup`. Det är för att databasen inte är konfigurerad. Klicka på `Begin setup` när du vill fortsätta.

Nu påbörjas en installationsprocess du kan följa för att installera ramverket. Följ instruktionerna för att konfigurera ramverket.

Användning
----------

Nu när ramverket är installerat kan du endast nå startsidan om du är inloggad som en administratör och sedan klickar på webbsidans rubrik eller logga. Annars hamnar du på den offentliga startsidan som du ställt in via installationen.

När du loggat in som `admin` kan du klicka på länken `acp` uppe i högra hörnet. Där kommer du till `Admin Control Panel`. Där kan du lägga till användare/grupper, redigera existerande användare/grupper, ändra en användares medlemskap i grupper, ändra dina sido-inställningar och ändra utseendet på webbsidan. Allt innehåll i acp är endast tillgängligt för administratörer. Användare som är medlemmar av `The Administrator Group` är alla administratörer.

När du klickar på startsidan, extra sidan 1 och extra sidan 2 kan du se vart du hittar respektive .php fil där du kan redigera innehållet i vardera sida. Där skriver du endast det innehåll du vill ha och inte några <body> eller <head> taggar.

För att ändra din logo  går du in på site/themes/mytheme/ och ersätter logo_80x80.png med din logo av filtyp png med samma namn. Alltså logo_80x80.png.

Debug
-----

I config.php som finns i site mappen kan du ställa in om du vill visa debug på din sida om du skulle vilja felsöka något. Där kan du se allt som händer och innehåll i filer o s v. Detta aktiverar du Genom att sätta följande värden på `True`.

    /**
     * Set what to show as debug or developer information in the get_debug() theme helper.
     */
    $ma->config['debug']['malin'] = false;
    $ma->config['debug']['session'] = false;
    $ma->config['debug']['timer'] = false;
    $ma->config['debug']['db-num-queries'] = false;
    $ma->config['debug']['db-queries'] = false;

Skriva ett blogginlägg
----------------------

Skriva ett blogginlägg kan du göra om du är administratör. Då kommer du åt denna funktion antingen genom Admin control panel eller när du redigerar ett existerande inlägg. När du gör ett nytt inlägg ska du fylla i följande rutor:

* Title: Här skriver du inläggets titel.
* Linktext: En kort länktext för detta inlägget
* Content: Inläggets innehåll
* Type: Här fyller du i vad för sorts typ inlägget ska vara. I detta fallet ska det vara post. För mer avancerade användare kan du skriva page här och använda inlägget som en webbsida.
* Filter: I filter skriver du hur innehållet ska visas upp. du kan välja på htmlpurify, bbcode och plain. Vad som är skillnaden på dessa kan du se på exempelinlägg som kommer med vid installation av databasen.
