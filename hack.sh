#!/bin/bash
rm -rf ~/.config/JetBrains/PhpStorm*/eval
rm -rf ~/.java/.userPrefs/jetbrains/phpstorm
sed -i '/evlsprt/d' ~/.config/JetBrains/PhpStorm*/options/other.xml
