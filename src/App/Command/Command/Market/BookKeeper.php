<?php

namespace Kobens\Gemini\App\Command\Command\Market;

use Amp\Websocket\Client\ConnectionException;
use Kobens\Exchange\Exception\ClosedBookException;
use Kobens\Gemini\App\Command\Argument\Symbol;
use Kobens\Gemini\App\Command\Traits\CommandTraits;
use Kobens\Gemini\App\Config;
use Kobens\Gemini\Exchange;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BookKeeper extends Command
{
    use CommandTraits;

    protected static $defaultName = 'market:book-keeper';

    protected function configure()
    {
        $this->setDescription('Opens a market book.');
        $arg = new Symbol();
        $this->addArgument($arg->getName(), $arg->getMode(), $arg->getDescription(), $arg->getDefault());
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $symbol = $input->getArgument('symbol');
        $book = (new Exchange())->getBookKeeper($symbol);
        $host = $this->getHost();
        $loop = true;
        if (!$output->isQuiet()) {
            $output->writeln(\sprintf('Opening book "%s" from "%s"', $symbol, $host));
        }
        do {
            try {
                $book->openBook();
            } catch (ClosedBookException $e) {
                if (!$output->isQuiet()) {
                    $this->clearTerminal($output);
                    $output->writeln(\sprintf('Opening book "%s" from "%s"', $symbol, $host));
                }
            } catch (ConnectionException $e) {
                if (   $e->getMessage() === 'Websocket connection attempt failed'
                    && $this->isMaintenance()
                ) {
                    // @todo
                } else {
                    // @todo Amp\Websocket\Client\ConnectionException
                    //      'Websocket connection attempt failed'

                    // @todo Check GET https://{envHost}/ response, look for maintenance message
                    //      'Connection closed unexpectedly'
                    $this->debugAndSleep($e, $output);
                }
            } catch (\Exception $e) {
                $loop = false;
                if ($output->isQuiet()) {
                    $output->setVerbosity(OutputInterface::VERBOSITY_NORMAL);
                }
                $this->clearTerminal($output);
                $this->debugAndSleep($e, $output);
            }
        } while ($loop);
    }

    /**
     * @todo finish me
     * @todo move to single purpose class object
     *
     * @return bool
     */
    protected function isMaintenance() : bool
    {
        return false;
        $ch = \curl_init(\sprintf('https://%s', (new Config())->gemini->api->host));
        \curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,   // return web page
            CURLOPT_HEADER         => false,  // don't return headers
            CURLOPT_FOLLOWLOCATION => false,  // follow redirects
            CURLOPT_MAXREDIRS      => 10,     // stop after 10 redirects
            CURLOPT_ENCODING       => "",     // handle compressed
            CURLOPT_USERAGENT      => "test", // name of client
            CURLOPT_AUTOREFERER    => true,   // set referrer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
            CURLOPT_TIMEOUT        => 120,    // time-out on response
        ]);
    }

}
