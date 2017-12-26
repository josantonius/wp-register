# CHANGELOG

## 1.0.5 - 2017-12-26

* Implemented `PHP Mess Detector` to detect inconsistencies in code styles.

* Implemented `PHP Code Beautifier and Fixer` to fixing errors automatically.

* Implemented `PHP Coding Standards Fixer` to organize PHP code automatically according to PSR standards.

* Implemented `WordPress PHPCS code standard` from all library PHP files.

* Implemented `Codacy` to automates code reviews and monitors code quality over time.

* Implemented `Codecov` to coverage reports.

* Deprecated `Josantonius\WP_Register\WP_Register::isAdded()` method.

* Added `Josantonius\WP_Register\WP_Register::is_added()` method.

## 1.0.4 - 2017-10-06

* Added functionality to minify CSS/JS content and unify files into a single file.

* Unit tests supported by `PHPUnit` were added.

* The repository was synchronized with `Travis CI` to implement continuous integration.

* Added `Josantonius\WP_Register\WP_Register::unify()` method.
* Added `Josantonius\WP_Register\WP_Register::validate()` method.
* Added `Josantonius\WP_Register\WP_Register::setParams()` method.
* Added `Josantonius\WP_Register\WP_Register::lookIfProcessFiles()` method.
* Added `Josantonius\WP_Register\WP_Register::prepareFiles()` method.
* Added `Josantonius\WP_Register\WP_Register::getRoutesToFolder()` method.
* Added `Josantonius\WP_Register\WP_Register::getProcessedFiles()` method.
* Added `Josantonius\WP_Register\WP_Register::getPathFromUrl()` method.
* Added `Josantonius\WP_Register\WP_Register::isModifiedFile()` method.
* Added `Josantonius\WP_Register\WP_Register::isModifiedHash()` method.
* Added `Josantonius\WP_Register\WP_Register::isExternalUrl()` method.
* Added `Josantonius\WP_Register\WP_Register::unifyFiles()` method.
* Added `Josantonius\WP_Register\WP_Register::saveExternalFile()` method.
* Added `Josantonius\WP_Register\WP_Register::compressFiles()` method.
* Added `Josantonius\WP_Register\WP_Register::saveFile()` method.
* Added `Josantonius\WP_Register\WP_Register::createDirectoryFromFile()` method.
* Added `Josantonius\WP_Register\WP_Register::setProcessedFiles()` method.
* Added `Josantonius\WP_Register\WP_Register::setNewParams()` method.
* Added `Josantonius\WP_Register\WP_Register::unifyParams()` method.

* Changed `Josantonius\WP_Register\WP_Register::isEnqueued()` method to `Josantonius\WP_Register\WP_Register::isAdded()` method.

* Added `Josantonius\WP_Register\Test\RegisterAdminScriptsTest` class.
* Added `Josantonius\WP_Register\Test\RegisterAdminScriptsTest->setUp()` method.
* Added `Josantonius\WP_Register\Test\RegisterAdminScriptsTest->testAddAdminScript()` method.
* Added `Josantonius\WP_Register\Test\RegisterAdminScriptsTest->testAddAdminScriptWithoutName()` method.
* Added `Josantonius\WP_Register\Test\RegisterAdminScriptsTest->testAddAdminScriptWithoutUrl()` method.
* Added `Josantonius\WP_Register\Test\RegisterAdminScriptsTest->testAddFrontEndScriptFromAdmin()` method.
* Added `Josantonius\WP_Register\Test\RegisterAdminScriptsTest->testAddAdminScriptAddingAllParams()` method.
* Added `Josantonius\WP_Register\Test\RegisterAdminScriptsTest->testIfAdminScriptsAddedCorrectly()` method.
* Added `Josantonius\WP_Register\Test\RegisterAdminScriptsTest->testIfAdminScriptIsRegistered()` method.
* Added `Josantonius\WP_Register\Test\RegisterAdminScriptsTest->testIfAdminScriptIsEnqueued()` method.
* Added `Josantonius\WP_Register\Test\RegisterAdminScriptsTest->testIfAdminScriptIsQueue()` method.
* Added `Josantonius\WP_Register\Test\RegisterAdminScriptsTest->testIfAdminScriptIsDone()` method.
* Added `Josantonius\WP_Register\Test\RegisterAdminScriptsTest->testRemoveAddedAdminScripts()` method.
* Added `Josantonius\WP_Register\Test\RegisterAdminScriptsTest->testValidationAfterDeletion()` method.

