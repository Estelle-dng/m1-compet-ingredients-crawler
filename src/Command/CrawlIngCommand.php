<?php

namespace App\Command;

use Facebook\WebDriver\WebDriverBy;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Panther\Client;

class CrawlIngCommand extends Command
{
    protected static $defaultName = 'crawl-ing';
    protected static $defaultDescription = 'Crawl the Romarin Website';

    protected function configure(): void
    {
        $this
            ->addOption('addbdd', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if ($input->getOption('addbdd')) {
            // 
        }

        $_client = Client::createChromeClient();

        $_client->request('GET', 'https://persiletromarin.fr/inscription/');
        $_client->takeScreenshot('screen.png');
        $_crawler = $_client->waitFor('iframe');
        $myFrame = $_client->findElement(WebDriverBy::cssSelector('iframe'));
        $_client->switchTo()->frame($myFrame);
        $_client->waitFor('#form-pagebreak-next_324');
        $_client->executeScript('document.querySelector("#form-pagebreak-next_324").click()');
        $_crawler = $_client->waitFor('.form-line');
        $domIngredients = $_crawler->filter("b");
        $arrayReceipeIngredients = [];
        
        foreach($domIngredients as $weak) {
            
            $fullText = $weak->getText();
            if($fullText === "") continue;
            $noLineBreak = str_replace("\n", "|||", $fullText);
            $arraySplit = explode('|||', $noLineBreak);

            //Ajustement des nom de Plats / ingrédient
            $plateName = substr($arraySplit[0],6,trim(strlen($arraySplit[0])));
            $arrayIngredients = explode(',', $arraySplit[1]);

            foreach($arrayIngredients as $key => $in) {
                $arrayIngredients[$key] = ucfirst(trim($in));
            }

            array_push($arrayReceipeIngredients, [
                "plateName" => $plateName,
                "ingredients" => $arrayIngredients
            ]);
        }
        
        

        $io->success('OKLM');

        return Command::SUCCESS;
    }
}