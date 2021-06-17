<?php

namespace App\Command;

use App\Entity\Ingredient;
use App\Entity\Plate;
use Doctrine\ORM\EntityManagerInterface;
use Facebook\WebDriver\WebDriverBy;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Panther\Client;

class CrawlingCommand extends Command
{
    protected static $defaultName = 'crawl-ing';
    protected static $defaultDescription = 'Crawl the Romarin Website';

    protected $listProt = [
        "Poulet",
        "Boeuf",
        "Agneau",
        "Jambon",
        "Poisson blanc",
        "Saumon",
        "Truite",
    ];

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }
    
    protected function configure(): void
    {
        $this
            ->addOption('addbdd', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $_client = Client::createChromeClient();

        $io->info("Connexion Site ...");
        $_client->request('GET', 'https://persiletromarin.fr/inscription/');
        $io->info("Connexion OK");
        //$_client->takeScreenshot('screen.png');
        $_crawler = $_client->waitFor('iframe');
        $myFrame = $_client->findElement(WebDriverBy::cssSelector('iframe'));
        $_client->switchTo()->frame($myFrame);
        $_client->waitFor('#form-pagebreak-next_324');
        $_client->executeScript('document.querySelector("#form-pagebreak-next_324").click()');
        //$_client->takeScreenshot('screen2.png');
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
        
        $io->info('BDD setup...');
        $progress = new ProgressBar($output, count($arrayReceipeIngredients));
        $progress->start();

        // BDD ----------------------------------------------------------------------------------------------
        if ($input->getOption('addbdd')) {
            foreach($arrayReceipeIngredients as $object) {
                $plate = $object['plateName'];
                $ingArray = $object['ingredients'];
                $newPlate = new Plate();
                $newPlate->setName($plate);
                foreach($ingArray as $ing) {
                    $ingWithoutS = substr($ing, 0 , strlen($ing) - 1);
                    $ingWithS = $ing . 's';
                    $ingRepo = $this->em->getRepository(Ingredient::class);
                    $ingFinded = $ingRepo->findOneBySomeField($ing);
                    $securePlural = $ingRepo->findOneBySomeField($ingWithS);
                    $ingFinded = $ingFinded === null ? $securePlural : $ingFinded;
                    $securePlural2 = $ingRepo->findOneBySomeField($ingWithoutS);
                    $ingFinded = $ingFinded === null ? $securePlural2 : $ingFinded;
                    if($ingFinded === null && $securePlural === null && $securePlural2 === null) {
                        if(in_array($ing, $this->listProt)) {
                            $type = "Principaux";
                        } else if(in_array($ing, $this->listLeg)) {
                            $type = "Légumes";
                        } else {
                            $type = "Condiments";
                        }
                        
                        $newIng = new Ingredient();
                        $newIng->setName($ing);
                        $newIng->setType($type);
                        $newIng->addPlate($newPlate);
                        $this->em->persist($newIng);
                        $ingFinded = $newIng;
                    }
                    
                    $newPlate->addIngredient($ingFinded);
                    $this->em->persist($newPlate);
                    $this->em->flush();
                }
                $progress->advance();
                
            }
            
        }
        // BDD ----------------------------------------------------------------------------------------------
        
        $progress->finish();
        $io->success('Terminée voici les résultats :');
        foreach($arrayReceipeIngredients as $object) {
            $io->text('
                Nom du plat : '. $object['plateName'] . "\n" .'Ingrédients : '. var_dump($object['ingredients']));
        }

        return Command::SUCCESS;
    }
}