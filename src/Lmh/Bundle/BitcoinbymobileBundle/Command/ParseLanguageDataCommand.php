<?php 

namespace Lmh\Bundle\BitcoinbymobileBundle\Command;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use XmlReader;

class ParseLanguageDataCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('lang:parse')
            ->setDescription('Parses the language demographic info from http://unicode.org/repos/cldr/trunk/common/supplemental/supplementalData.xml to determine the best local code to display for the enabled countries.')
            ->addOption('input', null, InputOption::VALUE_OPTIONAL, 'The input filename.', dirname(__FILE__).'/../Resources/config/supplementalData.xml')
            ->addOption('output', null, InputOption::VALUE_OPTIONAL, 'The output filename.', dirname(__FILE__).'/../Resources/config/default-country-locales.json')
            ->addOption('all', null, InputOption::VALUE_OPTIONAL, 'If specified, will ignore the system configured enabled countries and output all locales.', false);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $countries = null;
        if(!$input->getOption('all')) {
            $countries = $this->getContainer()->get('purchase_service')->getEnabledCountries();
        }

        $xml = new XmlReader();
        $xml->open($input->getOption('input'));

        $locales = array();
        $country = null;
        $highestPopulationPercentage = 0;
        $highestPopulationLanguage = null;
        
        while($xml->read()) {

            if(XmlReader::ELEMENT !== $xml->nodeType) {
                continue;
            }

            if('region' === $xml->name) {

                $country = $xml->

                $xml->moveToAttribute('iso3166');

                // all countries requested
                if(is_null($countries)) {
                    // save previous territory
                    $locales[$country] = $highestPopulationLanguage . '-' . $country;
                } else {
                    // check if this is an enabled country
                    if(false !== array_search($country, $countries)) {
                        // save previous territory
                        $locales[$country] = $highestPopulationLanguage . '-' . $country;
                    }
                }


                // reset the language stat for the new country
                $highestPopulationPercentage = 0;
                $highestPopulationLanguage = null;

                // grab the new country
                $newCountry = $xml->value;
                $country = $newCountry;

            }

            if('languagePopulation' === $xml->name) {
                $xml->moveToAttribute('type');
                $language = $xml->value;
                $xml->moveToAttribute('populationPercent');
                $percent = \floatval($xml->value);

                if($percent > $highestPopulationPercentage) {
                    $highestPopulationPercentage = $percent;
                    $highestPopulationLanguage = $language;
                }
            }

        }

        // this is faster than checking every iteration if we're on the first one yet
        unset($locales['']);

        $json = json_encode($locales);

        $outputFile = $input->getOption('output');
        $handle = fopen($outputFile, 'w');
        if(!$handle) {
            die("Couldn't open file '$outputFile' for writing.");
        }
        fwrite($handle, $json);
        fclose($handle);
        
    }

}