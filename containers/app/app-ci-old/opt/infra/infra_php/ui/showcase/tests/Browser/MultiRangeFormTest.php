<?php

namespace Tests\Browser;

use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\HomePage;
use Tests\DuskTestCase;
use TRF4\UI\Component\MultiRange;

class MultiRangeFormTest extends DuskTestCase
{

    /**
     * @var HomePage
     */
    public $homePage;

    public function setUp(): void
    {
        $this->homePage = new HomePage();
        parent::setUp(); // TODO: Change the autogenerated stub
    }

    public function testGetMultiRangeValueDefault()
    {
        $this->browse(function (Browser $b) {
            $b->visit($this->homePage);
            $b->scrollIntoView('@multiRangeForm');
            $this->submitForm($b, '@multiRangeform', '@multiRangeFormResult');
            $expected = "array (
  0 => '0',
  1 => '10',
)";

            $b->assertSeeIn('@multiRangeFormResult', $expected);
        });
    }


    public function testGetMultiRangeValueChanged()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit($this->homePage);
            $browser->scrollIntoView('@multiRangeForm');
            $this->setMultiRange($browser, '@multiRange', 2, 5);
            $this->submitForm($browser, '@multiRangeForm', '@multiRangeFormResult');
            $expected = "array (
  0 => '2',
  1 => '5',
)";

            $browser->assertSeeIn('@multiRangeFormResult', $expected);
        });
    }


    protected function setMultiRange(Browser $b, string $rangeSelector, float $value1, float $value2)
    {
        $baseSelector = $this->homePage->getSelector($rangeSelector);
        $range1 = $baseSelector;
        $range2 = MultiRange::get2ndRangeId($baseSelector);


        $b->driver->executeScript("\$('$range1').val($value1).trigger('input');");
        $b->driver->executeScript("\$('$range2').val($value2).trigger('input');");
    }

    private function submitForm(Browser $b, string $form, string $formResultSelector)
    {

        $button = $b->element($form)->findElement(WebDriverBy::className('submit-button'));
        $button->click();
        $b->waitUntil('!$.active');


        $b->waitUsing(10, 1, function () use ($formResultSelector, $b) {
            return $b->element($formResultSelector)->getText() !== '';
        });
    }
}
