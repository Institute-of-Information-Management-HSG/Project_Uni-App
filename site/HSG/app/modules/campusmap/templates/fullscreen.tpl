<DOCTYPE html PUBLIC "-//W3C//DTD XHTML Basic 1.1//EN" "http://www.w3.org/TR/xhtml-basic/xhtml-basic11.dtd">
         <html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xml:lang="en">
        <head>
            <meta http-equiv="content-type" content="application/xhtml+xml"/>
            <meta name="viewport" content="initial-scale=1.0" user-scalable=no" />
                
            <title>{$moduleName}{if !$isModuleHome}: {$pageTitle}{/if}</title>

{$map->getHeaderJS()}
{$map->getMapJS()}

        </head>
        <body>
            <div width="100%" height="100%">


                {$map->printOnLoad()}
                {$map->printMap()}
            </div>
        </body>
    </html>
