<?php

namespace App\Server\Contracts;

use Symfony\Component\Console\Output\OutputInterface;

interface Logger
{
    /**
     * Get or set the output interface the server logs output to.
     *
     * @example logger() ==> \Symfony\Component\Console\Output\OutputInterface
     *          logger($interface) ==> self
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $interface
     *
     * @return \Symfony\Component\Console\Output\OutputInterface|self
     */
    public function logger(OutputInterface $interface = null);

    /**
     * Get or set if the broker should log anything.
     *
     * @example logging() ==> true
     *          logging(true) ==> self
     *
     * @param bool $enable
     *
     * @return bool|self
     */
    public function logging($enable = null);

    /**
     * Log to the output.
     *
     * @param mixed $message that can be cast to a string
     *
     * @return self
     */
    public function log($message);
}
