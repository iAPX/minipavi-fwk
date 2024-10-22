# Actions

Responsability : build and provide informations for end-of-query and next-query.

Sources directory : [src/actions/](../../src/actions/)


Actions are meant to provide the Queryhandler through queryLogic what will should be displayed and the controller responsible to handle the next input from the user.
Typically controller answer to an input is built by instantiating an Action.


## How Actions are used
Actions are returned by Controllers after processing an user input, one at a time.
These Actions will provide two informations to the Query Handler: which Controller is responsible to handle the next input including giving informations about this input, and what should be displayed to the user.

Shortly, this (new) Controller will indicate where is the input area on-screen, the expected format of the input area, the allowed function keys that Minipavi should accept from the user. Some informations are 


## Which Actions are provided by MiniPaviFwk
- AccueilAction : startup Action for new user and also when switching service (see SwitchServiceAction), could also serve to switch from a VideotexController to XML managed by XMLController or a derivative, or to come back to the homepage.

- ControllerAction : transfer control to a new Controller by it's full name, outputting what its `public function ecran(): string` returns.

- DeconnexionAction : throw out the user, displaying an optional service message on line 00 of the Minitel.

- Ligne00Action : displays a service message on the line 00 of the Minitel. Main usage is to display an error message

- PageAction : switch to another <page> on XmlController managed service, and enable changing the XML file used, could also be used to transfer control from a VideotexController managed service to a XmlController XML managed service,

- RedirectAction : redirect to another MiniPavi Minitel service by its URL. Internal switch done by SwitchServiceAction.

- RepetitionAction : displays again the current page, through the controller `public function ecran(): string` method.

- SwitchServiceAction : switch to the homepage of another internal service by its name. service should exists and be allowed in the [services/global-config.php](../../services/global-config.php) through `const ALLOWED_SERVICES = [ ... ];`

- VideotexOutputAction : displays a Vidéotex stream, staying in the same controller.

Usage examples in [services/demo/controllers/DemoActionCodeController.php](../../services/demo/controllers/DemoActionCodeController.php)


## Specific case of AccueilAction
It send back to the Accueil (homepage) of the service
Depending on services/{serviceName}/config.php,
instantiate the DEFAULT_CONTROLLER is not false or empty.
if not, use the DEFAULT_XML_FILE, in services/{serviceName}/xml,
in this case startup page is provided by the `<service><debut nom="xxx" /></service>` of the XML


## Create your own Action
Your action should extends the abstract class Action [src/actions/Action.php](../../src/actions/Action.php).
You will preferably put it on the "action" subdirectory of your service.

It should provide the output to be displayed to the user through `public function getOutput(): string` and the next instiated controller to be used through `public function getController(): \MiniPaviFwk\controllers\VideotexController`.

Actions generally implement a `public function __construct(...)` that initialize the `protected string $output = '';` and the `protected \MiniPaviFwk\controllers\VideotexController $controller;` variables, using getters provided by the abstract Action class.


> [!NOTE]
> `public function getOutput(): string` should return an empty string or a Vidéotex encoded string. Never null or an unicode string. You could use `Videotex->ecritUnicode()` or `\MiniPavi\MiniPaviCli::toG2()` to encode a unicode strings into a Vidéotex one.


## References
[Provided Actions](../../src/actions/)

[Actions usage example](../../services/demo/controllers/DemoActionCodeController.php)

[Controllers documentation](./Controllers.md)

[Controllers example in demo service](../../services/demo/controllers/)

[Videotex documentation](./Videotex-helper.md)

[Query Handler documentation](./Query-handler.md)
