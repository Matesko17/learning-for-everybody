<?php

namespace App\AdminModule\Console;

use App\AdminModule\Services\TranslationService;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\{InputArgument, InputInterface, InputOption};
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TranslationCommand.
 *
 * @author Kamil Walig <kamil.walig@q2.cz>
 * @author Jan Hermann <jan.hermann@q2.cz>
 * @package Qetteweb
 */
class TranslationCommand extends Command
{
    /**
     * @var TranslationService
     */
    private $translationService;
    
    public function __construct(TranslationService $translationService)
    {
        parent::__construct();
        
        $this->translationService = $translationService;
    }
    
    protected function configure()
    {
        $this->setName('app:translation')
            ->setDescription('Create or modify translation entities or generates translation files')
            ->setDefinition([
                new InputOption(
                    'create', 'c', InputOption::VALUE_NONE,
                    'Create or modify translation entities.'
                ),
                
                new InputOption(
                    'generate', 'g', InputOption::VALUE_NONE,
                    'Generates translation files.'
                ),
                
                new InputOption(
                    'import', 'i', InputOption::VALUE_REQUIRED,
                    'Import translations from file'
                ),
            ]);
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $create = true === $input->getOption('create');
            $generate = true === $input->getOption('generate');
            $import = true === !empty($input->getOption('import'));
            
            if($create) {
                $output->writeln('Creating translation entities...');
                $this->translationService->create();
                $output->writeln('success');
            }
            
            if($generate) {
                $output->writeln('Generates translation files....');
                $this->translationService->generate();
                $output->writeln('success');
            }
            
            if($import) {
                $output->writeln('Importing translations...');
                $this->translationService->import($input->getOption('import'));
                $output->writeln('success');
            }
            
            if($generate || $create || $import) {
                return 0; // zero return code means everything is ok
            }
        } catch(Exception $e) {
            $output->writeln('<error>'.$e->getMessage().'</error>');
            return 1; // non-zero return code means error
        }
        
        $output->writeln('<error>Missing argument</error>');
        $output->writeln(sprintf('    <info>%s --create</info> to create or modify translation entities', $this->getName()));
        $output->writeln(sprintf('    <info>%s --generate</info> to generates translation files', $this->getName()));
        $output->writeln(sprintf('    <info>%s --import</info> to import translations', $this->getName()));
        
        return 1;
    }
}