* Added `Josantonius\WP_Register\Test\RegisterAdminStylesTest` class.
* Added `Josantonius\WP_Register\Test\RegisterAdminStylesTest->setUp()` method.
* Added `Josantonius\WP_Register\Test\RegisterAdminStylesTest->testAddAdminStyle()` method.
* Added `Josantonius\WP_Register\Test\RegisterAdminStylesTest->testAddAdminStyleWithoutName()` method.
* Added `Josantonius\WP_Register\Test\RegisterAdminStylesTest->testAddAdminStyleWithoutUrl()` method.
* Added `Josantonius\WP_Register\Test\RegisterAdminStylesTest->testAddFrontEndStyleFromAdmin()` method.
* Added `Josantonius\WP_Register\Test\RegisterAdminStylesTest->testAddAdminStyleAddingAllParams()` method.
* Added `Josantonius\WP_Register\Test\RegisterAdminStylesTest->testIfAdminStylesAddedCorrectly()` method.
* Added `Josantonius\WP_Register\Test\RegisterAdminStylesTest->testIfAdminStyleIsRegistered()` method.
* Added `Josantonius\WP_Register\Test\RegisterAdminStylesTest->testIfAdminStyleIsEnqueued()` method.
* Added `Josantonius\WP_Register\Test\RegisterAdminStylesTest->testIfAdminStyleIsQueue()` method.
* Added `Josantonius\WP_Register\Test\RegisterAdminStylesTest->testIfAdminStyleIsDone()` method.
* Added `Josantonius\WP_Register\Test\RegisterAdminStylesTest->testRemoveAddedAdminStyles()` method.
* Added `Josantonius\WP_Register\Test\RegisterAdminStylesTest->testValidationAfterDeletion()` method.

* Added `Josantonius\WP_Register\Test\RegisterScriptsTest` class.
* Added `Josantonius\WP_Register\Test\RegisterScriptsTest->setUp()` method.
* Added `Josantonius\WP_Register\Test\RegisterScriptsTest->testAddFrontEndScript()` method.
* Added `Josantonius\WP_Register\Test\RegisterScriptsTest->testAddFrontEndScriptWithoutName()` method.
* Added `Josantonius\WP_Register\Test\RegisterScriptsTest->testAddFrontEndScriptWithoutUrl()` method.
* Added `Josantonius\WP_Register\Test\RegisterScriptsTest->testAddAdminScriptFromFrontEnd()` method.
* Added `Josantonius\WP_Register\Test\RegisterScriptsTest->testAddFrontEndScriptAddingAllParams()` method.
* Added `Josantonius\WP_Register\Test\RegisterScriptsTest->testIfFrontEndScriptsAddedCorrectly()` method.
* Added `Josantonius\WP_Register\Test\RegisterScriptsTest->testIfFrontEndScriptIsRegistered()` method.
* Added `Josantonius\WP_Register\Test\RegisterScriptsTest->testIfFrontEndScriptIsEnqueued()` method.
* Added `Josantonius\WP_Register\Test\RegisterScriptsTest->testIfFrontEndScriptIsQueue()` method.
* Added `Josantonius\WP_Register\Test\RegisterScriptsTest->testIfFrontEndScriptIsDone()` method.
* Added `Josantonius\WP_Register\Test\RegisterScriptsTest->testRemoveAddedFrontEndScripts()` method.
* Added `Josantonius\WP_Register\Test\RegisterScriptsTest->testValidationAfterDeletion()` method.

* Added `Josantonius\WP_Register\Test\RegisterStylesTest` class.
* Added `Josantonius\WP_Register\Test\RegisterStylesTest->setUp()` method.
* Added `Josantonius\WP_Register\Test\RegisterStylesTest->testAddFrontEndStyle()` method.
* Added `Josantonius\WP_Register\Test\RegisterStylesTest->testAddFrontEndStyleWithoutName()` method.
* Added `Josantonius\WP_Register\Test\RegisterStylesTest->testAddFrontEndStyleWithoutUrl()` method.
* Added `Josantonius\WP_Register\Test\RegisterStylesTest->testAddAdminStyleFromFrontEnd()` method.
* Added `Josantonius\WP_Register\Test\RegisterStylesTest->testAddFrontEndStyleAddingAllParams()` method.
* Added `Josantonius\WP_Register\Test\RegisterStylesTest->testIfFrontEndStylesAddedCorrectly()` method.
* Added `Josantonius\WP_Register\Test\RegisterStylesTest->testIfFrontEndStyleIsRegistered()` method.
* Added `Josantonius\WP_Register\Test\RegisterStylesTest->testIfFrontEndStyleIsEnqueued()` method.
* Added `Josantonius\WP_Register\Test\RegisterStylesTest->testIfFrontEndStyleIsQueue()` method.
* Added `Josantonius\WP_Register\Test\RegisterStylesTest->testIfFrontEndStyleIsDone()` method.
* Added `Josantonius\WP_Register\Test\RegisterStylesTest->testRemoveAddedFrontEndStyles()` method.
* Added `Josantonius\WP_Register\Test\RegisterStylesTest->testValidationAfterDeletion()` method.

