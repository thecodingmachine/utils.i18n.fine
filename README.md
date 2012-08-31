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

In the Mouf administration, you should see 3 new menus in the Mouf User Interface:
![FINE menu](https://raw.github.com/thecodingmachine/utils.i18n.fine/3.0/doc/images/fineMenu.jpg)

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
Fine defines 2 useful functions: *eMsg* or *iMsg*.
*eMsg* will display the translated label in the output. For instance:

```php
// This function will display the "login.password" label in the browser's language.
eMsg("login.password");
```

*iMsg* is similar to *eMsg* excepts it returns the label instead of displaying it. For instance:
```php
$passwordLbl = iMsg("login.password");
```

Labels with parameters
----------------------

Labels can contain parameters. In this case, parameters will be inserted at runtime, when calling the *iMsg* or *eMsg* functions.
For instance:

```php
// The label you defined
form.invalidMail="Error. {0} is not a valid mail."

// How to call the eMsg function. 
eMsg("form.invalidMail", $mail).
```

The <code>{0}</code> label will be dynamically replaced with the "$mail" variable. Of course, you can put {1}, {2}, {3}... in your labels and pass additional parameters to *iMsg* or *eMsg* function.


How it works
------------

Internally, FINE deals with 2 kinds of objects:
- *Translation services* (objects implementing the LanguageTranslationInterface) are objects that can translate a string from one language to another.
- *Language detectors* (objects implementing the LanguageDetectionInterface) are objects that are in charge of finding what language the user knows.

When you use the *iMsg* or *eMsg* functions, FINE actually access the *translationService* instance defined in Mouf.
This instance is the default service used to translate strings (it is an instance of the FinePHPArrayTranslationService class).
The *translationService* contains the path of the translations. By default, these are stored in "/resources".
The *translationService* accesses the *defaultLanguageDetection* instance to know what language it should use for the translation.

Here is the translationService instance:
![FINE translationService](https://raw.github.com/thecodingmachine/utils.i18n.fine/3.0/doc/images/mouf_translationService.png)

By default, the *defaultLanguageDetection* is a BrowserLanguageDetection class, that analyses the language of the browser.
You can of course change that. For instance the domainLanguageDetection can be used to define the language based on the domain name of the website.

If you use the domaineLanguageDetection, you must add value to the array. There are 2 values:
- domain: name domain. Example: www.thecodingmachine.com;
- value: only code language. Exemple: en

![FINE translationService](https://raw.github.com/thecodingmachine/utils.i18n.fine/3.0/doc/images/mouf_domainelanguagedetection.png)


Dynamically translating your code
---------------------------------

Fine has a very nice feature called "automated message translation". You can enable or disable this mode using the "Enable/Disable translation" menu.
![FINE enable/disable translation](https://raw.github.com/thecodingmachine/utils.i18n.fine/3.0/doc/images/enableDisableTranslation.jpg)

When this mode is enabled, in your application, all labels will have a trailing "edit" link. By clicking on this link, you will be directed to the "translation" page.

A normal page (translation disabled)
![FINE translation disabled](https://raw.github.com/thecodingmachine/utils.i18n.fine/3.0/doc/images/translationDisabled.jpg)
<img src="images/translationDisabled.jpg" />
A page with translation enabled
![FINE translation enabled](https://raw.github.com/thecodingmachine/utils.i18n.fine/3.0/doc/images/translationEnabled.jpg)

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

Where are messages stored
-------------------------

All your translated messages are stored in the /resources directory of your project.
The translated messages are stored as PHP files. <b>message.php</b> contains the messages for the default language. <b>message_fr.php</b> will contain the
language translations for French, etc...
If your translation key contains a dot (for instance login.password), FINE creates a special file containing all "login.*" keys.

Advanced features: translation
------------------------------

Instead of using *iMsg* and *eMsg* functions, you can use directly the *translationService*. You can create many instances
of classes implementing the *LanguageTranslationInterface*. If you are developping modules, this is useful to store the
translations of your module right in your module, without messing with the user's main tranlsation files.
