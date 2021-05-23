<?php


namespace App\Command;


use App\Services\UpdatePricesService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateItemCommand extends Command
{
    protected static $defaultName = 'app:update-items';
    protected const DESCRIPTION = "For automate daily item quality and sell in date update";

    protected $updatePricesService;

    public function __construct(UpdatePricesService $updatePricesService, string $name = null)
    {
        $this->setDescription(self::DESCRIPTION);
        $this->updatePricesService = $updatePricesService;
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Starting to update items");
        $this->updatePricesService->updateQuality();
        $output->writeln("Items were successfully updated");
        return Command::SUCCESS;
    }

}