* Added `Josantonius\WP_Register\Test\UnifyAdminFiles` class.
* Added `Josantonius\WP_Register\Test\UnifyAdminFiles->setUp()` method.
* Added `Josantonius\WP_Register\Test\UnifyAdminFiles->testUnify()` method.
* Added `Josantonius\WP_Register\Test\UnifyAdminFiles->testUnifySpecifyingDifferentUrlPaths()` method.
* Added `Josantonius\WP_Register\Test\UnifyAdminFiles->testUnifyAndMinify()` method.
* Added `Josantonius\WP_Register\Test\UnifyAdminFiles->testUnifyAndMinifySpecifyingDifferentUrlPaths()` method.
* Added `Josantonius\WP_Register\Test\UnifyAdminFiles->testAddFrontEndStylesAndScripts()` method.
* Added `Josantonius\WP_Register\Test\UnifyAdminFiles->testIfFrontEndStylesAndScriptsAddedCorrectly()` method.
* Added `Josantonius\WP_Register\Test\UnifyAdminFiles->testIfFrontEndStyleAndScriptsWasRegistered()` method.
* Added `Josantonius\WP_Register\Test\UnifyAdminFiles->testIfUnifiedFilesWasCreated()` method.
* Added `Josantonius\WP_Register\Test\UnifyAdminFiles->testIfFrontEndStylesAndScriptsAreEnqueued()` method.
* Added `Josantonius\WP_Register\Test\UnifyAdminFiles->testIfFrontEndStylesAndScriptsAreQueue()` method.
* Added `Josantonius\WP_Register\Test\UnifyAdminFiles->testIfFrontEndStylesAndScriptAreDone()` method.
* Added `Josantonius\WP_Register\Test\UnifyAdminFiles->testRemoveAddedFrontEndStylesAndScripts()` method.
* Added `Josantonius\WP_Register\Test\UnifyAdminFiles->testValidationAfterDeletion()` method.

* Added `Josantonius\WP_Register\Test\UnifyFiles` class.
* Added `Josantonius\WP_Register\Test\UnifyFiles->setUp()` method.
* Added `Josantonius\WP_Register\Test\UnifyFiles->testUnify()` method.
* Added `Josantonius\WP_Register\Test\UnifyFiles->testUnifySpecifyingDifferentUrlPaths()` method.
* Added `Josantonius\WP_Register\Test\UnifyFiles->testUnifyAndMinify()` method.
* Added `Josantonius\WP_Register\Test\UnifyFiles->testUnifyAndMinifySpecifyingDifferentUrlPaths()` method.
* Added `Josantonius\WP_Register\Test\UnifyFiles->testAddFrontEndStylesAndScripts()` method.
* Added `Josantonius\WP_Register\Test\UnifyFiles->testIfFrontEndStylesAndScriptsAddedCorrectly()` method.
* Added `Josantonius\WP_Register\Test\UnifyFiles->testIfFrontEndStyleAndScriptsWasRegistered()` method.
* Added `Josantonius\WP_Register\Test\UnifyFiles->testIfUnifiedFilesWasCreated()` method.
* Added `Josantonius\WP_Register\Test\UnifyFiles->testIfFrontEndStylesAndScriptsAreEnqueued()` method.
* Added `Josantonius\WP_Register\Test\UnifyFiles->testIfFrontEndStylesAndScriptsAreQueue()` method.
* Added `Josantonius\WP_Register\Test\UnifyFiles->testIfFrontEndStylesAndScriptAreDone()` method.
* Added `Josantonius\WP_Register\Test\UnifyFiles->testRemoveAddedFrontEndStylesAndScripts()` method.
* Added `Josantonius\WP_Register\Test\UnifyFiles->testValidationAfterDeletion()` method.

* Added `WP_Register/src/bootstrap.php` file

* Added `WP_Register/tests/bootstrap.php` file.
* Added `WP_Register/tests/sample-plugin.php` file.

* Added `WP_Register/phpunit.xml.dist` file.
* Added `WP_Register/_config.yml` file.
* Added `WP_Register/.travis.yml` file.

* Added `WP_Register/bin/install-wp-tests.sh` file.

## 1.0.3 - 2017-08-04

* Changed `Josantonius\WP_Register\WP_Register::isSet()` method to `Josantonius\WP_Register\WP_Register::isEnqueued()` method.

## 1.0.2 - 2017-06-17

* Now if two equal style/scripts are added, will be overwritten and only the last one will be added.

* Added `Josantonius\WP_Register\WP_Register::isSet()` method.
* Added `Josantonius\WP_Register\WP_Register::remove()` method.

## 1.0.1 - 2017-05-24

* The action hook was changed in the add() method.

## 1.0.0 - 2017-03-24

* Added `Josantonius\WP_Register\WP_Register` class.
* Added `Josantonius\WP_Register\WP_Register::add()` method.
* Added `Josantonius\WP_Register\WP_Register::addScript()` method.
* Added `Josantonius\WP_Register\WP_Register::addStyle()` method.