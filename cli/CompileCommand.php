<?php
namespace Grav\Plugin\Console;

use Grav\Common\Grav;
use Grav\Console\ConsoleCommand;
use \Leafo\ScssPhp\Compiler;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class CompileCommand
 *
 * @package Grav\Plugin\Console
 */
class CompileCommand extends ConsoleCommand
{
    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName("compile")
            ->setDescription("SCSS compile a specific location")
            ->addOption(
                'in',
                null,
                InputOption::VALUE_REQUIRED,
                'The path location of the SCSS source files'
            )
            ->addOption(
                'out',
                null,
                InputOption::VALUE_REQUIRED,
                'The path location of the compiled CSS files'
            )
            ->addOption(
                'base',
                null,
                InputOption::VALUE_OPTIONAL,
                'The base location for in and out paths'
            )
            ->addOption(
                'format',
                'nested',
                InputOption::VALUE_OPTIONAL,
                'The output format [expanded, nested, compressed, compact, crunched]'
            )
            ->setHelp('The <info>compile command</info> compiles SCSS source files into CSS files')
        ;
    }

    /**
     * @return int|null|void
     */
    protected function serve()
    {
        $autoload = Grav::instance()['locator']->findResource('plugins://scss/vendor/autoload.php');
        require_once($autoload);

        $this->output->writeln('');
        $this->output->writeln('<magenta>Compiling SCSS</magenta>');
        $this->output->writeln('');

        $this->compileScss($this->input->getOption('in'),
            $this->input->getOption('out'),
            $this->input->getOption('base'),
            $this->input->getOption('format'));
        $this->output->writeln('Done.');
    }

    private function compileScss($in, $out, $base, $format)
    {
        $scss = new Compiler();

        if ($format != 'nested') {
            $formatter = 'Leafo\\ScssPhp\\Formatter\\' . ucfirst($format);
            $scss->setFormatter($formatter);
        }

        $in_path = str_replace('//', '/', GRAV_ROOT . '/' .$base . '/' . $in);
        $out_path = str_replace('//', '/', GRAV_ROOT . '/' .$base . '/' . $out);

        $scss->setImportPaths(dirname($in_path));
        $input = file_get_contents($in_path);
        $output = $scss->compile($input);
        file_put_contents($out_path, $output);
    }
}

