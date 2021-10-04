# Installation

Click `Clone or download` > `Download ZIP` and then upload the archive 
to your Joomla site. Alternatively, you may download the archive by [clicking here](https://github.com/bluewallweb/com_continuousdelivery/archive/refs/heads/main.zip).

# Usage

You can install a package by running the follow command:

```bash
curl --form 'deployKey=DEPLOY_KEY_HERE' --form 'package=@/path/to/file' \
  https://example.org/index.php?option=com_continuousdelivery
```

The deploy key can be found in the Global Configuration section of 
Joomla after this component is installed.

The installation process should return a JSON encoded object as a 
response. This object will contain an `error` key (string) on failure or 
a `success` key (bool) on success.

# Example GitHub Action

You can use the below as a general template for setting up an automatic
build and deploy process with GitHub.

```
name: Build & Deploy Site

on:
  push:
    branches: [ main ]
    
jobs:
  build_deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2           
      - name: Build Joomla! Package 
        run: |
          tar -cvzf com_example.tar.gz src/
      - name: Deploy Joomla! Package
        env:
          DEPLOY_KEY: ${{ secrets.DEPLOY_KEY }}    
          URL: ${{ secrets.URL }}        
        run: |
           test $(curl --silent --form deployKey="$DEPLOY_KEY" --form 'package=@com_example.tar.gz' "$URL"/index.php?option=com_continuousdelivery | grep 'success')
```
