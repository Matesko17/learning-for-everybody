# QetteWeb
Základní web pro většinu Q2 webů a webových aplikací.

## Instalace
* nový projekt - následujícím příkazem, kde místo `__složka__` vyplníte název neexistující složky do které se QetteWeb nainstaluje
```
composer create-project q2/qetteweb --repository-url=http://satis.q2cz.local --no-secure-http --remove-vcs __složka__
```
* existující naklonovaný projekt z repozitáře - spustit
 ```
 composer install
 ```

---

## Nastavení
  * nastavení názvu a popisu projektu v `composer.json` a `package.json`
  * nastavení htaccess (min. `RewriteBase`)
  * nastavení databáze  
    - `config-development.neon/config-stage.neon/config.neon`
    - `doctrine` a `dibi`
  * nastavení parametrů `parameters.neon`  
    - prefix pro tabulky `tb_prefix`
    - povolené jazyky `allowLangs`
    - základní SEO `homeTitle, homeDesc, titleSuffix`  
  * nastavení session name (config.neon)
    - `session: name:`
  * vygenerování tabulek  
    - `php www/index.php orm:schema-tool:update --dump-sql` pro vypis změn v DB struktuře  
    - `php www/index.php orm:schema-tool:update --force` pro zápis změn v DB struktuře

---

