
Here you could put your Minitel service Actions, if needed

see /src/actions/*Action.php : the different Actions offered

Any action should extend Action, and fill-in $this->controller and $this->output,
that will be available through ->getController() and ->getOutput().

$this->controller should be an instantiated controller implementing VideotexController,
This controler will be reinstantiated on the next user input, to process it.

$this->output should be not null, an empty string is acceptable, and is output directly to the user.
You might use Videotex class to create it's content, or MiniPaviCli directly.
