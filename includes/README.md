# Includes
In this folder are the neccessary PHP files for the extension. The extension autoloads the hooks in the `hooks/`-folder and this file is using `MultiCodeBlock.php` itself.

### File Structure
The different files are structures like this:
* *includes/ [Folder]*
  * *class/ [Folder]*
    * **Code.php [File]**
    * **Description.php [File]**
    * **LanguageBlock.php [File]**
  * *hooks/ [Folder]*
    * **MultiCodeBlockHooks.php [File]**
  * *languages/ [Folder]*
    * **languages.json [File]**
  * *utils/ [Folder]*
    * **getData.php [File]**
    * **HTMLFramework.php [File]**
  * **MultiCodeBlock.php [File]**

### Files in this directory
* ##### MultiCodeBlock.php
  > The file that contains the main logic of the extension and combines all of the other files except the `MultiCodeBlockHooks.php` file.
  > It is the base file of MultiCodeBlock.

* ##### composer.json
  > The file that tells PHP Composer on what dependencies to install. These dependencies need to be installed, when the extension should be used.
  > Any of the code of the dependencies shouldn't be changed in this project and should rather be changed in their respective projects.
  
