# Context

Responsability : store the current state of the current controller, as an array

@TODO

## What is stored

- `context['controller']` : full class name of the controller to instantiate for the current query, and the next one

- `context['xml_filename']` : for XML Controllers, the current XML filename as a string

- `context['xml_page']` : for XML Controllers, the current XML page name as a string

- `context['params']` : The params array provided when initiallly instantiating the current controller, or an empty array

- `context['stack']` : *RESERVED FOR FUTURE USE*


## What you could store?

Whatever you want, as long as you avoid modifying or erasing the data expected by the current Controller


## References
[Controllers](./Controllers.md)

[XML Controllers](./XML-controllers.md)

[Session](./Session.md)

[Session Handler](./Session-handler.md)
