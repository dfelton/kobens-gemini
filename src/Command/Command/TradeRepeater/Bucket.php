<?php

namespace Kobens\Gemini\Command\Command\TradeRepeater;

use Kobens\Gemini\TradeRepeater\Model\Trade\Profits\Bucket as BucketModel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Kobens\Math\BasicCalculator\Compare;
use Kobens\Gemini\Command\Traits\Output;

final class Bucket extends Command
{
    use Output;

    protected static $defaultName = 'repeater:bucket';

    private BucketModel $bucket;

    public function __construct(
        BucketModel $bucket
    ) {
        $this->bucket = $bucket;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Add / Remove funds to/from a bucket. Lists Bucket funds if neither option provided.');
        $this->addArgument('bucket', InputArgument::REQUIRED, 'Bucket to interact with');
        $this->addOption('add', null, InputOption::VALUE_OPTIONAL, 'Add given amount to a bucket. If does not exist, it will be created.', '0');
        $this->addOption('remove', null, InputOption::VALUE_OPTIONAL, 'Remove given amount from a bucket. If "add" option is provided, this option is ignored.', '0');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $exitCode = 0;
        $bucket = (string) $input->getArgument('bucket');
        $add = (string) $input->getOption('add');
        $remove = (string) $input->getOption('remove');

        if (Compare::getResult($add, '0') === Compare::LEFT_GREATER_THAN) {
            try {
                // TODO: Add Validation
                // We have validation against pulling more than what is available,
                // somehow accomplishing validation on adding would be nice as well..
                // Especially here where manual action is applied
                $this->bucket->addToBucket($bucket, $add);
            } catch (\Throwable $e) {
                $exitCode = 1;
                $this->writeError($e->getMessage(), $output);
            }
        } elseif (Compare::getResult($remove, '0') === Compare::LEFT_GREATER_THAN) {
            try {
                $this->bucket->removeFromBucket($bucket, $remove);
            } catch (\Throwable $e) {
                $exitCode = 1;
                $this->writeError($e->getMessage(), $output);
            }
        } else {
            $this->writeNotice(
                sprintf(
                    'Amount available in "%s" bucket: %s',
                    $bucket,
                    $this->bucket->get($bucket)
                ),
                $output
            );
        }

        return $exitCode;
    }
}
