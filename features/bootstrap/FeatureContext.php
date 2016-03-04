<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\MinkExtension\Context\MinkContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context, SnippetAcceptingContext
{
    /**
     * @When /^I hover over the element "([^"]*)"$/
     */
    public function iHoverOverTheElement($locator)
    {
        $session = $this->getSession(); // get the mink session
        $element = $session->getPage()->find('css', $locator); // runs the actual query and returns the element

        // errors must not pass silently
        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Could not evaluate CSS selector: "%s"', $locator));
        }

        // ok, let's hover it
        $element->mouseOver();
        $session->wait(1000); // and wait for a second
    }

    /**
     * @When /^I click the element "([^"]*)"$/
     */
    public function iClickTheElement($locator)
    {
        $session = $this->getSession(); // get the mink session
        $element = $session->getPage()->find('css', $locator); // runs the actual query and returns the element

        // errors must not pass silently
        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Could not evaluate CSS selector: "%s"', $locator));
        }

        $element->click();
    }

    /**
     * @When /^I fill the element "([^"]*)" with "([^"]*)"$/
     */
    public function iFillTheElement($locator, $value)
    {
        $session = $this->getSession(); // get the mink session
        $element = $session->getPage()->find('css', $locator); // runs the actual query and returns the element

        if (null === $element) {
            throw new \InvalidArgumentException(sprintf('Could not evaluate CSS selector: "%s"', $locator));
        }

        $element->setValue($value);
    }

    /**
     * @When /^I click the element "([^"]*)" with JS$/
     */
    public function iClickTheElementWithJs($id)
    {
        $this->getSession()->evaluateScript("document.getElementById('$id').click();");
    }

    /**
     * @Then /^I wait "(\d+)" seconds$/
     */
    public function wait($time)
    {
        $this->getSession()->wait($time * 1000);
    }

    /**
     * @Then /^the element "([^"]*)" should not be visible$/
     */
    public function elementNotVisible($element)
    {
        $element = $this->getSession()->getPage()->find('css', $element);

        if ($element->isVisible()) {
            throw new Exception();
        }
    }

    /**
     * @Then /^the element "([^"]*)" should be visible$/
     */
    public function theElementShouldBeVisible($element)
    {
        $element = $this->getSession()->getPage()->find('css', $element);

        if (!$element->isVisible()) {
            throw new Exception();
        }
    }

    /**
     * @BeforeScenario
     */
    public function resetSession()
    {
        $this->getSession()->restart();
    }
}
