Internationalisation with FINE
==============================

Fine is a PHP internationalisation package. It will help you develop applications that support several languages.
FINE means: Fine is not English :).

Translation is performed using PHP mapping files, but Fine is a [http://mouf-php.com](Mouf package).
This means you will have a nice graphical interface inside the Mouf framework to write your own translated messages.

In this document, we will describe how to use FINE to support several languages in your application.

Installing Fine
---------------

Fine is a Mouf package. It means you can easily install it using Mouf installer, or simply by adding a composer dependency on your project.

Tutorial
--------

###Using the Fine User Interface

Out of the box, Fine uses the "browser" default language to decide in which language the message should be displayed (We will see later how to change this behaviour).
If the language is not available (for instance if the browser language is "Chinese", but if there is no chinese translataion, Fine will use the "default" language.
The "Supported languages" menu will help you add new supported languages:
![FINE Supported Languages screen](https://raw.github.com/thecodingmachine/utils.i18n.fine/3.0/doc/images/supportedLanguages.jpg)

By clicking on the "Find Missing Labels" menu, a screen listing all existing labels will be displayed:
![FINE Missing labels screen](https://raw.github.com/thecodingmachine/utils.i18n.fine/3.0/doc/images/missingLabels.jpg)

On this page, the list of all translated labels is shown in a table. There is one column for each language.
In the sample screenshot, there are 2 supported languages: the default language and French. On this screen,
we can see that we forgot to provide a valid translation for the label "login.password" in French.

We can use this screen to add new labels too.

###Using Fine in your PHP code

Adding new translated messages is very useful, but we still need to be able to display them in the correct language.
Fine defines 2 useful functions: <em>eMsg</em> or <em>iMsg</em>.
<p><em>eMsg</em> will display the translated label in the output. For instance:</p>
<pre>
// This function will display the "login.password" label in the browser's language.
eMsg("login.password");
</pre>
<p><em>iMsg</em> is similar to <em>eMsg</em> excepts it returns the label instead of displaying it. For instance:</p>
<pre>
$passwordLbl = iMsg("login.password");
</pre>

<h2>Labels with parameters</h2>
<p>Labels can contain parameters. In this case, parameters will be inserted at runtime, when calling the <em>iMsg</em> or <em>eMsg</em> functions.
For instance:</p>
<pre>
// The label you defined
form.invalidMail="Error. {0} is not a valid mail."

// How to call the eMsg function. 
eMsg("form.invalidMail", $mail).
</pre>
<p>The <code>{0}</code> label will be dynamically replaced with the "$mail" variable. Of course, you can put {1}, {2}, {3}... in your labels and pass additional parameters to <em>iMsg</em> or <em>eMsg</em> function.</p>






How it works
------------

Internally, FINE deals with 2 kinds of objects:
- *Translation services* (objects implementing the LanguageTranslationInterface) are objects that can translate a string from one language to another.
- *Language detectors* (objects implementing the LanguageDetectionInterface) are objects that are in charge of finding what language the user knows.

TODO: continue here!

// TODO: coller ça plus tard!
The Fine install process will create a number of instances for you:
- translationService: this is an instance of the FinePHPArrayTranslationService. This instance is the object you will call to get translations.

For instance:
```php

``` 

<p>Click on the link to create the new instance of FinePHPArrayTranslationService. The name must be "translationService".</p>
<p>After it, the FinePHPArrayTranslationService component needs 2 properties:</p>

<ul>
	<li>i18nMessagePath: folder of the file where the translation is stored;</li>
	<li>languageDetection: create an instance to detect the language</li>
</ul>
<img src="images/mouf_translationService.png" alt="" />

<p>If you use the domaineLanguageDetection, you must add value to the array. There are 2 values:
	<ul>
		<li>domain: name domain. Example: www.thecodingmachine.com;</li>
		<li>value: only code language. Exemple: en</li>
	</ul>
</p>
<img src="images/mouf_domainelanguagedetection.png" alt="" />

<p>In the administration, you should see 3 new menus in the Mouf User Interface:</p>
<img src="images/fineMenu.jpg" alt="" />



<h2>Dynamically translating your code</h2>

<p>Fine has a very nice feature called "automated message translation". You can enable or disable this mode using the "Enable/Disable translation" menu.</p>
<img src="images/enableDisableTranslation.jpg" alt="" />
<p>When this mode is enabled, in your application, all labels will have a trailing "edit" link. By clicking on this link, you will be directed to the "translation" page.</p>

<p>A normal page (translation disabled)</p>
<img src="images/translationDisabled.jpg" />
<p>A page with translation enabled</p>
<img src="images/translationEnabled.jpg" />

<h2>Where are messages stored</h2>

<p>All your translated messages are stored in the /resources directory of your project.</p>
<p>The translated messages are stored as PHP files. <b>message.php</b> contains the messages for the default language. <b>message_fr.php</b> will contain the
language translations for French, etc...</p>

Best practices
--------------

All your application's labels will be stored together. Since an application can contain thousands of labels, it can quickly become a mess.
In order to keep labels organized, we recommend to organize labels using a "suffix". For instance, all labels
related to the login screen could start with "login.".
The login labels would therefore look like this:

- login.login
- login.password
- login.loginbutton
- login.welcome
- login.error
- ...

Only very broad and common labels (like "yes", "no", "cancel"...) should have no prefix.

<h2>Advanced features: translation</h2>

<p>With the FinePHPArrayTranslationService class, you can translate each component separately. You should see 2 new menus in the right. They work like the same link to the left</p>
<img src="images/mouf_translate.png" alt="" />