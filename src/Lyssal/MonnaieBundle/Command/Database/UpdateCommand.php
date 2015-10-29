<?php
namespace Lyssal\MonnaieBundle\Command\Database;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Lyssal\Csv;
use Symfony\Component\Console\Command\Command;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Config\FileLocatorInterface;
use Lyssal\MonnaieBundle\Manager\MonnaieManager;

/**
 * Commande pour remplir la base de données.
 * 
 * @author Rémi Leclerc
 */
class UpdateCommand extends Command
{
    /**
     * @var \Symfony\Bridge\Doctrine\RegistryInterface Doctrine
     */
    private $doctrine;

    /**
     * @var \Symfony\Component\Config\FileLocatorInterface FileLocator
     */
    private $fileLocator;

    /**
     * @var \Lyssal\MonnaieBundle\Manager\MonnaieManager MonnaieManager
     */
    private $monnaieManager;
    
    /**
     * @var string Chemin vers le dossier de fichiers de LyssalMonnaieBundle
     */
    private $cheminLyssalMonnaieBundleFiles;

    
    /**
     * Constructeur
     * 
     * @param \Symfony\Bridge\Doctrine\RegistryInterface Doctrine $doctrine
     * @param \Symfony\Component\Config\FileLocatorInterface FileLocator $fileLocator
     * @param \Lyssal\MonnaieBundle\Manager\MonnaieManager MonnaieManager $monnaieManager
     */
    public function __construct(RegistryInterface $doctrine, FileLocatorInterface $fileLocator, MonnaieManager $monnaieManager)
    {
        $this->doctrine = $doctrine;
        $this->fileLocator = $fileLocator;
        $this->monnaieManager = $monnaieManager;
        
        parent::__construct();
    }
    
    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Console\Command\Command::configure()
     */
    protected function configure()
    {
        $this
            ->setName('lyssal:monnaie:database:import')
            ->setDescription('Vide et importe les données en base')
        ;
    }

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Console\Command\Command::execute()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->doctrine->getConnection()->getConfiguration()->setSQLLogger(null);
        $this->initCheminLyssalMonnaieBundleFiles();
        
        $this->importeMonnaies();
    }
    
    /**
     * Initialise le chemin vers le dossier de fichiers de LyssalGeographieBundle.
     *
     * @return void
     */
    private function initCheminLyssalMonnaieBundleFiles()
    {
        foreach ($this->fileLocator->locate('@LyssalMonnaieBundle', null, false) as $cheminMonnaieBundle)
        {
            if (false !== strpos($cheminMonnaieBundle, 'src/Lyssal/MonnaieBundle'))
            {
                $this->cheminLyssalMonnaieBundleFiles = $cheminMonnaieBundle.'../../../files';
                break;
            }
        }
    }
    
    /**
     * Importe en base les monnaies.
     */
    private function importeMonnaies()
    {
        $fichierCsv = new Csv($this->cheminLyssalMonnaieBundleFiles.'/csv/monnaies.csv', ',', '"');
        $fichierCsv->importe(false);
    
        $this->monnaieManager->removeAll(true);
    
        foreach ($fichierCsv->getLignes() as $ligneCsv)
        {
            $symbole = $ligneCsv[0];
            $code = $ligneCsv[1];
            
            $monnaie = $this->monnaieManager->create();
            $monnaie->setSymbole($symbole);
            $monnaie->setCode($code);
    
            $this->monnaieManager->save($monnaie);
        }
    }
}
