# Installation

Click `Clone or download` > `Download ZIP` and then upload the archive 
to your Joomla site.

# Usage

You can install a package by running the follow command:

```bash
curl --form 'deployKey=DEPLOY_KEY_HERE' --form 'package=@/path/to/file' \
  https://example.org/index.php?option=com_continuousdelivery
```

The installation process should return a JSON encoded object as a 
response. This object will contain an `error` key (string) on failure or 
a `success` key (bool) on success.