## JavaScripty a Sass (CSS) styly
Kompilují se přes [Gulp](https://gulpjs.com) příkazy:
* `gulp` - pro vývojovou verzi
* `gulp stage` - pro stage verzi
* `gulp production` - pro produkční verzi

---

## Skripty
Ve složce `bin` jsou připraveny skripty pro různé akce.

Spouští se v terminálu z hlavní složky QetteWebu takto:

`bash bin/__skript__.sh`

### Soubory
* `build.sh` a `post-build.sh` - pro spouštění v Jenkins build procesích
* `database.sh` - pro práci s databází a generování databáze z entit 
* `folders.sh` - pro vytváření a nastavení práv složek pro nahrávání souborů
* `tests.sh` - pro spuštění testů z `tests` složky
* `translations.sh` - vytvoří a vygeneruje překlady  

---

## Příkazy
* vygenerování tabulek z entit do dtatabáze
    - `php www/index.php orm:schema-tool:update --dump-sql` - pro vypis změn v DB struktuře  
    - `php www/index.php orm:schema-tool:update --force` - pro zápis změn v DB struktuře
* překlady
    * `php www/index.php app:translation -c` - zapíše překlady z neon souborů do databáze
    * `php www/index.php app:translation -g` - vezme překlady z databáze a zapíše je do `www/locale/*.neon`
    * `php www/index.php app:translation -i "www/files/translation.csv"` - importuje překlady z definovaného souboru do databáze

---

## Struktura
Platí pravidlo, že soubory ve složkách `core` se neupravují, mohou se rozšířit.

  * [app](#app) (V) - složka pro aplikační soubory
  * [assets](#assets) (NV) - složka pro front devel soubory
  * [bin](#bin) - složka pro skripty
  * [log](#log) (V, P) - složka pro logy
  * [log-cli](#log-cli) (V) - složka pro konzolové logy
  * [node_modules](#node_modules) (NV) - složka s knihovnami pro vývoj, nebo pro front
  * [sql](#sql) (NV) - složka pro sql soubory
  * [temp](#temp) (V, P) - složka pro dočasné soubory (cache)
  * [temp-cli](#temp-cli) (V) - složka pro dočasné soubory konzole
  * [tests](#temp-cli) (NV)
  * [vendor](#vendor) (V) - složka pro závislosti pro backend
  * [www](#www) (V) - složka pro front webu
  * [root soubory](#root-soubory)

\* V - vypouští se, NV - nevypouští se, P - po vypuštění je nutno nastavit na složky (někdy i podsložky) práva (většinou 775)

---

### <a name="app">app</a>
Hlavní složka pro aplikační soubory.

  * [AdminModule](#admin-module)  
  \- budoucí administrace  
  \- router na složku jako `qred/`
  * [components](#components) (App\Components) - obsauje komponenty  
  &nbsp;&nbsp;| \- <b>core</b>  
  &nbsp;&nbsp;|&nbsp;&nbsp;| \- [Css](#component-css) - vykresluje styly, stará se o verzování  
  &nbsp;&nbsp;|&nbsp;&nbsp;| \- [GaCode](#component-ga-code) - vykresluje měřící kód GA  
  &nbsp;&nbsp;|&nbsp;&nbsp;| \- [GtmCode](#component-gtm-code) - vykresluje měřící kód GTM  
  &nbsp;&nbsp;|&nbsp;&nbsp;| \- [Js](#component-js) - vykresluje javascript, stará se o verzování  
  &nbsp;&nbsp;|&nbsp;&nbsp;| \- [LanguageSwitch](#component-language-switch) - vykresluje přepínač jazyků  
  &nbsp;&nbsp;|&nbsp;&nbsp;| \- [Menu](#component-menu) - stará se o výpis menu  
  &nbsp;&nbsp;|&nbsp;&nbsp;| \- [SlugCreator](#component-slug-creator) - stará se o "hezkou" url  
  &nbsp;&nbsp;|&nbsp;&nbsp;| \- [Text](#component-text) - vypisuje textové bloky  
  * [config](#config)  
  &nbsp;&nbsp;| \- <b>core</b>  
  &nbsp;&nbsp;|&nbsp;&nbsp;| \- [config.neon](#config-core-config) - nastavení pro php, application, session a tracy  
  &nbsp;&nbsp;|&nbsp;&nbsp;| \- [console.neon](#config-core-console) - nastavení pro konzoli (console)  
  &nbsp;&nbsp;|&nbsp;&nbsp;| \- [extension.neon](#config-core-extension) - nastavení pro rozšíření (extensions)  
  &nbsp;&nbsp;|&nbsp;&nbsp;| \- [parameters.neon](#config-core-parameters) - parametry pro aplikaci  
  &nbsp;&nbsp;|&nbsp;&nbsp;| \- [service.neon](#config-core-services) - nastavení pro služby (services)  
  &nbsp;&nbsp;| \- [config-development.neon](#config-config-devel) - nastavení pro vývojové (development) prostředí  
  &nbsp;&nbsp;| \- [config-stage.neon](#config-config-stage) - nastavení pro stage prostředí  
  &nbsp;&nbsp;| \- [config.neon](#config-config) - vlastní nastavení pro php, application, session a tracy  
  &nbsp;&nbsp;| \- [console.neon](#config-console) - vlastní nastavení pro konzoli (console)  
  &nbsp;&nbsp;| \- [extensions.neon](#config-extensions) - vlastní nastavení pro rozšíření (extensions)  
  &nbsp;&nbsp;| \- [parameters.neon](#config-parameters) - vlastní parametry pro aplikaci  
  &nbsp;&nbsp;| \- [service.neon](#config-services) - vlastní nastavení pro služby (services)  
  * [extensions](#extensions) (App\Extensions) - obsahuje vlastní rozšíření (extensions)  
  &nbsp;&nbsp;| \- <b>core</b>  
  &nbsp;&nbsp;|&nbsp;&nbsp; \-TranslationProviderExtension - rozšíření pro práci s překlady  
  * [interfaces](#interfaces) - obsahuje vlastní rozšíření (extensions)  
  &nbsp;&nbsp;| \- <b>core</b>  
  &nbsp;&nbsp;|&nbsp;&nbsp; \-IInvalidateCache - interface pro IInValidatecache  
  * [locale](#locale) - složka pro překladové soubory  
  * [model](#model) - obsahuje soubory, které tvoří databázový model aplikace  
  &nbsp;&nbsp;| \- <b>core</b>  
  &nbsp;&nbsp;|&nbsp;&nbsp;| \-[facades](#facades-core) (App\Model\Facades)  
  &nbsp;&nbsp;|&nbsp;&nbsp;|&nbsp;&nbsp; \-[MenuFacade](#facades-core-menu)  
  &nbsp;&nbsp;|&nbsp;&nbsp;|&nbsp;&nbsp; \-[PageFacade](#facades-core-page)  
  &nbsp;&nbsp;|&nbsp;&nbsp;|&nbsp;&nbsp; \-[SiteSettingFacade](#facades-core-site-setting)  
  &nbsp;&nbsp;|&nbsp;&nbsp;|&nbsp;&nbsp; \-[TextFacade](#facades-core-text)  
  &nbsp;&nbsp;|&nbsp;&nbsp;|&nbsp;&nbsp; \-[TranslationFacade](#facades-core-translation)  
  &nbsp;&nbsp;|&nbsp;&nbsp;| \-[orm](#orm-core) (App\Model\Entities)  
  &nbsp;&nbsp;|&nbsp;&nbsp;|&nbsp;&nbsp;| \-[AdminLog](#orm-core-admin-log) - entita pro logování událostí v administraci 
  &nbsp;&nbsp;|&nbsp;&nbsp;|&nbsp;&nbsp;| \-[Language](#orm-core-language) - entita pro jazyky  
  &nbsp;&nbsp;|&nbsp;&nbsp;|&nbsp;&nbsp;| \-[Menu](#orm-core-menu) - entita pro menu  
  &nbsp;&nbsp;|&nbsp;&nbsp;|&nbsp;&nbsp;| \-[MenuItem](#orm-core-menu-item) - entita pro položky menu  
  &nbsp;&nbsp;|&nbsp;&nbsp;|&nbsp;&nbsp;| \-[Page](#orm-core-page) - entita pro textové stránky  
  &nbsp;&nbsp;|&nbsp;&nbsp;|&nbsp;&nbsp;| \-[Route](#orm-core-route) - entita pro routy  
  &nbsp;&nbsp;|&nbsp;&nbsp;|&nbsp;&nbsp;| \-[RouteAlias](#orm-core-route-alias) - entita pro aliasy rout  
  &nbsp;&nbsp;|&nbsp;&nbsp;|&nbsp;&nbsp;| \-[RouteSeo](#orm-core-route-seo) - entita pro nastavení seo routy  
  &nbsp;&nbsp;|&nbsp;&nbsp;|&nbsp;&nbsp;| \-[SiteSettings](#orm-core-site-settings) - entita pro nastavení webové stránky  
  &nbsp;&nbsp;|&nbsp;&nbsp;|&nbsp;&nbsp;| \-[Text](#orm-core-text) - entita pro textové bloky  
  &nbsp;&nbsp;|&nbsp;&nbsp;|&nbsp;&nbsp;| \-[Translation](#orm-core-translation) - entita pro překlady  
  &nbsp;&nbsp;| \-[facades](#facades) - vlastní facady  
  &nbsp;&nbsp;| \-[orm](#orm) - vlastní entity  
  * [presenters](#presenters) (App\Presenters) - presentery a šablony  
  &nbsp;&nbsp;| \-<b>core</b>  
  &nbsp;&nbsp;|&nbsp;&nbsp;| \-<b>presenters</b>  
  &nbsp;&nbsp;|&nbsp;&nbsp;|&nbsp;&nbsp;| \-[AbstractError4xxPresenter.php](#abstract-error4-presenter) - abstraktní presenter pro Error4xx  
  &nbsp;&nbsp;|&nbsp;&nbsp;|&nbsp;&nbsp;| \-[AbstractErrorPresenter.php](#abstract-error-presenter) - abstraktní presenter pro Error  
  &nbsp;&nbsp;|&nbsp;&nbsp;|&nbsp;&nbsp;| \-[AbstractPagePresenter.php](#abstract-page-presenter) - abstraktní presenter pro Page  
  &nbsp;&nbsp;|&nbsp;&nbsp;|&nbsp;&nbsp;| \-[AbstractQwPresenter.php](#abstract-qw-presenter) - abstraktní presenter pro Base  
  &nbsp;&nbsp;|&nbsp;&nbsp;|&nbsp;&nbsp;| \-[AbstractSecurityPresenter.php](#abstract-security-presenter) - abstraktní presenter pro SecurityBase  
  &nbsp;&nbsp;| \-[templates](#templates) - šablony  
  &nbsp;&nbsp;|&nbsp;&nbsp;| \-<b>Error</b> - šablony pro Error4xxPresenter a ErrorPresenter  
  &nbsp;&nbsp;|&nbsp;&nbsp;| \-<b>Homepage</b> - šablona pro HomepagePresenter  
  &nbsp;&nbsp;|&nbsp;&nbsp;| \-<b>Page</b> - šablony pro PagePresenter, default a detail  
  &nbsp;&nbsp;|&nbsp;&nbsp;| \-[@layout.latte](#latte-layout) - hlavní šablona pro webovou aplikaci  
  &nbsp;&nbsp;|&nbsp;&nbsp;| \-[footer.latte](#latte-footer) - šablona pro patičku webu (footer)  
  &nbsp;&nbsp;|&nbsp;&nbsp;| \-[header.latte](#latte-header) - šablona pro záhlaví webu (header)  
  &nbsp;&nbsp;|&nbsp;&nbsp;| \-[paginator.latte](#latte-paginator) - šablona pro stránkování  
  &nbsp;&nbsp;| \-[BasePresenter.php](#base-presenter) - hlavní presenter aplikace  
  &nbsp;&nbsp;| \-[Error4xxPresenter.php](#error4xx-presenter) - presenter pro obsluhu 4xx errorů  
  &nbsp;&nbsp;| \-[ErrorPresenter.php](#error-presenter) - presenter pro obsluhu erroru  
  &nbsp;&nbsp;| \-[HomepagePresenter.php](#hp-presenter) - presenter obsluhující homepage  
  &nbsp;&nbsp;| \-[PagePresenter.php](#page-presenter) - presenter pro obsluhu textových stránek  
  &nbsp;&nbsp;| \-[SecurityBasePresenter.php](#security-presenter) - presenter pro obsluhu přihlašování  
  * <b>router</b>  
  &nbsp;&nbsp;| \-[RouterFactory.php](#router-factory) - vytváření router pro aplikaci  
  * <b>services</b> (App\Services)  
  &nbsp;&nbsp;| \-<b>core</b>  
  &nbsp;&nbsp;|&nbsp;&nbsp;| \-[DatabaseRouter](#services-database-router) - služba pro databázový router  
  &nbsp;&nbsp;|&nbsp;&nbsp;| \-[LanguageService](#services-language) - služba pro jazyky a překlady (odstraní se)  
  &nbsp;&nbsp;|&nbsp;&nbsp;| \-[SiteSettingsService](#services-site-settings) - služba pro nastavení webové aplikace  
  &nbsp;&nbsp;|&nbsp;&nbsp;| \-[StaticRouter](#services-static-router) - služba pro statický router  
  &nbsp;&nbsp;|&nbsp;&nbsp;|\- [TranslatorService](#services-translator) - služba pro překlady  
  * <b>subscribers</b>  
  &nbsp;&nbsp;| \-TablePrefixSubscriber.php - stará se o doplnění prefixu k entitám  
  * [bootstrap.php](#bootstrap) - stará se o načtení závislostí a knihoven, načtení configu  

---

## <a name="admin-module">AdminModule</a>
TODO

### Skripty na URL
* `qred/translator/create` - zapíše překlady z neon souborů do databáze
* `qred/translator/generate` - vezme překlady z databáze a zapíše je do `www/locale/*.neon`
* `qred/translator/import?file=translation.csv` - import překladů ze souboru uloženého v `www/files` do databáze

---

## <a name="components">components</a>  

### <a name="component-css">Css</a>  

#### Popis  
Komponenta pro výpis a verzování stylopisu, obsahuje:  
* css.latte
* CssControl.php  

#### Registrace  
services.neon - `cssControl: App\Components\CssControl('%wwwDir%', '%cssControl%', '%environment%')`  
parameters.neon - `cssControl: front: files: - 'css/styles'`  

#### Použití  
Defaultně v AbstractQwPresenter.php  
```php
use App\Components\CssControl;

/** @var CssControl @inject */
    public $cssControl;

/**
 * @return CssControl
 */
public function createComponentCssControl() {
    $menuControl = $this->menuControlFactory->create();
    return $this->cssControl;
}
```
@layout.latte - `{control cssControl, 'front'}`  

### <a name="component-ga-code">GaCode</a>   

#### Popis
Komponenta pro výpis GA měřícího kódu, obsahuje:  
* GaCodeControl.php  

#### Registrace  
services.neon - `gaCodeControl: App\Components\GaCodeControl(@siteSettings)`  
parameters.neon - `ga: ""`

#### Použití
Defaultně v AbstractQwPresenter.php  
```php
use App\Components\GaCodeControl;

/** @var GaCodeControl @inject */
    public $gaCodeControl;

/**
 * google analytics control
 * @return GaCodeControl
 */
protected function createComponentGaCode() {
    return $this->gaCodeControl->setLang($this->locale);
}
```
@layout.latte - `{control gaCode}`  

### <a name="component-gtm-code">GtmCode</a>  

#### Popis
Komponenta pro výpis GTM měřícího kódu, obsahuje:  
* GtmCodeControl.php  

#### Registrace  
services.neon - `gtmCodeControl: App\Components\GtmCodeControl(@siteSettings)`  
parameters.neon - `gtm: ""`

#### Použití
Defaultně v AbstractQwPresenter.php  
```php
use App\Components\GtmCodeControl;

/** @var GtmCodeControl @inject */
    public $gtmCodeControl;

/**
 * google tag manager control
 * @return GtmCodeControl
 */
protected function createComponentGoogleTagManager() {
    return $this->gtmCodeControl;
}
```
@layout.latte  
- `<body>{control googleTagManager:body} ...`  
- `<head>{control googleTagManager:head} ...`  

### <a name="component-js">Js</a>  

#### Popis  
Komponenta pro výpis a verzování javscriptu, obsahuje:  
* js.latte
* JsControl.php  

#### Registrace  
services.neon - `jsControl: App\Components\JsControl('%wwwDir%', '%jsControl%', '%environment%')`  
parameters.neon - `jsControl: front: files: - 'js/scripts'`  

#### Použití  
Defaultně v AbstractQwPresenter.php  
```php
use App\Components\JsControl;

/** @var JsControl @inject */
    public $jsControl;

/**
 * @return JsControl
 */
protected function createComponentJsControl() {
    return $this->jsControl;
}
```
@layout.latte - `{control jsControl, 'front'}`  

### <a name="component-language-switch">LanguageSwitch</a>  

#### Popis  
Komponenta pro výpis a přepínání jazyků, obsahuje:  
* LanguageSwitch.latte  
* LanguageSwitchControl.php  
* ILanguageSwitchControlFactory.php  

#### Registrace  
services.neon - `languageSwitchControlFactory: App\Components\ILanguageSwitchControlFactory`  
nastavení povolených jazyků - parameters.neon - `siteSettings: allowLang:`  

#### Použití  
Defaultně v AbstractQwPresenter.php  
```php
use App\Components\ILanguageSwitchControlFactory;

/** @var ILanguageSwitchControlFactory @inject */
    public $languageSwitchControlFactory;

/**
 * language switch control
 * @return LanguageSwitchControl
 */
protected function createComponentLanguageSwitch() {
    return $this->languageSwitchControlFactory->create();
}

```  
`{control languageSwitch}`  

### <a name="component-menu">Menu</a>  

#### Popis  
Komponenta pro výpis menu, obsahuje:  
* Menu.latte  
* MenuControl.php  

#### Registrace  
services.neon - `menuControlFactory: App\Components\IMenuControlFactory`  

#### Použití  
Defaultně v AbstractQwPresenter.php  
```php
use App\Components\IMenuControlFactory;

/** @var IMenuControlFactory @inject */
    public $menuControlFactory;

/**
 * menu control (use {control menu 'main'})
 * @return MenuControl
 */
protected function createComponentMenu() {
    return $this->menuControlFactory->create();
}

```  
`{control menu 'IDENT', 'CLASS'}`  
IDENT - ident menu které chci vykreslit  
CLASS - možnost nastavit na menu vlastní classy

### <a name="component-slug-creator">SlugCreator</a>  

#### Popis  
Komponenta pro zápis slugu z latte do databáze, obsahuje:  
* SlugCreatorControl.php  

#### Registrace  
services.neon - `slugCreatorControl: App\Components\SlugCreatorControl`  

#### Použití  
Defaultně v AbstractQwPresenter.php  
```php
use App\Components\SlugCreatorControl;

/** @var SlugCreatorControl @inject */
    public $slugCreatorControl;

/**
 * slug generator control for filling database router
 * @return SlugCreatorControl
 */
protected function createComponentSlugCreator() {
    return $this->slugCreatorControl;
}

```  
V xxx.latte kde nastavuji slug
{block slug}{SLUG}{/block}

V @layout.latte pro zpracování slug blocku
```php
{* zpracovani slugu pro db router *}
{if $presenter->context->parameters['router']['database']}
    {ifset #slug}
        {capture $slug}{include #slug}{/capture}
        {control slugCreator $slug}
    {/ifset}
{/if}
```

### <a name="component-text">Text</a>  

#### Popis  
Komponenta pro práci s textovými bloky, obsahuje:  
* AbstractTextControl.php  
* TextControl.php  
Vytváří entitu Text, s překlady pro všechny povolené jazyky.

#### Registrace  
services.neon - `textControlFactory: App\Components\ITextControlFactory`  

#### Použití  
Defaultně v AbstractQwPresenter.php  
```php
use App\Components\ITextControlFactory;

/** @var ITextControlFactory @inject */
    public $textControlFactory;

/**
 * html text control (use {control text:title 'ident'})
 * @return TextControl
 */
protected function createComponentText() {
    return $this->textControlFactory->create();
}

```  
Jazyk se bere ten, který je nastavený v aplikaci jako aktuálně aktivní  
Pro výpis nadpisu textového bloku:  
`{control text:title 'IDENT'}`  
Pro výpis obsahu textového bloku:  
`{control text:content 'IDENT'}`

---

## <a name="config">config</a>  

### core  

#### <a name="config-core-config">config.neon</a>  
Nastavení pro:  
php (date.timezone)  
application (debuger, errorPresenter, mapping (`*: App\*Module\Presenters\*Presenter`))  
tracy

#### <a name="config-core-console">console.neon</a>  
Nastavení pro konzoli:
```
translationCommand:
        class: App\AdminModule\Console\TranslationCommand
        tags: [kdyby.console.command]
```

#### <a name="config-core-extension">extensions.neon</a>  
Základní rozšíření  
```
dibi: Dibi\Bridges\Nette\DibiExtension22 (v další verzi půjde pryč)
console: Kdyby\Console\DI\ConsoleExtension
events: Kdyby\Events\DI\EventsExtension
annotations: Kdyby\Annotations\DI\AnnotationsExtension
doctrine: Kdyby\Doctrine\DI\OrmExtension
translation: Kdyby\Translation\DI\TranslationExtension
translationProvider: App\Extensions\TranslationProviderExtension
antispam: Zet\AntiSpam\AntiSpamExtension
translatable: Zenify\DoctrineBehaviors\DI\TranslatableExtension
timestampable: Zenify\DoctrineBehaviors\DI\TimestampableExtension
```
Nastavení metadat pro doctrinu  
`doctrine: metadata: App: %appDir%`  
Nastavení pro překlady  
`translationProvider: dir: ['%rootDir%/www/', '%rootDir%/vendor/', '%appDir%/']`  
`translatable: currentLocaleCallable: [@translation.default, getLocale]`

#### <a name="config-core-parameters">parameters.neon</a>  
Základní parametry pro aplikaci  
pro `plurals: cs, en, de`  
cesta a název souboru pro kompilované js scripty `jsControl: front: files: - js/scripts`  
 cesta a název souboru pro kompilované css soubory `cssControl: front: files: - css/styles`  
tabulka pro routu (vyžaduje stará verze databázového routingu) `tb_route: "%tb_prefix%route"` (půjde pryč v další verzi)  

#### <a name="config-core-services">services.neon</a>  
Základní služby  
```
slugCreatorControl: App\Components\SlugCreatorControl
jsControl: App\Components\JsControl('%wwwDir%', '%jsControl%', '%environment%')
cssControl: App\Components\CssControl('%wwwDir%', '%cssControl%', '%environment%')
gaCodeControl: App\Components\GaCodeControl(@siteSettings)
gtmCodeControl: App\Components\GtmCodeControl(@siteSettings)
textControlFactory: App\Components\ITextControlFactory 
menuControlFactory: App\Components\IMenuControlFactory
languageSwitchControlFactory: App\Components\ILanguageSwitchControlFactory
textFacade: App\Model\Facades\TextFacade(%siteSettings.allowLang%)
pageFacade: App\Model\Facades\PageFacade
menuFacade: App\Model\Facades\MenuFacade
siteSettingFacade: App\Model\Facades\SiteSettingFacade
translationFacade: App\Model\Facades\TranslationFacade
databaseRouterModel: App\Services\DatabaseRouterModel(%tb_route%)
siteSettings: App\Services\SiteSettingService(%siteSettings%, %tempDir%)
routerFactory: App\RouterFactory
router: @App\RouterFactory::createRouter()
seo:
    class: App\Services\DatabaseRouterSeoModel(%tb_route%)
    autowired: no
staticLanguageService: App\Services\StaticLanguageService(@siteSettings)
translationService: App\AdminModule\Services\TranslationService('%wwwDir%/locale/')
tablePrefix:
    class: App\Subscribers\TablePrefixSubscriber(%tb_prefix%)
    tags: [kdyby.subscriber]
```

### <a name="config-config-devel">config-development.neon</a>  
Nastavení aplikace pro vývojové prostředí  
Cache: `cacheStorage: class: Nette\Caching\Storages\DevNullStorage`  
Nastavení pro DB (doctrine, dibi)  
Nastavení pro mail  

### <a name="config-config-stage">config-stage.neon</a>  
Nastavení aplikace pro stage prostředí  
Cache: `cacheStorage: class: Nette\Caching\Storages\DevNullStorage`  
Nastavení pro DB (doctrine, dibi)  
Nastavení pro mail

### <a name="config-config">config.neon</a>  
Nastavení session  
```
session:
    autoStart: true  # vychozi je smart
    expiration: 14 days
    name: qetteweb
```  

### <a name="config-console">console.neon</a>  
Nastavení pro práci s konzolí

### <a name="config-extensions">extensions.neon</a>  
Nastavení rozšíření v aplikaci
Nastavení pro DB (doctrine, dibi)  
Nastavení pro mail  
Url pro konzoli  
Nastavení pro překlady  

### <a name="config-parameters">parameters.neon</a>  
Nastavení základních parametrů pro aplikaci  
Šifrovaná komunikace (https)  
Nastavení pro router  
```
router:
        database: true  # enable database router
        seo: false      # enable router seo extension

        # enable domain language translation
        languageDomainSwitch: false
        languageDomainAlias:
            cz: cs
            com: en
```  
Prefix pro tabulky `tb_prefix: "xxx_"`  
Sitesettings
```
  mainLang: "cs" #hlavní jazyk pro aplikaci
          
  allowLang: #povolené jazyky pro aplikaci (odtud si bere nastavení překladač, textové bloky etc.)
      "cs": "Čeština"
      "en": "English"

  aliasLang: #alias pro jazyk který není povolený, ale chceme ho zobrazovat s jinou lokalizací
      de: "en"
      sk: "cs"
      fr: "en"

  homeTitle: Qetteweb #základní seo, title pro hompeage
  homeDesc: Qetteweb default nastavení #základní seo, description pro homepage

  separator: " | " #separator pro title stránek

  ga: "" #případný GA kód
  gtm: "" #případný GTM kód

  titleSuffix: Qetteweb #základní seo, title pro všechyn stránky (xxx separator titleSuffix -> Kontakt | QetteWeb)

  headAuthor: "Q2" #nastavení autora v hlavičce html dokumentu
  headRobots: "all,follow" #nastavení pro robots v hlavičce html dokumentu
```

### <a name="config-service">services.neon</a>  
Nastavení služeb pro aplikaci  

---

## <a name="model">model</a>  

## core  

## <a name="facades-core">facades</a>  

### <a name="facades-core-menu">MenuFacade</a>  
Fasáda pro práci s menu.  
Pracuje s entitami <a name="orm-core-menu">Menu</a> a <a name="orm-core-menu-item">MenuItem</a>.  
#### Dostupně funkce:  
  *  `public function getMenuByIdent($ident, $lang)`  
  `$ident - [string]` ident menu, které se má vybrat  
  `$lang - [string]` jazyk vybíraného menu  
  Funkce pro získání menu pomocí metody `buildTree`
  *  `private function buildTree($elements, $parentId = 0)`  
  `$elements - [array]` prvky menu, které se prochází pro rekurzivně kvůli zanoření  
  `$parentId - [integer]` id rodičovského prvku  
  Funkce pro získání stromu menu pomocí rekurze

### <a name="facades-core-page">PageFacade</a>  
Fasáda pro práci s textovými stránkami.
Pracuje s entitou <a name="orm-core-page">Page</a>  
#### Dostupné funkce:
  *  `public function getPageByIdent($ident)`  
  `$ident - [string]` ident stránky, která se má vybrat  
  Funkce pro vybrání stránky na základě identu
  *  `public function getPageById($id)`  
  `$id - [integer]` id stránky, která se má vybrat  
  Funkce pro vybrání stránky na základě id  

### <a name="facades-core-site-settings">SiteSettingsFacade</a>  
Fasáda pro práci s nastavením stránek.
Pracuje s entitou <a name="orm-core-site-settings">SiteSettings</a>  
#### Dostupné funkce:
  *  `public function getSiteSettings()`  
  Funkce pro vybrání všech záznamů pro nastavení stránek  

### <a name="facades-core-text">TextFacade</a>  
Fasáda pro práci s textovými bloky  
Pracuje s entitou <a name="orm-core-text">Text</a>  
<b>Vyžaduje</b> nastavení proměné `$allowLangs v parameters.neon`  
#### Dostupné funkce:
  *  `public function getAllTexts()`  
  Funkce pro získání všech záznámů textových bloků (entity Text)  
  *  `public function getTextByIdent($ident)`  
  `$ident - [string]` ident hledaného textového bloku  
  Funkce pro získání jednoho záznamu dle identu  
  *  `public function getTextById($id)`  
  `$id - [integer]` id hledaného textového bloku  
  Funkce pro získání jednoho záznamu dle id  
  *  `public function createNew($ident)`  
  `$ident - [string]` ident nového textového bloku  
  Funkce pro vytvoření nového textového bloku, k čemuž využívá funkci `save($text, $ident)`  
  $ident se upravuje pomocí `Nette\Utils\Strings::webalize`
  *  `private function save($text, $ident)`  
  `$text [App\Model\Entities\Text]` entita textového bloku  
  `$ident [string]` ident pro nový textový blok  
  Funkce vytváří nový textový blok a jeho překlady (entita TextTranslation) pro všechny povolené jazyky ($allowLangs)  

### <a name="facades-core-translation">TranslationFacade</a>  
Fasáda pro práci s překlady  
Pracuje s entitou <a name="orm-core-translation">Translation</a>  
#### Dostupné funkce:  
  *  `public function getTranslations()`  
  Funkce pro získání všech překladů  
  *  `public function hasEntity(string $namespace, string $section, string $key, string $lang)`  
  `$namespace [string]`  
  `$section [string]`  
  `$key [string]`  
  `$lang [string]`  
  Funkce pro získání jednoho překladu dle parametrů  
  *  `public function prepareEntitiesForImport()`  
  Funkce pro přípravu dat před importem (nastavuje atribut `updated = false`)  
  *  `public function importEntities(array $entities)`  
  `$entities [array]`  
  Funkce pro import překladů  
  *  `public function cleanUpTranslation()`  
  Funkce pro vyčištění nepouživaných překladů po importu (smaže všechny záznamy s `updated = false`)

## <a name="orm-core">orm</a>  

### <a name="orm-core-admin-log">AdminLog</a>  
#### AdminLog  
Entita pro záznamy aktivit v ETA  
Tabulka `prefix_admin_log`  
```
$tableName [string] - název tabulky kde proběhla změna  
$primaryKeyValue [string] - hodnota primárního klíče záznamu  
$action [string] - akce (insert, update, delete)  
$identityId [integer] [nullable] - id uživatele  
$username [string] - username uživatele  
$ip [string] - ip adresa uživatele  
$timestamp [datetime] - čas a datum změny  
```
#### AdminLogRepozitory  

### <a name="orm-core-language">Language</a>  

### <a name="orm-core-menu">Menu</a>  
#### Menu  
Entita pro menu  
Tabulka `prefix_menu`  
```
$menuItems [OneToMany] [App\Model\Entities\MenuItem] - položky menu  
$ident [string] - ident menu  
$locale [string] - jazyková verze menu  
```
#### MenuRepozitory  

### <a name="orm-core-menu-item">MenuItem</a>  
#### MenuItem  
Entita pro položky menu  
Tabulka `prefix_menu_item`  
```
Timestampable - entita si vytváří atributy created_at a updated_at (datetime), ukláda datum a čas upravy a vytvoření záznamu (pouze z aplikace)  

$menu [ManyToOne] [App\Model\Entities\Menu join on id] - menu položky  
$parent [ManyToOne] [App\Model\Entities\MenuItem join on id] - rodič položky  
$childs [OneToMany] [App\Model\Entities\MenuItem by parent] - potomci položky  
$blank [boolean] - otevřít v novém okně  
$routeAlias [ManyToOne] [App\Model\Entities\RouteAlias join on id] - propojení na existující stránku v aplikaci  
$title [string] - název položky  
$link [string] [nullable] - odkaz  
$anchor [string] [nullable] - kotva  
$dropdown [boolean] [nullable] - dropdown menu (potomci)  
$show [boolean] [nullable] - zobrazit  
$order [integer] - pořadí (pro každé menu se bere zvlášť)  
$treeAccessRights [integer] [nullable] - pro zobrazení stromu  
```
#### MenuItemRepozitory  

### <a name="orm-core-page">Page</a>  
#### Page  
Entita pro textové stránky  
Tabulka `prefix_page`  
```
Timestampable - entita si vytváří atributy created_at a updated_at (datetime), ukláda datum a čas upravy a vytvoření záznamu (pouze z aplikace)  
Translatable - entita má některé atributy jako přeložitelné, ty se definují ve zvláštní entitě PageTranslation  

$ident [string] - textový ident textové stránky  
```
#### PageTranslation  
Entita definující přeložitelné atributy entity Page  
Tabulka `prefix_page_translation`  
```
Timestampable - entita si vytváří atributy created_at a updated_at (datetime), ukláda datum a čas upravy a vytvoření záznamu (pouze z aplikace)  
Translatable - entita si vytváří atribut locale, pro definici jazyka  

$title [string] - nadpis stránky  
$text [text] [nullable] - obsah textové stránky  
$visible [boolean] - zobrazení  
```

#### PageRepozitory  

### <a name="orm-core-router">Router</a>  
#### Router  
Entita pro databázový router, pracuje s presenterem a action  
Tabulka `prefix_router`  
```
$presenter [string] - ukládá název presenteru  
$action [string] - ukládá název akce presenteru  
```
#### RouterRepozitory  

### <a name="orm-core-route-alias">RouteAlias</a>  
#### RouteAlias  
Entita pro databázový router, pro výpis 'pěkné url'  
Tabulka `prefix_route_alias`  
```
$route [ManyToOne] [App\Model\Entities\Route join on id] - záznam routy    
$language [string] - jazyk  
$slug [string] - vytvořené (pěkné url)  
$item [integer] [nullable] - id položky  
$parameters [text] [nullable] - parametry požadavku (url)  
$deleted [boolean] [nullable] - je položka neaktivní  
$added [datetime] - datum přidání položky  
```
#### RouteAliasRepozitory  

### <a name="orm-core-route-seo">RouteSeo</a>  
#### RouteSeo  
Entita pro tvorbu seo  
Tabulka `prefix_route_seo`  
```
$route [ManyToOne] [App\Model\Entities\Route join on id] - záznam routy    
$language [string] - jazyk  
$slug [string] - vytvořené (pěkné url)  
$item [integer] [nullable] - id položky  
$title [string] [nullable] - SEO title  
$description [string] [nullable] - SEO description  
$keywords [string] - SEO keywords  
```
#### RouteSeoRepozitory  

### <a name="orm-core-site-settings">SiteSettings</a>  
#### SiteSettings  
Entita pro nastavení stránek  
Tabulka `prefix_site_setting`  
```
$key [string] - klíč nastavení  
$value [string] [nullable] - hodnota nastavení  
$lang [string] [nullable] - jazyk pro nastavení  
```
#### SiteSettingsRepozitory  

### <a name="orm-core-text">Text</a>  
#### Text  
Entita pro textové bloky  
Tabulka `prefix_text`  
```
Timestampable - entita si vytváří atributy created_at a updated_at (datetime), ukláda datum a čas upravy a vytvoření záznamu (pouze z aplikace)  
Translatable - entita má některé atributy jako přeložitelné, ty se definují ve zvláštní entitě TextTranslation  

$ident [string] - textový ident textového bloku  
```
#### TextTranslation  
Entita definující přeložitelné atributy entity Text  
Tabulka `prefix_text_translation`  
```
Timestampable - entita si vytváří atributy created_at a updated_at (datetime), ukláda datum a čas upravy a vytvoření záznamu (pouze z aplikace)  
Translatable - entita si vytváří atribut locale, pro definici jazyka  

$title [string] - nadpis textového bloku  
$content [text] [nullable] - obsah textového bloku  
```

#### TextRepozitory  

### <a name="orm-core-translation">Translation</a>  
#### Translation  
Entita pro překlady  
Tabulka `prefix_translation`  
```
$namespace [string] - jmenný prostor  
$section [string] - sekce  
$key [string] - klíč  
$default [string] - defaultní hodnota překládané fráze  
$translate [string] [nullable] - překlad fráze  
$lang [string] - jazyk překladu  
$updated [smallint] [default 0] - pomocný atribut pro generování překladů  
```
#### TranslationRepozitory  

---

## <a name="presenters">presenters</a>  

## core  

### <a name="abstract-error4-presenter">AbstractError4xxPresenter</a>  
Abstraktní presenter pro handle errorů s kódem 4xx  
<b>Extends:</b> `AbstractQwPresenter`  
<b>Use:</b>  
```  
- App\Presenters\AbstractQwPresenter  
- Nette  
- Exception  
```
<b>Funkce:</b>  

  *  `public function renderDefault(Exception $exception)`  
  `$exception [Exception]` - vyvolaná vyjímka  
  Funkce zobrazí šablonu dle kódu erroru a zobrazí jí  

### <a name="abstract-error-presenter">AbstractErrorPresenter</a>  
Abstraktní presenter pro handle errorů  
<b>Implements:</b> `IPresenter`  
<b>Use:</b>  
```  
- Nette\Application\Request  
- Nette\Application\IResponse  
- Nette\Application\IPresenter  
- Nette\Application\BadRequestException  
- Nette\Application\Responses\ForwardResponse  
- Nette\Application\Responses\CallbackResponse  
- Nette\SmartObject  
- Tracy\ILogger   
```
<b>Funkce:</b>  

  -  `public function __construct(ILogger $logger)`  
    `$logger [ILogger]` - vyvolaná vyjímka  
    Funkce zobrazí šablonu dle kódu vyjímky a zobrazí jí  
  -  `public function run(Request $request)`  
    `$request [Request]` - request který vyvolal chybu  
    Základní funkce pro handle errorů. Při erroru 500 zobrazí chybu, při errorech s kódem 4xx přesměruje na Error4xx  

### <a name="abstract-page-presenter">AbstractPagePresenter</a>  
Abstraktní presenter pro textové stránky  
<b>Extends:</b> `AbstractQwPresenter`  
<b>Use:</b>  
```  
- App\Model\Facades\PageFacade;  
- App\Presenters\AbstractQwPresenter;  
- Nette\Application\BadRequestException;  
```
<b>Funkce:</b>  

  -  `public function startup()`  
    Funkce zobrazuje textovou stránku dle identu (který se předává jako `action`), v případě neexistujícího identu vyhazuje 404  

### <a name="abstract-qw-presenter">AbstractQwPresenter</a>  
Základní abstraktní presenter  
<b>Extends:</b> `Presenter`  
<b>Use:</b>  
```  
- stdclass;
- Nette\Application\UI\Presenter;
- Nette\Bridges\ApplicationLatte\Template;
- Kdyby\Translation\Translator;
- App\Services\SiteSettingService;
- App\Components\CssControl;
- App\Components\JsControl;
- App\Components\ILanguageSwitchControlFactory;
- App\Components\ITextControlFactory;
- App\Components\IMenuControlFactory;
- App\Components\SlugCreatorControl;
- App\Components\GaCodeControl;
- App\Components\GtmCodeControl;  
```
<b>Funkce:</b>  

  * `public function startup()`    
    Funkce nastavuje základní SiteSettings, jazyk, překladač, latte filtry a seo data  
  * `public function checkRequirements($element)`  
  * `public function createComponentCssControl()`  
    Vytváří komponentu pro výpis stylů  
  * `protected function createComponentJsControl()`  
    Vytváří komponentu pro výpis scriptů  
  * `protected function createComponentText()`  
    Vytváří komponentu pro práci s textovými bloky  
  * `protected function createComponentMenu()`  
    Vytváří komponentu pro výpis menu  
  * `protected function createComponentLanguageSwitch()`  
    Vytváří komponentu pro přepínač jazyků  
  * `protected function createComponentSlugCreator()`  
    Vytváří komponentu pro vytváření slugu ('pěkná url') pro databázový router  
  * `protected function createComponentGaCode()`  
    Vytváří komponentu pro výpis měřícího kódu GA  
  * `protected function createComponentGoogleTagManager()`  
    Vytváří komponentu pro výpis měřícího kódu GTM  

### <a name="abstract-security-presenter">AbstractSecurityPresenter</a>  
Abstraktní presenter pro zabezpečené sekce webu  
<b>Extends:</b> `AbstractQwPresenter`  
<b>Use:</b>  
```
- App\Presenters\AbstractQwPresenter;  
```
<b>Funkce:</b>  

  *  `public function startup()`  
    Funkce kontroluje přihlášení uživatele, pokud není přihlášený přesměrovává na Homepage  

## <a name="templates">templates</a>  

### <b>Error</b>  
Šablony pro Error4xxPresenter a ErrorPresenter  

  * 403.latte  
  * 404.latte  
  * 405.latte  
  * 410.latte  
  * 4xx.latte  
  * 500.phtml  

### <b>Homepage</b>  
Šablona pro HomepagePresenter  

### <b>Page</b>  
šablony pro PagePresenter (action default)  

### <a name="latte-layout"> @layout.latte</a>  
Hlavní šablona pro webovou aplikaci  
```
< html >
  < head >
    Výpis GTM komponenty (head)
    Výpis title, description a keywords (přes bloky)  
    Výpis CSS komponenty
    ... (meta tagy)
  < /head >
  < body >
    Výpis GTM komponenty (body)
    {include header.latte} - záhlaví webu
    {include content} - hlavní obsah (většinou obsah šablony z presenteru)
    {include footer.latte} - zápatí webu
    Vytváření slugu pro DBRouter (přes bloky)
    Výpis JS komponenty
    Výpis flashes zpráv
  < /body>
< /html >

```

### <a name="latte-footer"> footer.latte</a>  
Šablona pro patičku webu (footer)  

### <a name="latte-header"> header.latte</a>  
Šablona pro záhlaví webu (header)  
```
< header >
  Výpis loga
  Výpis MENU komponenty
  Výpis komponenty pro přepínání jazyků
< /header >
```

### <a name="latte-paginator"> paginator.latte</a>  
Šablona pro zobrazení stránkování  

### <a name="base-presenter">BasePresenter.php</a>  
Hlavní presenter aplikace  
<b>Extends:</b> `AbstractQwPresenter`  

### <a name="error4xx-presenter">Error4xxPresenter.php</a>  
Presenter pro obsluhu 4xx errorů  
<b>Extends:</b> `AbstractError4xxPresenter`  

### <a name="error-presenter">ErrorPresenter.php</a>  
Presenter pro obsluhu erroru  
<b>Extends:</b> `AbstractErrorPresenter`  

### <a name="hp-presenter">HomepagePresenter.php</a>  
Presenter obsluhující homepage  
<b>Extends:</b> `BasePresenter`  

### <a name="page-presenter">PagePresenter.php</a>  
Presenter pro obsluhu textových stránek  
<b>Extends:</b> `AbstractPagePresenter`  

### <a name="security-presenter">SecurityBasePresenter.php</a>  
Presenter pro obsluhu přihlašování  
<b>Extends:</b> `AbstractSecurityBasePresenter`  

---

## router  

### <a name="router-factory">RouterFactory</a>  
Vytváří RouteList pro aplikaci  
<b>Use:</b>  
```  
- App\Services\SiteSettingService;
- Nette\Application\IRouter;
- Nette\DI\Container;
- Nette\Application\Routers\RouteList;
- Nette\Application\Routers\Route;
- App\Services\DatabaseRouter;
- App\Services\StaticRouter;
```  
<b>Základní routy:</b>  
```  
// Routa pro AdminModule, dostupný na adrese xx.xx/qred/xxx
$router[] = new Route('http' . ($this->https ? 's' : '') . '://' . '%host%/%basePath%/[<locale ('.$langs.')>/]qred/<presenter>/<action>[/<id>]',['module' => 'Admin', 'presenter' => 'Homepage', 'action' => 'default', 'locale' => $this->lang]);

// Databázový router
$router[] = new DatabaseRouter($this->context,NULL,['presenter' => 'Homepage', 'action' => 'default', 'locale' => $this->lang],($this->https ? IRouter::SECURED : 0));

// Základní routa
$router[] = new Route('http' . ($this->https ? 's' : '') . '://' . '%host%/%basePath%/[<locale ('.$langs.')>/]<presenter>/<action>[/<id>]',['presenter' => 'Homepage', 'action' => 'default', 'locale' => $this->lang]);
```  

---

## services  

### core  

### <a name="services-database-router">DatabaseRouter</a>  
Služba pro databázový router  

### <a name="services-language">LanguageService</a>  
Služba pro jazyky a překlady (odstraní se)  

### <a name="services-site-settings">SiteSettingsService</a>  
Služba pro nastavení webové aplikace  

### <a name="services-static-router">StaticRouter</a>  
Služba pro statický router  

### <a name="services-translator">TranslatorService</a>  
Služba pro překlady  

---

## <a name="bootstrap">boostrap.php</a>  
Načtení knihoven z vendoru  
Zapnutí debug modu a nastavení prostředí  
Načtení config souborů
Vytvoření DI containeru
