Short explanation : 
These XML files use the XMLint format
[XMLint documentation](https://raw.githubusercontent.com/ludosevilla/minipaviCli/master/XMLint/XMLint-doc.pdf)


What was added:
- Usage of locally stored page when their URL begin with the config.php XML_PAGES_URL parameter to avoid http/https queries
- Usage of locally stored page when not with http/https scheme (ie: "sommaire.vdt" instead full URL)
- If a service controller is named as a page, it will be called instead XmlController, with its xml_file and xml_pagename set on its context
- Consequently you could extends XmlController for any page to expand capabilities, without rewriting all the existing XmlController behaviour
- You might also jump to any controller extending VideotexController (thus outside of the XML file scope)
- From a VideotexController you might jump back to the XML and XmlController through \MiniPaviFwk\actions\PageAction()
- When you go back to XML through \MiniPaviFwk\actions\PageAction() the overriding is also supported transparently


What is not supported yet:
- multiline input
- multiline input being sent by email
