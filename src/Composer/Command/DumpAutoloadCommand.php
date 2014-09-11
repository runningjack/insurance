<?php











namespace Composer\Command;

use Composer\Plugin\CommandEvent;
use Composer\Plugin\PluginEvents;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;




class DumpAutoloadCommand extends Command
{
protected function configure()
{
$this
->setName('dump-autoload')
->setAliases(array('dumpautoload'))
->setDescription('Dumps the autoloader')
->setDefinition(array(
new InputOption('optimize', 'o', InputOption::VALUE_NONE, 'Optimizes PSR0 packages to be loaded with classmaps too, good for production.'),
))
->setHelp(<<<EOT
<info>php composer.phar dump-autoload</info>
EOT
)
;
}

protected function execute(InputInterface $input, OutputInterface $output)
{
$output->writeln('<info>Generating autoload files</info>');

$composer = $this->getComposer();

$commandEvent = new CommandEvent(PluginEvents::COMMAND, 'dump-autoload', $input, $output);
$composer->getEventDispatcher()->dispatch($commandEvent->getName(), $commandEvent);

$installationManager = $composer->getInstallationManager();
$localRepo = $composer->getRepositoryManager()->getLocalRepository();
$package = $composer->getPackage();
$config = $composer->getConfig();

$composer->getAutoloadGenerator()->dump($config, $localRepo, $package, $installationManager, 'composer', $input->getOption('optimize'));
}
}
