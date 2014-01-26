Malin - Ett enkelt MVC ramverk
==============================

Detta ramverk �r ett projekt baserat p� kursen PHPMVC, gjort av Daniel Sch�der.


Installation
------------

F�r att installera Malin m�ste du f�rst ladda ner ramverket fr�n Github och sedan ladda upp p� din server. Som standard �r ramverket inst�llt p� att det ligger som grund till din webbsida. Skulle du l�gga Malin inuti en mapp p� din server m�ste du nagivera .htaccess filen dit vilket du g�r genom att skriva in vart det ligger p� raden "RewriteBase".

t ex:

    RewriteBase / {din mapp} /
  
Nu kan du navigera till din server i webbl�saren. D�r kan du se en startsida d�r det finns information om det nuvarande l�get f�r ramverket. Det st�r lite l�tt information vad du beh�ver g�ra f�r att kunna installera databasen och inst�llningar. I den nedersta rutan skall det nu st� `Your database has not yet been configured please proceed with setup`. Det �r f�r att databasen inte �r konfigurerad. Klicka p� `Begin setup` n�r du vill forts�tta.

Nu p�b�rjas en installationsprocess du kan f�lja f�r att installera ramverket. F�lj instruktionerna f�r att konfigurera ramverket.

Anv�ndning
----------

Nu n�r ramverket �r installerat kan du endast n� startsidan om du �r inloggad som en administrat�r och sedan klickar p� webbsidans rubrik eller logga. Annars hamnar du p� den offentliga startsidan som du st�llt in via installationen.

N�r du loggat in som `admin` kan du klicka p� l�nken `acp` uppe i h�gra h�rnet. D�r kommer du till `Admin Control Panel`. D�r kan du l�gga till anv�ndare/grupper, redigera existerande anv�ndare/grupper, �ndra en anv�ndares medlemskap i grupper, �ndra dina sido-inst�llningar och �ndra utseendet p� webbsidan. Allt inneh�ll i acp �r endast tillg�ngligt f�r administrat�rer. Anv�ndare som �r medlemmar av `The Administrator Group` �r alla administrat�rer.

N�r du klickar p� startsidan, extra sidan 1 och extra sidan 2 kan du se vart du hittar respektive .php fil d�r du kan redigera inneh�llet i vardera sida. D�r skriver du endast det inneh�ll du vill ha och inte n�gra <body> eller <head> taggar.

F�r att �ndra din logo  g�r du in p� site/themes/mytheme/ och ers�tter logo_80x80.png med din logo av filtyp png med samma namn. Allts� logo_80x80.png.

skapa ny sida (avancerade anv�ndare)
------------------------------------

F�r mer avancerade anv�ndare kan du skapa �nnu fler sidor �n de du har att v�lja p� i installationen. Till att b�rja med kan du l�gga till en l�nk i navigeringsmenyn. Detta g�r du genom att l�gga till denna kod i `Add menu links` i site/config.php:

	  $ma->config['menus']['my-navbar']['*variable name*'] = array('label'=>'*page name*', 'url'=>'my/*method*');

Denna kod finns f�rdig men kommenterad inne i filen men vill du ha fler l�nkar kan du kopiera den. Det du nu beh�ver g�ra �r att d�pa om *variable name* till ett namn p� variabeln som k�nnetecknar din sida. Sedan skriver du om *page name* till namnet p� din nya sida och till sist skriver du om *method* till vad du ska d�pa din metod till, vilket kommer bli som din l�nk till sidan.

Nu g�r du in i webbl�saren till din sida, loggar in som administrat�r och klickar p� acp sedan Create content. Hur du skapar inneh�ll till en sida kan du se l�ngst ner i denna readme p� Skriva ett blogginl�gg. n�r du gjort detta g�r du in p� view all. under fliken all content hittar du det inl�gg du precis skapat och till v�nster om det st�r en siffra. memorera den siffran f�r n�sta steg.

Nu �ppnar du upp filen site/src/CCMycontroller/CCMycontroller.php. D�r inne i klassen CCMycontroller l�gger du till denna koden:

	  public function *method*() {
	    $content = new CMContent(*Content id*);
	    $this->views->SetTitle('*Page title*'.htmlEnt($content['title']))
	                ->AddInclude(__DIR__ . '/newpage.tpl.php', array(
	                  'content' => $content,
	                ));
	
	  }

Koden finns redan d�r men kommenterad. Precis som innan kan du kopiera den f�r att g�ra flera sidor. Nu kollar du igenom koden och skriver om *method* till vad du d�pte din metod till i navigeringsmenyn. Sedan skriver du om *Content id* till den siffran du skulle memorera av ditt inl�gg. Denna fungerar som en id f�r vilket inneh�ll du ska visa p� sidan. *page title* skriver du om till vad du vill att din sida ska ha f�r titel.

Nu var det f�rdigt och du kan anv�nda din sida.

Debug
-----

I config.php som finns i site mappen kan du st�lla in om du vill visa debug p� din sida om du skulle vilja fels�ka n�got. D�r kan du se allt som h�nder och inneh�ll i filer o s v. Detta aktiverar du Genom att s�tta f�ljande v�rden p� `True`.

    /**
     * Set what to show as debug or developer information in the get_debug() theme helper.
     */
    $ma->config['debug']['malin'] = false;
    $ma->config['debug']['session'] = false;
    $ma->config['debug']['timer'] = false;
    $ma->config['debug']['db-num-queries'] = false;
    $ma->config['debug']['db-queries'] = false;

Skriva ett blogginl�gg
----------------------

Skriva ett blogginl�gg kan du g�ra om du �r administrat�r. D� kommer du �t denna funktion antingen genom att klicka p� acp uppe i h�gra h�rnet och sedan create content, eller n�r du redigerar ett existerande inl�gg klickar du p� create new. N�r du g�r ett nytt inl�gg ska du fylla i f�ljande rutor:

* Title: H�r skriver du inl�ggets/sidans titel.
* key: En kort l�nktext f�r inl�gget/sidan
* Content: Inl�ggets/sidans inneh�ll
* Filter: I filter skriver du hur inneh�llet ska visas upp. du kan v�lja p� htmlpurify, bbcode och plain. Vad som �r skillnaden p� dessa kan du se p� exempelinl�gg som kommer med vid installation av databasen.
* Type: H�r fyller du i vad f�r sorts typ inl�gget/sidan ska vara. I detta fallet ska det vara post. F�r mer avancerade anv�ndare kan du skriva page h�r och anv�nda inl�gget som en webbsida.
