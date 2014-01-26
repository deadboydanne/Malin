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

skapa ny sida (avancerade användare)
------------------------------------

För mer avancerade användare kan du skapa ännu fler sidor än de du har att välja på i installationen. Till att börja med kan du lägga till en länk i navigeringsmenyn. Detta gör du genom att lägga till denna kod i `Add menu links` i site/config.php:

	  $ma->config['menus']['my-navbar']['*variable name*'] = array('label'=>'*page name*', 'url'=>'my/*method*');

Denna kod finns färdig men kommenterad inne i filen men vill du ha fler länkar kan du kopiera den. Det du nu behöver göra är att döpa om *variable name* till ett namn på variabeln som kännetecknar din sida. Sedan skriver du om *page name* till namnet på din nya sida och till sist skriver du om *method* till vad du ska döpa din metod till, vilket kommer bli som din länk till sidan.

Nu går du in i webbläsaren till din sida, loggar in som administratör och klickar på acp sedan Create content. Hur du skapar innehåll till en sida kan du se längst ner i denna readme på Skriva ett blogginlägg. när du gjort detta går du in på view all. under fliken all content hittar du det inlägg du precis skapat och till vänster om det står en siffra. memorera den siffran för nästa steg.

Nu öppnar du upp filen site/src/CCMycontroller/CCMycontroller.php. Där inne i klassen CCMycontroller lägger du till denna koden:

	  public function *method*() {
	    $content = new CMContent(*Content id*);
	    $this->views->SetTitle('*Page title*'.htmlEnt($content['title']))
	                ->AddInclude(__DIR__ . '/newpage.tpl.php', array(
	                  'content' => $content,
	                ));
	
	  }

Koden finns redan där men kommenterad. Precis som innan kan du kopiera den för att göra flera sidor. Nu kollar du igenom koden och skriver om *method* till vad du döpte din metod till i navigeringsmenyn. Sedan skriver du om *Content id* till den siffran du skulle memorera av ditt inlägg. Denna fungerar som en id för vilket innehåll du ska visa på sidan. *page title* skriver du om till vad du vill att din sida ska ha för titel.

Nu var det färdigt och du kan använda din sida.

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

Skriva ett blogginlägg kan du göra om du är administratör. Då kommer du åt denna funktion antingen genom att klicka på acp uppe i högra hörnet och sedan create content, eller när du redigerar ett existerande inlägg klickar du på create new. När du gör ett nytt inlägg ska du fylla i följande rutor:

* Title: Här skriver du inläggets/sidans titel.
* key: En kort länktext för inlägget/sidan
* Content: Inläggets/sidans innehåll
* Filter: I filter skriver du hur innehållet ska visas upp. du kan välja på htmlpurify, bbcode och plain. Vad som är skillnaden på dessa kan du se på exempelinlägg som kommer med vid installation av databasen.
* Type: Här fyller du i vad för sorts typ inlägget/sidan ska vara. I detta fallet ska det vara post. För mer avancerade användare kan du skriva page här och använda inlägget som en webbsida.
