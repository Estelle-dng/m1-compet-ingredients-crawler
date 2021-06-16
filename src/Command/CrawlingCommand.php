<?php

namespace App\Command;

use Facebook\WebDriver\WebDriverBy;
use Symfony\Component\Panther\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CrawlingCommand extends Command
{
    protected static $defaultName = 'ingredients-crawler';
    protected static $defaultDescription = 'ingredients crawler';

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
       
        $client = Client::createChromeClient();
       
        // Or, if you care about the open web and prefer to use Firefox
        //$client = Client::createFirefoxClient();
        
        $client->request('GET', 'https://persiletromarin.fr/inscription/'); // Yes, this website is 100% written in JavaScript
    
        $client->waitForVisibility('iframe');
        $myFrame = $client->findElement(WebDriverBy::cssSelector('iframe'));
        $client->switchTo()->frame($myFrame);
        
        // Wait for an element to be present in the DOM (even if hidden)
        $crawler = $client->waitForVisibility('#form-pagebreak-next_324');
        $client->executeScript("document.getElementById('form-pagebreak-next_324').click()");
        $crawler = $client->waitFor("label");
        //$title = $crawler->filter("label b");
        $ingredients = $crawler->filter("small .weak");
        $ingredientsList = "";
        foreach($ingredients as $ingredient){
            $ingredientsList .= $ingredient->getText();
        }
        $ingredientsList = explode(',' , $ingredientsList);
        $output->writeln($ingredientsList);
        // Alternatively, wait for an element to be visible
        //$crawler = $client->waitForVisibility('#form-pagebreak-next_324');
        //$client->clickLink('Get started');
        $client->takeScreenshot('screen.png'); // Yeah, screenshot!
        
        return Command::SUCCESS;
    }

}
